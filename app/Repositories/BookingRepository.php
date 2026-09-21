<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\Booking\BookingStatusEnum;
use App\Exceptions\Booking\BookingNotFoundException;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    public function __construct(protected Booking $model)
    {
    }

    public function create(array $data): Booking
    {
        return $this->model->create($data);
    }

    /**
     * @throws BookingNotFoundException
     */
    public function findByIdAndUser(int $id, int $userId): Booking
    {
        $booking = $this->model
            ->where('id', $id)
            ->where('user_id', $userId)
            ->with($this->defaultRelations(detailedBranch: true))
            ->first();

        if (!$booking) {
            throw new BookingNotFoundException();
        }

        return $this->hideRawLocalizedAttributes($booking);
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getActiveBookingsForUser(int $userId): Collection
    {
        $bookings = $this->model
            ->where('user_id', $userId)
            ->active()
            ->with($this->defaultRelations(detailedBranch: false))
            ->orderBy('pickup_at', 'asc')
            ->get();

        return $bookings->each(fn (Booking $b) => $this->hideRawLocalizedAttributes($b));
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getClosedBookingsForUser(int $userId): Collection
    {
        $bookings = $this->model
            ->where('user_id', $userId)
            ->closed()
            ->with($this->defaultRelations(detailedBranch: false))
            ->orderBy('updated_at', 'desc')
            ->get();

        return $bookings->each(fn (Booking $b) => $this->hideRawLocalizedAttributes($b));
    }

    public function hasOverlappingBooking(int $carId, string $pickupAt, string $returnAt, ?int $ignoreBookingId = null): bool
    {
        $query = $this->model
            ->where('car_id', $carId)
            ->where('status', '!=', BookingStatusEnum::CANCELLED->value)
            ->where(function ($q) use ($pickupAt, $returnAt) {
                // Check time overlap: existing.pickup_at < new.return_at AND existing.return_at > new.pickup_at
                $q->where('pickup_at', '<', $returnAt)
                  ->where('return_at', '>', $pickupAt);
            });

        if ($ignoreBookingId !== null) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return $query->exists();
    }

    public function generateBookingNumber(): string
    {
        $prefix = config('services.booking.booking_number_prefix', 'BH') . '-' . date('Y');

        $latestBooking = $this->model
            ->where('booking_number', 'like', $prefix . '-%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($latestBooking) {
            $parts = explode('-', $latestBooking->booking_number);
            $lastSequence = (int) end($parts);
            $nextNumber = $lastSequence + 1;
        }

        return sprintf('%s-%05d', $prefix, $nextNumber);
    }

    /**
     * Define default eager-loaded relationships for bookings.
     *
     * @return array<string, mixed>
     */
    protected function defaultRelations(bool $detailedBranch = false): array
    {
        $branchFields = $detailedBranch
            ? 'id,name_ar,name_en,city_id,address_ar,address_en,phone'
            : 'id,name_ar,name_en';

        return [
            'car' => function ($query) {
                $query->select([
                    'id',
                    'brand_id',
                    'model_year_id',
                    'transmission_id',
                    'fuel_type_id',
                    'name_ar',
                    'name_en',
                    'seats',
                    'daily_price',
                ])->with([
                    'brand:id,name_ar,name_en',
                    'modelYear:id,year',
                    'transmission:id,name_ar,name_en',
                    'fuelType:id,name_ar,name_en',
                    'primaryImage:id,car_id,image_path',
                ]);
            },
            'pickupBranch:' . $branchFields,
            'returnBranch:' . $branchFields,
        ];
    }

    /**
     * Hide raw multilingual database columns (_ar, _en) from serialized API responses,
     * leaving only the dynamic cast localized attributes (name, description, address).
     */
    public function hideRawLocalizedAttributes(Booking $booking): Booking
    {
        if ($booking->relationLoaded('car') && $booking->car) {
            $booking->car->makeHidden(['name_ar', 'name_en', 'description_ar', 'description_en']);

            if ($booking->car->relationLoaded('brand') && $booking->car->brand) {
                $booking->car->brand->makeHidden(['name_ar', 'name_en']);
            }

            if ($booking->car->relationLoaded('transmission') && $booking->car->transmission) {
                $booking->car->transmission->makeHidden(['name_ar', 'name_en']);
            }

            if ($booking->car->relationLoaded('fuelType') && $booking->car->fuelType) {
                $booking->car->fuelType->makeHidden(['name_ar', 'name_en']);
            }
        }

        if ($booking->relationLoaded('pickupBranch') && $booking->pickupBranch) {
            $booking->pickupBranch->makeHidden(['name_ar', 'name_en', 'address_ar', 'address_en']);
        }

        if ($booking->relationLoaded('returnBranch') && $booking->returnBranch) {
            $booking->returnBranch->makeHidden(['name_ar', 'name_en', 'address_ar', 'address_en']);
        }

        return $booking;
    }
}

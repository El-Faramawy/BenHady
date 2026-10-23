<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\Booking\BookingStatusEnum;
use App\Exceptions\Booking\BookingNotFoundException;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
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

        return $bookings->each(fn(Booking $b) => $this->hideRawLocalizedAttributes($b));
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

        return $bookings->each(fn(Booking $b) => $this->hideRawLocalizedAttributes($b));
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

    /**
     * Get DataTable query for Admin bookings list with optional status filtering.
     */
    public function getDataTableQuery(?string $statusFilter = null): Builder
    {
        $query = $this->model->newQuery()
            ->with([
                'user:id,name,phone,email',
                'car' => function ($q) {
                    $q->select(['id', 'brand_id', 'name_ar', 'name_en', 'daily_price'])
                        ->with([
                            'brand:id,name_ar,name_en',
                            'primaryImage:id,car_id,image_path',
                        ]);
                },
                'pickupBranch:id,name_ar,name_en',
                'returnBranch:id,name_ar,name_en',
            ]);

        if ($statusFilter === 'active') {
            $query->active();
        } elseif ($statusFilter === 'closed') {
            $query->closed();
        } elseif (!empty($statusFilter) && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return $query->orderByDesc('id');
    }

    /**
     * Find booking by ID with full details for showcase and invoice.
     */
    public function findWithFullDetails(int $id): Booking
    {
        return $this->model->newQuery()
            ->with([
                'user',
                'car.brand',
                'car.category',
                'car.primaryImage',
                'car.modelYear',
                'car.transmission',
                'car.fuelType',
                'pickupBranch.city',
                'returnBranch.city',
                'coupon',
            ])
            ->findOrFail($id);
    }

    /**
     * Update booking.
     *
     * @param array<string, mixed> $data
     */
    public function update(Booking $booking, array $data): bool
    {
        return $booking->update($data);
    }

    /**
     * Delete booking.
     */
    public function delete(Booking $booking): bool
    {
        return (bool) $booking->delete();
    }

    /**
     * Batch delete bookings.
     *
     * @param array<int> $ids
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->model->newQuery()->whereIn('id', $ids)->delete();
    }

    /**
     * Get total count of bookings.
     */
    public function getTotalCount(): int
    {
        return $this->model->newQuery()->count();
    }

    /**
     * Get count of bookings by status.
     *
     * @return array<string, int>
     */
    public function getStatusCounts(): array
    {
        return [
            'pending' => $this->model->newQuery()->where('status', BookingStatusEnum::PENDING->value)->count(),
            'active' => $this->model->newQuery()->where('status', BookingStatusEnum::ACTIVE->value)->count(),
            'completed' => $this->model->newQuery()->where('status', BookingStatusEnum::COMPLETED->value)->count(),
            'cancelled' => $this->model->newQuery()->where('status', BookingStatusEnum::CANCELLED->value)->count(),
        ];
    }

    /**
     * Get total revenue from active and completed bookings.
     */
    public function getTotalRevenue(): float
    {
        return (float) $this->model->newQuery()
            ->whereIn('status', [BookingStatusEnum::ACTIVE->value, BookingStatusEnum::COMPLETED->value])
            ->sum('total');
    }

    /**
     * Get monthly metrics for chart visualization.
     *
     * @return array{labels: array<string>, revenue: array<float>, bookings: array<int>}
     */
    public function getMonthlyRevenue(int $months = 6): array
    {
        $labels = [];
        $revenue = [];
        $bookings = [];

        $startOfCurrentMonth = now()->startOfMonth();
        $startDate = (clone $startOfCurrentMonth)->subMonthsNoOverflow($months - 1)->startOfMonth();

        $rows = $this->model->newQuery()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym,
                         COUNT(*) as total_count,
                         SUM(CASE WHEN status IN (?, ?) THEN total ELSE 0 END) as total_sum",
                         [BookingStatusEnum::ACTIVE->value, BookingStatusEnum::COMPLETED->value])
            ->where('created_at', '>=', $startDate)
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthDate = (clone $startOfCurrentMonth)->subMonthsNoOverflow($i);
            $ym = $monthDate->format('Y-m');

            $labels[] = $monthDate->translatedFormat('F');
            $revenue[] = isset($rows[$ym]) ? round((float) $rows[$ym]->total_sum, 2) : 0.0;
            $bookings[] = isset($rows[$ym]) ? (int) $rows[$ym]->total_count : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'bookings' => $bookings,
        ];
    }

    /**
     * Get latest bookings with relations for dashboard activity feed.
     *
     * @return Collection<int, Booking>
     */
    public function getLatestBookings(int $limit = 6): Collection
    {
        return $this->model->newQuery()
            ->with(['user', 'car.brand', 'car.primaryImage', 'pickupBranch'])
            ->latest('id')
            ->take($limit)
            ->get();
    }
}

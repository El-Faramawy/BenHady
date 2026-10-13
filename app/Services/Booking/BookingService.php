<?php

declare(strict_types=1);

namespace App\Services\Booking;

use App\Enums\Booking\BookingStatusEnum;
use App\Enums\Car\CarStatusEnum;
use App\Exceptions\Booking\BookingOverlapException;
use App\Exceptions\Booking\BranchClosedException;
use App\Exceptions\Booking\CarNotAvailableException;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Repositories\BranchRepository;
use App\Repositories\CarRepository;
use App\Services\Booking\DTO\CreateBookingDTO;
use App\Services\Coupon\CouponService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class BookingService
{
    public function __construct(
        protected BookingRepository $bookingRepository,
        protected BranchRepository $branchRepository,
        protected CarRepository $carRepository,
        protected BranchAvailabilityService $branchAvailabilityService,
        protected CouponService $couponService
    ) {
    }

    /**
     * @throws CarNotAvailableException
     * @throws BranchClosedException
     * @throws BookingOverlapException
     */
    public function createBooking(CreateBookingDTO $dto): Booking
    {
        $car = $this->carRepository->findActiveByIdOrFail($dto->carId);

        $statusValue = $car->status instanceof CarStatusEnum
            ? $car->status->value
            : (string) $car->status;

        if ($statusValue !== CarStatusEnum::AVAILABLE->value) {
            throw new CarNotAvailableException();
        }

        $pickupBranch = $this->branchRepository->findActiveById($dto->pickupBranchId);
        if (!$pickupBranch) {
            throw new BranchClosedException();
        }

        if (!$this->branchRepository->isCarAvailableAtBranch($dto->carId, $dto->pickupBranchId)) {
            throw new CarNotAvailableException();
        }

        $returnBranch = $this->branchRepository->findActiveById($dto->returnBranchId);
        if (!$returnBranch) {
            throw new BranchClosedException();
        }

        $this->branchAvailabilityService->validatePickupTime($dto->pickupBranchId, $dto->pickupAt);
        $this->branchAvailabilityService->validateReturnTime($dto->returnBranchId, $dto->returnAt);

        if ($this->bookingRepository->hasOverlappingBooking($dto->carId, $dto->pickupAt, $dto->returnAt)) {
            throw new BookingOverlapException();
        }

        $pickupCarbon = Carbon::parse($dto->pickupAt);
        $returnCarbon = Carbon::parse($dto->returnAt);
        $totalHours = $pickupCarbon->diffInHours($returnCarbon);
        $totalDays = max(1, (int) ceil($totalHours / 24));

        $dailyPrice = (float) $car->daily_price;
        $subtotal = round($dailyPrice * $totalDays, 2);

        $couponId = null;
        $couponCode = null;
        $discount = 0.00;
        $couponModel = null;

        if ($dto->couponCode !== null && trim($dto->couponCode) !== '') {
            $couponResult = $this->couponService->validateAndCalculate($dto->couponCode, $subtotal, $dto->userId);
            $discount = $couponResult->discountAmount;
            $couponId = $couponResult->coupon->id;
            $couponCode = $couponResult->coupon->code;
            $couponModel = $couponResult->coupon;
        }

        $total = max(0.00, round($subtotal - $discount, 2));

        $bookingNumber = $this->bookingRepository->generateBookingNumber();

        $booking = $this->bookingRepository->create([
            'booking_number' => $bookingNumber,
            'user_id' => $dto->userId,
            'car_id' => $dto->carId,
            'coupon_id' => $couponId,
            'coupon_code' => $couponCode,
            'pickup_branch_id' => $dto->pickupBranchId,
            'return_branch_id' => $dto->returnBranchId,
            'pickup_at' => $dto->pickupAt,
            'return_at' => $dto->returnAt,
            'daily_price' => $dailyPrice,
            'total_days' => $totalDays,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'notes' => $dto->notes,
            'status' => BookingStatusEnum::PENDING->value,
        ]);

        if ($couponModel !== null) {
            $this->couponService->recordUsage($couponModel, $dto->userId, $booking->id, $discount);
        }

        return $this->bookingRepository->findByIdAndUser($booking->id, $dto->userId);
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getActiveBookings(int $userId): Collection
    {
        return $this->bookingRepository->getActiveBookingsForUser($userId);
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getClosedBookings(int $userId): Collection
    {
        return $this->bookingRepository->getClosedBookingsForUser($userId);
    }

    public function getBookingDetails(int $bookingId, int $userId): Booking
    {
        return $this->bookingRepository->findByIdAndUser($bookingId, $userId);
    }
}

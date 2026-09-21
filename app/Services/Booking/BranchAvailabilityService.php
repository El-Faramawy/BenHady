<?php

declare(strict_types=1);

namespace App\Services\Booking;

use App\Enums\Branch\DayOfWeekEnum;
use App\Exceptions\Booking\BranchClosedException;
use App\Repositories\BranchRepository;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class BranchAvailabilityService
{
    public function __construct(protected BranchRepository $branchRepository)
    {
    }

    /**
     * @throws BranchClosedException
     */
    public function validatePickupTime(int $branchId, string|CarbonInterface $pickupAt): void
    {
        $this->validateBranchTime($branchId, $pickupAt);
    }

    /**
     * @throws BranchClosedException
     */
    public function validateReturnTime(int $branchId, string|CarbonInterface $returnAt): void
    {
        $this->validateBranchTime($branchId, $returnAt);
    }

    /**
     * @throws BranchClosedException
     */
    protected function validateBranchTime(int $branchId, string|CarbonInterface $dateTime): void
    {
        $timezone = (string) config('app.timezone', 'Asia/Riyadh');
        $carbon = is_string($dateTime)
            ? Carbon::parse($dateTime, $timezone)
            : $dateTime->copy()->setTimezone($timezone);

        $dayOfWeek = DayOfWeekEnum::fromDate($carbon)->value;
        $workingHour = $this->branchRepository->getWorkingHour($branchId, $dayOfWeek);

        if (!$workingHour || !$workingHour->is_open) {
            throw new BranchClosedException();
        }

        if ($workingHour->is_24_hours) {
            return;
        }

        $openTime = $workingHour->reservation_start_at ?? $workingHour->open_at;
        $closeTime = $workingHour->reservation_close_at ?? $workingHour->close_at;

        if ($openTime === null || $closeTime === null) {
            return;
        }

        $checkTime = $carbon->format('H:i');
        $start = substr((string) $openTime, 0, 5);
        $end = substr((string) $closeTime, 0, 5);

        $isOpen = $start <= $end
            ? ($checkTime >= $start && $checkTime <= $end)
            : ($checkTime >= $start || $checkTime <= $end);

        if (!$isOpen) {
            throw new BranchClosedException();
        }
    }
}

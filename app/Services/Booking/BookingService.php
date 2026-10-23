<?php

declare(strict_types=1);

namespace App\Services\Booking;

use App\Enums\Booking\BookingStatusEnum;
use App\Enums\Branch\DayOfWeekEnum;
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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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

    /**
     * Get DataTable response for Admin bookings list.
     */
    public function getDataTable(?string $statusFilter = null): JsonResponse
    {
        $query = $this->bookingRepository->getDataTableQuery($statusFilter);

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Booking $booking): string => tableCheckbox($booking->id))
            ->addColumn('booking_number_badge', function (Booking $booking): string {
                return '<a href="' . route('bookings.show', $booking->id) . '" class="font-weight-bold text-primary font-size-13">' .
                    htmlspecialchars($booking->booking_number, ENT_QUOTES, 'UTF-8') . '</a>';
            })
            ->addColumn('user_info', function (Booking $booking): string {
                $user = $booking->user;
                if ($user) {
                    return '<div class="font-weight-bold text-dark font-size-13">' . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . '</div>' .
                           '<div class="text-muted font-size-12" dir="ltr" style="margin-top: 2px;"><i class="fa fa-phone ml-1"></i>' . htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8') . '</div>';
                }
                return '<span class="text-muted font-size-12">مستخدم محذوف</span>';
            })
            ->addColumn('car_info', function (Booking $booking): string {
                $car = $booking->car;
                $defaultImg = asset('Admin/imgs/default.jpg');
                if ($car) {
                    $img = $car->primaryImage?->image_path ? get_file($car->primaryImage->image_path) : $defaultImg;
                    return '<div class="d-flex align-items-center">' .
                           '<img src="' . htmlspecialchars((string) $img, ENT_QUOTES, 'UTF-8') . '" class="shadow-sm rounded ml-2" style="width: 50px; height: 36px; object-fit: cover; border: 1px solid #e2e8f0;" onerror="this.onerror=null;this.src=\'' . $defaultImg . '\';" alt="car">' .
                           '<div>' .
                               '<div class="font-weight-bold text-dark font-size-13">' . htmlspecialchars($car->name_ar, ENT_QUOTES, 'UTF-8') . '</div>' .
                               '<small class="text-muted" dir="ltr">' . htmlspecialchars($car->name_en ?? '', ENT_QUOTES, 'UTF-8') . '</small>' .
                           '</div>' .
                           '</div>';
                }
                return '<span class="text-muted font-size-12">سيارة محذوفة</span>';
            })
            ->addColumn('branches_formatted', function (Booking $booking): string {
                $pickup = htmlspecialchars($booking->pickupBranch?->name_ar ?? '-', ENT_QUOTES, 'UTF-8');
                $return = htmlspecialchars($booking->returnBranch?->name_ar ?? '-', ENT_QUOTES, 'UTF-8');
                return '<div class="font-size-12 mb-1"><span class="badge badge-light-success text-success px-2 py-1 font-weight-bold"><i class="fa fa-arrow-down ml-1"></i>استلام: ' . $pickup . '</span></div>' .
                       '<div class="font-size-12"><span class="badge badge-light-primary text-primary px-2 py-1 font-weight-bold"><i class="fa fa-arrow-up ml-1"></i>تسليم: ' . $return . '</span></div>';
            })
            ->addColumn('period_formatted', function (Booking $booking): string {
                $pickupAt = $booking->pickup_at?->format('Y-m-d H:i') ?? '-';
                $returnAt = $booking->return_at?->format('Y-m-d H:i') ?? '-';
                return '<div class="font-size-12 text-dark font-weight-bold">' . $pickupAt . '</div>' .
                       '<div class="font-size-12 text-muted">' . $returnAt . '</div>' .
                       '<span class="badge badge-info px-2 py-0 mt-1 font-size-11">' . $booking->total_days . ' يوم</span>';
            })
            ->addColumn('pricing_formatted', function (Booking $booking): string {
                $total = '<strong class="text-success font-size-14">' . number_format((float) $booking->total, 2) . '</strong> <small class="text-muted">ر.س</small>';
                if ((float) $booking->discount > 0) {
                    $total .= '<br><small class="text-danger">خصم: ' . number_format((float) $booking->discount, 2) . ' ر.س</small>';
                }
                return $total;
            })
            ->addColumn('status_badge', function (Booking $booking): string {
                $statusVal = $booking->status instanceof BookingStatusEnum ? $booking->status->value : (string) $booking->status;
                return match ($statusVal) {
                    'pending'   => '<span class="badge badge-warning px-3 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-clock-o ml-1"></i>قيد الانتظار</span>',
                    'active'    => '<span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-car ml-1"></i>نشط</span>',
                    'completed' => '<span class="badge badge-info px-3 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-check-circle ml-1"></i>مكتمل</span>',
                    'cancelled' => '<span class="badge badge-danger px-3 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-times-circle ml-1"></i>ملغي</span>',
                    default     => '<span class="badge badge-secondary px-3 py-1 font-weight-bold" style="border-radius: 6px;">' . htmlspecialchars($statusVal, ENT_QUOTES, 'UTF-8') . '</span>',
                };
            })
            ->addColumn('created_at_formatted', fn (Booking $booking): string => $booking->created_at?->format('Y-m-d H:i') ?? '-')
            ->addColumn('action', function (Booking $booking): string {
                $detailsBtn = '<a href="' . route('bookings.show', $booking->id) . '" class="btn btn-default btn-action-details btn-sm mb-2 mb-xl-0" data-toggle="tooltip" title="تفاصيل الحجز الكاملة"><i class="fa fa-eye text-white"></i></a> ';
                $invoiceBtn = '<a href="' . route('bookings.invoice', $booking->id) . '" class="btn btn-default btn-action-view btn-sm mb-2 mb-xl-0" data-toggle="tooltip" title="عرض وطباعة الفاتورة"><i class="fa fa-file-text-o text-white"></i></a> ';
                return $detailsBtn . $invoiceBtn . tableAction($booking->id, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a new booking from Admin.
     *
     * @param array<string, mixed> $data
     * @throws BranchClosedException
     * @throws CarNotAvailableException
     * @throws BookingOverlapException
     */
    public function storeFromAdmin(array $data): Booking
    {
        $car = $this->carRepository->findActiveByIdOrFail((int) $data['car_id']);

        $pickupBranch = $this->branchRepository->findActiveById((int) $data['pickup_branch_id']);
        if (!$pickupBranch) {
            throw new BranchClosedException();
        }

        if (!$this->branchRepository->isCarAvailableAtBranch((int) $data['car_id'], (int) $data['pickup_branch_id'])) {
            throw new CarNotAvailableException();
        }

        $returnBranch = $this->branchRepository->findActiveById((int) $data['return_branch_id']);
        if (!$returnBranch) {
            throw new BranchClosedException();
        }

        $this->branchAvailabilityService->validatePickupTime((int) $data['pickup_branch_id'], $data['pickup_at']);
        $this->branchAvailabilityService->validateReturnTime((int) $data['return_branch_id'], $data['return_at']);

        if ($this->bookingRepository->hasOverlappingBooking((int) $data['car_id'], (string) $data['pickup_at'], (string) $data['return_at'])) {
            throw new BookingOverlapException();
        }

        $pickupCarbon = Carbon::parse($data['pickup_at']);
        $returnCarbon = Carbon::parse($data['return_at']);
        $totalHours = $pickupCarbon->diffInHours($returnCarbon);
        $totalDays = max(1, (int) ceil($totalHours / 24));
        $data['total_days'] = $totalDays;

        if (empty($data['daily_price'])) {
            $data['daily_price'] = (float) $car->daily_price;
        } else {
            $data['daily_price'] = (float) $data['daily_price'];
        }

        $subtotal = round($data['daily_price'] * $totalDays, 2);
        $data['subtotal'] = $subtotal;

        $discount = isset($data['discount']) ? (float) $data['discount'] : 0.00;
        $data['discount'] = $discount;
        $data['total'] = max(0.00, round($subtotal - $discount, 2));

        if (empty($data['booking_number'])) {
            $data['booking_number'] = $this->bookingRepository->generateBookingNumber();
        }

        if (empty($data['status'])) {
            $data['status'] = BookingStatusEnum::PENDING->value;
        }

        $booking = $this->bookingRepository->create($data);

        $statusVal = $booking->status instanceof BookingStatusEnum ? $booking->status->value : (string) $booking->status;
        if ($statusVal === BookingStatusEnum::COMPLETED->value) {
            $this->syncCarLocationOnCompletion($booking);
        }

        return $booking;
    }

    /**
     * Update an existing booking from Admin.
     *
     * @param array<string, mixed> $data
     * @throws BranchClosedException
     * @throws CarNotAvailableException
     * @throws BookingOverlapException
     */
    public function updateFromAdmin(Booking $booking, array $data): bool
    {
        $carId = isset($data['car_id']) ? (int) $data['car_id'] : (int) $booking->car_id;
        $pickupBranchId = isset($data['pickup_branch_id']) ? (int) $data['pickup_branch_id'] : (int) $booking->pickup_branch_id;
        $returnBranchId = isset($data['return_branch_id']) ? (int) $data['return_branch_id'] : (int) $booking->return_branch_id;
        $pickupAt = $data['pickup_at'] ?? $booking->pickup_at?->format('Y-m-d H:i:s');
        $returnAt = $data['return_at'] ?? $booking->return_at?->format('Y-m-d H:i:s');

        if (isset($data['pickup_branch_id']) || isset($data['car_id'])) {
            if (!$this->branchRepository->isCarAvailableAtBranch($carId, $pickupBranchId)) {
                throw new CarNotAvailableException();
            }
        }

        if (isset($data['pickup_branch_id']) || isset($data['pickup_at'])) {
            $this->branchAvailabilityService->validatePickupTime($pickupBranchId, $pickupAt);
        }

        if (isset($data['return_branch_id']) || isset($data['return_at'])) {
            $this->branchAvailabilityService->validateReturnTime($returnBranchId, $returnAt);
        }

        if (isset($data['pickup_at']) || isset($data['return_at']) || isset($data['car_id'])) {
            if ($this->bookingRepository->hasOverlappingBooking($carId, (string) $pickupAt, (string) $returnAt, $booking->id)) {
                throw new BookingOverlapException();
            }
        }

        if (isset($data['pickup_at'], $data['return_at'])) {
            $pickupCarbon = Carbon::parse($data['pickup_at']);
            $returnCarbon = Carbon::parse($data['return_at']);
            $totalHours = $pickupCarbon->diffInHours($returnCarbon);
            $totalDays = max(1, (int) ceil($totalHours / 24));
            $data['total_days'] = $totalDays;

            $dailyPrice = isset($data['daily_price']) ? (float) $data['daily_price'] : (float) $booking->daily_price;
            $data['daily_price'] = $dailyPrice;

            $subtotal = round($dailyPrice * $totalDays, 2);
            $data['subtotal'] = $subtotal;

            $discount = isset($data['discount']) ? (float) $data['discount'] : (float) $booking->discount;
            $data['discount'] = $discount;
            $data['total'] = max(0.00, round($subtotal - $discount, 2));
        }

        $result = $this->bookingRepository->update($booking, $data);

        $booking->refresh();
        $statusVal = $booking->status instanceof BookingStatusEnum ? $booking->status->value : (string) $booking->status;
        if ($statusVal === BookingStatusEnum::COMPLETED->value) {
            $this->syncCarLocationOnCompletion($booking);
        }

        return $result;
    }

    /**
     * Complete a booking from API or system and relocate car if needed.
     */
    public function completeBooking(int $bookingId, int $userId): Booking
    {
        $booking = $this->bookingRepository->findByIdAndUser($bookingId, $userId);

        $this->bookingRepository->update($booking, [
            'status' => BookingStatusEnum::COMPLETED->value,
        ]);

        $booking->refresh();
        $this->syncCarLocationOnCompletion($booking);

        return $booking;
    }

    /**
     * When a booking is completed, update car branch if pickup != return branch.
     * Replaces the pickup branch row with return branch in car_branch pivot table.
     */
    public function syncCarLocationOnCompletion(Booking $booking): void
    {
        $pickupBranchId = (int) $booking->pickup_branch_id;
        $returnBranchId = (int) $booking->return_branch_id;

        if ($pickupBranchId === $returnBranchId) {
            return;
        }

        $car = $booking->car ?? $this->carRepository->findActiveByIdOrFail((int) $booking->car_id);
        if (!$car) {
            return;
        }

        // 1. Update the car's current branch_id to return_branch_id
        $car->branch_id = $returnBranchId;
        $car->save();

        // 2. In car_branch pivot table: replace the row that has pickup_branch_id with return_branch_id
        $returnBranchExists = DB::table('car_branch')
            ->where('car_id', $car->id)
            ->where('branch_id', $returnBranchId)
            ->exists();

        if ($returnBranchExists) {
            DB::table('car_branch')
                ->where('car_id', $car->id)
                ->where('branch_id', $pickupBranchId)
                ->delete();
        } else {
            $pickupBranchExists = DB::table('car_branch')
                ->where('car_id', $car->id)
                ->where('branch_id', $pickupBranchId)
                ->exists();

            if ($pickupBranchExists) {
                DB::table('car_branch')
                    ->where('car_id', $car->id)
                    ->where('branch_id', $pickupBranchId)
                    ->update([
                        'branch_id' => $returnBranchId,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('car_branch')->insert([
                    'car_id' => $car->id,
                    'branch_id' => $returnBranchId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Calculate earliest available pickup date & time for a car at a specific branch.
     *
     * @return array{pickup_at: string, return_at: string, min_pickup_at: string}
     */
    public function getEarliestAvailablePickup(int $carId, int $branchId, ?int $ignoreBookingId = null): array
    {
        $timezone = (string) config('app.timezone', 'Asia/Riyadh');
        $now = Carbon::now($timezone);

        // Find existing future/active bookings for this car
        $latestBooking = Booking::query()
            ->where('car_id', $carId)
            ->whereIn('status', [BookingStatusEnum::PENDING->value, BookingStatusEnum::ACTIVE->value])
            ->when($ignoreBookingId !== null, fn($q) => $q->where('id', '!=', $ignoreBookingId))
            ->where('return_at', '>', $now)
            ->orderByDesc('return_at')
            ->first();

        $baseTime = $latestBooking && $latestBooking->return_at
            ? Carbon::parse($latestBooking->return_at, $timezone)
            : $now->copy();

        if ($baseTime->lessThan($now)) {
            $baseTime = $now->copy();
        }

        // Loop up to 14 days to find the earliest slot when the branch is open and within reservation hours
        $candidateDate = $baseTime->copy();
        $foundSlot = null;

        for ($day = 0; $day < 14; $day++) {
            $checkDate = $candidateDate->copy()->addDays($day);
            $dayOfWeek = DayOfWeekEnum::fromDate($checkDate)->value;
            $workingHour = $this->branchRepository->getWorkingHour($branchId, $dayOfWeek);

            if (!$workingHour || !$workingHour->is_open) {
                continue;
            }

            if ($workingHour->is_24_hours) {
                if ($day === 0) {
                    $earliestHour = $baseTime->copy()->addHour()->startOfHour();
                    $foundSlot = $earliestHour->lessThan($now) ? $now->copy()->addHour()->startOfHour() : $earliestHour;
                } else {
                    $foundSlot = $checkDate->copy()->setTime(9, 0, 0);
                }
                break;
            }

            $openStr = $workingHour->reservation_start_at ?? $workingHour->open_at ?? '08:00';
            $closeStr = $workingHour->reservation_close_at ?? $workingHour->close_at ?? '23:00';

            [$startH, $startM] = array_map('intval', explode(':', substr((string) $openStr, 0, 5)));
            [$closeH, $closeM] = array_map('intval', explode(':', substr((string) $closeStr, 0, 5)));

            $dayStart = $checkDate->copy()->setTime($startH, $startM, 0);
            $dayClose = $checkDate->copy()->setTime($closeH, $closeM, 0);

            if ($day === 0) {
                $targetTime = $baseTime->copy()->addMinutes(30)->ceilHour();
                if ($targetTime->lessThan($dayStart)) {
                    $foundSlot = $dayStart;
                    break;
                }
                if ($targetTime->lessThanOrEqualTo($dayClose)) {
                    $foundSlot = $targetTime;
                    break;
                }
            } else {
                $foundSlot = $dayStart;
                break;
            }
        }

        if (!$foundSlot) {
            $foundSlot = $now->copy()->addHour()->startOfHour();
        }

        $returnSlot = $foundSlot->copy()->addDays(2);

        return [
            'pickup_at'     => $foundSlot->format('Y-m-d\TH:i'),
            'return_at'     => $returnSlot->format('Y-m-d\TH:i'),
            'min_pickup_at' => $now->format('Y-m-d\TH:i'),
        ];
    }

    /**
     * Delete booking.
     */
    public function delete(Booking $booking): bool
    {
        return $this->bookingRepository->delete($booking);
    }

    /**
     * Batch delete bookings.
     *
     * @param array<int> $ids
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->bookingRepository->deleteMultiple($ids);
    }

    /**
     * Get booking with full relations for showcase and invoice.
     */
    public function getBookingWithFullDetails(int $id): Booking
    {
        return $this->bookingRepository->findWithFullDetails($id);
    }
}

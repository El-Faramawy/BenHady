<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Booking\StoreBookingRequest;
use App\Http\Requests\Admin\Booking\UpdateBookingRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Setting;
use App\Models\User;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $statusFilter = $request->query('status_filter');
            return $this->bookingService->getDataTable($statusFilter ? (string) $statusFilter : null);
        }

        return view('Admin.Booking.index');
    }

    public function create(): View
    {
        $users = User::query()->select(['id', 'name', 'phone'])->orderBy('name')->get();
        $cars = Car::query()
            ->where('is_active', true)
            ->with(['branches:id,name_ar', 'branch:id,name_ar'])
            ->select(['id', 'branch_id', 'name_ar', 'name_en', 'daily_price'])
            ->orderBy('name_ar')
            ->get();
        $branches = Branch::query()->where('is_active', true)->select(['id', 'name_ar'])->orderBy('name_ar')->get();

        $cars->each(function (Car $car) {
            $list = $car->branches->map(fn($b) => ['id' => $b->id, 'name' => $b->name_ar])->values()->all();
            if (empty($list) && $car->branch) {
                $list = [['id' => $car->branch->id, 'name' => $car->branch->name_ar]];
            } elseif (!empty($list) && $car->branch && !collect($list)->contains('id', $car->branch->id)) {
                $list[] = ['id' => $car->branch->id, 'name' => $car->branch->name_ar];
            }
            $car->setAttribute('branches_json', json_encode($list, JSON_UNESCAPED_UNICODE));
        });

        return view('Admin.Booking.parts.create', compact('users', 'cars', 'branches'));
    }

    public function getCarAvailability(Request $request): JsonResponse
    {
        $carId = (int) $request->query('car_id');
        $branchId = (int) $request->query('branch_id');
        $ignoreBookingId = $request->filled('ignore_booking_id') ? (int) $request->query('ignore_booking_id') : null;

        if (!$carId || !$branchId) {
            return response()->json(['error' => 'Missing parameters'], 422);
        }

        $availability = $this->bookingService->getEarliestAvailablePickup($carId, $branchId, $ignoreBookingId);

        return response()->json($availability);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $this->bookingService->storeFromAdmin($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function show(int $id): View
    {
        $booking = $this->bookingService->getBookingWithFullDetails($id);

        return view('Admin.Booking.show', compact('booking'));
    }

    public function invoice(int $id): View
    {
        $booking = $this->bookingService->getBookingWithFullDetails($id);
        $setting = Setting::first();

        return view('Admin.Booking.invoice', compact('booking', 'setting'));
    }

    public function edit(Booking $booking): View
    {
        $users = User::query()->select(['id', 'name', 'phone'])->orderBy('name')->get();
        $cars = Car::query()
            ->with(['branches:id,name_ar', 'branch:id,name_ar'])
            ->select(['id', 'branch_id', 'name_ar', 'name_en', 'daily_price'])
            ->orderBy('name_ar')
            ->get();
        $branches = Branch::query()->select(['id', 'name_ar'])->orderBy('name_ar')->get();

        $cars->each(function (Car $car) {
            $list = $car->branches->map(fn($b) => ['id' => $b->id, 'name' => $b->name_ar])->values()->all();
            if (empty($list) && $car->branch) {
                $list = [['id' => $car->branch->id, 'name' => $car->branch->name_ar]];
            } elseif (!empty($list) && $car->branch && !collect($list)->contains('id', $car->branch->id)) {
                $list[] = ['id' => $car->branch->id, 'name' => $car->branch->name_ar];
            }
            $car->setAttribute('branches_json', json_encode($list, JSON_UNESCAPED_UNICODE));
        });

        return view('Admin.Booking.parts.edit', compact('booking', 'users', 'cars', 'branches'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        $this->bookingService->updateFromAdmin($booking, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $this->bookingService->delete($booking);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->bookingService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}

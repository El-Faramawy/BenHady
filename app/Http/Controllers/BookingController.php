<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\Messages\BookingMessages;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Responses\ApiResponse;
use App\Services\Booking\BookingService;
use App\Services\Booking\DTO\CreateBookingDTO;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        $userId = (int) auth()->id();
        $dto = CreateBookingDTO::fromRequest($request, $userId);
        $booking = $this->bookingService->createBooking($dto);

        return (new ApiResponse())
            ->setCode(201)
            ->setData($booking)
            ->setMessages([BookingMessages::created()])
            ->create();
    }

    public function activeBookings(): JsonResponse
    {
        $userId = (int) auth()->id();
        $bookings = $this->bookingService->getActiveBookings($userId);

        return (new ApiResponse())
            ->setData($bookings)
            ->create();
    }

    public function closedBookings(): JsonResponse
    {
        $userId = (int) auth()->id();
        $bookings = $this->bookingService->getClosedBookings($userId);

        return (new ApiResponse())
            ->setData($bookings)
            ->create();
    }

    public function show(int $id): JsonResponse
    {
        $userId = (int) auth()->id();
        $booking = $this->bookingService->getBookingDetails($id, $userId);

        return (new ApiResponse())
            ->setData($booking)
            ->create();
    }
}

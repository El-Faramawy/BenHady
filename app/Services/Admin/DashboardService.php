<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Repositories\BookingRepository;
use App\Repositories\BranchRepository;
use App\Repositories\CarRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function __construct(
        protected BookingRepository $bookingRepository,
        protected CarRepository $carRepository,
        protected UserRepository $userRepository,
        protected BranchRepository $branchRepository,
        protected CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * Compile comprehensive statistics and chart metrics for admin dashboard.
     *
     * @return array<string, mixed>
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('admin_dashboard_stats', 30, function () {
            $totalBookings = $this->bookingRepository->getTotalCount();
        $bookingStatusCounts = $this->bookingRepository->getStatusCounts();
        $totalCars = $this->carRepository->getTotalCount();
        $carStatusCounts = $this->carRepository->getStatusCounts();
        $totalUsers = $this->userRepository->getTotalCount();
        $totalBranches = $this->branchRepository->getTotalCount();
        $totalRevenue = $this->bookingRepository->getTotalRevenue();

        $completedBookings = $bookingStatusCounts['completed'] ?? 0;
        $completionRate = $totalBookings > 0
            ? round(($completedBookings / $totalBookings) * 100, 1)
            : 0.0;

        $rentedCars = $carStatusCounts['rented'] ?? 0;
        $utilizationRate = $totalCars > 0
            ? round(($rentedCars / $totalCars) * 100, 1)
            : 0.0;

        return [
            'totals' => [
                'revenue' => $totalRevenue,
                'bookings' => $totalBookings,
                'cars' => $totalCars,
                'users' => $totalUsers,
                'branches' => $totalBranches,
            ],
            'rates' => [
                'completion_rate' => $completionRate,
                'utilization_rate' => $utilizationRate,
            ],
            'booking_statuses' => $bookingStatusCounts,
            'car_statuses' => $carStatusCounts,
            'monthly_chart' => $this->bookingRepository->getMonthlyRevenue(6),
            'category_distribution' => $this->carRepository->getCategoryDistribution(),
            'top_cars' => $this->carRepository->getTopRentedCars(5),
            'latest_bookings' => $this->bookingRepository->getLatestBookings(6),
            'recent_users' => $this->userRepository->getRecentUsers(5),
            'branches_summary' => $this->branchRepository->getBranchesSummary(5),
        ];
        });
    }
}

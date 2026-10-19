<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Branch\UpdateBranchWorkingHoursRequest;
use App\Models\Branch;
use App\Services\Branch\BranchService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BranchWorkingHourController extends Controller
{
    public function __construct(protected BranchService $branchService)
    {
    }

    public function edit(Branch $branch): View
    {
        $existing = $this->branchService->getWorkingHours($branch)
            ->keyBy(fn ($item): string => is_object($item->day_of_week) ? $item->day_of_week->value : (string) $item->day_of_week);

        $days = [
            'saturday'  => __('messages.days.saturday'),
            'sunday'    => __('messages.days.sunday'),
            'monday'    => __('messages.days.monday'),
            'tuesday'   => __('messages.days.tuesday'),
            'wednesday' => __('messages.days.wednesday'),
            'thursday'  => __('messages.days.thursday'),
            'friday'    => __('messages.days.friday'),
        ];

        $hours = [];
        foreach ($days as $dayKey => $dayLabel) {
            $record = $existing->get($dayKey);
            $hours[$dayKey] = [
                'day_of_week'          => $dayKey,
                'label'                => $dayLabel,
                'is_open'              => $record ? (bool) $record->is_open : true,
                'is_24_hours'          => $record ? (bool) $record->is_24_hours : false,
                'open_at'              => $record && $record->open_at ? substr((string) $record->open_at, 0, 5) : '08:00',
                'close_at'             => $record && $record->close_at ? substr((string) $record->close_at, 0, 5) : '23:00',
                'reservation_start_at' => $record && $record->reservation_start_at ? substr((string) $record->reservation_start_at, 0, 5) : '08:00',
                'reservation_close_at' => $record && $record->reservation_close_at ? substr((string) $record->reservation_close_at, 0, 5) : '23:00',
            ];
        }

        return view('Admin.Branch.parts.working_hours', compact('branch', 'hours'));
    }

    public function update(UpdateBranchWorkingHoursRequest $request, Branch $branch): JsonResponse
    {
        $this->branchService->updateWorkingHours($branch, $request->validated('working_hours'));

        return response()->json(['message' => __('messages.general.updated_success')]);
    }
}

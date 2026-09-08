<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Branch\DayOfWeekEnum;
use App\Models\Branch;
use App\Models\BranchWorkingHour;
use App\Models\City;
use Illuminate\Database\Seeder;

class CityAndBranchSeeder extends Seeder
{
    public function run(): void
    {
        $citiesWithBranches = [
            [
                'city' => ['name_ar' => 'الرياض', 'name_en' => 'Riyadh'],
                'branches' => [
                    [
                        'name_ar' => 'فرع مطار الملك خالد',
                        'name_en' => 'King Khalid Airport Branch',
                        'address_ar' => 'مطار الملك خالد الدولي، الصالة 5، الرياض',
                        'address_en' => 'King Khalid International Airport, Terminal 5, Riyadh',
                        'latitude' => 24.9576,
                        'longitude' => 46.6988,
                        'phone' => '+966112233441',
                        'type' => 'airport',
                    ],
                    [
                        'name_ar' => 'فرع طريق الملك فهد',
                        'name_en' => 'King Fahd Road Branch',
                        'address_ar' => 'طريق الملك فهد، حي العليا، الرياض',
                        'address_en' => 'King Fahd Road, Al Olaya, Riyadh',
                        'latitude' => 24.7136,
                        'longitude' => 46.6753,
                        'phone' => '+966112233442',
                        'type' => 'friday_off',
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'جدة', 'name_en' => 'Jeddah'],
                'branches' => [
                    [
                        'name_ar' => 'فرع مطار الملك عبد العزيز',
                        'name_en' => 'King Abdulaziz Airport Branch',
                        'address_ar' => 'مطار الملك عبد العزيز الدولي، الصالة 1، جدة',
                        'address_en' => 'King Abdulaziz International Airport, Terminal 1, Jeddah',
                        'latitude' => 21.6796,
                        'longitude' => 39.1565,
                        'phone' => '+966122334441',
                        'type' => 'airport',
                    ],
                    [
                        'name_ar' => 'فرع طريق التحلية',
                        'name_en' => 'Tahlia Street Branch',
                        'address_ar' => 'طريق الأمير محمد بن عبد العزيز (التحلية)، جدة',
                        'address_en' => 'Prince Mohammed Bin Abdulaziz St (Tahlia), Jeddah',
                        'latitude' => 21.5540,
                        'longitude' => 39.1670,
                        'phone' => '+966122334442',
                        'type' => 'friday_saturday_off',
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'الدمام', 'name_en' => 'Dammam'],
                'branches' => [
                    [
                        'name_ar' => 'فرع مطار الملك فهد',
                        'name_en' => 'King Fahd Airport Branch',
                        'address_ar' => 'مطار الملك فهد الدولي، الدمام',
                        'address_en' => 'King Fahd International Airport, Dammam',
                        'latitude' => 26.4712,
                        'longitude' => 49.7979,
                        'phone' => '+966132233441',
                        'type' => 'airport',
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'المدينة المنورة', 'name_en' => 'Madinah'],
                'branches' => [
                    [
                        'name_ar' => 'فرع مطار الأمير محمد بن عبد العزيز',
                        'name_en' => 'Prince Mohammad Bin Abdulaziz Airport Branch',
                        'address_ar' => 'مطار الأمير محمد بن عبد العزيز الدولي، المدينة المنورة',
                        'address_en' => 'Prince Mohammad Bin Abdulaziz International Airport, Madinah',
                        'latitude' => 24.5534,
                        'longitude' => 39.7051,
                        'phone' => '+966142233441',
                        'type' => 'airport',
                    ],
                ],
            ],
        ];

        foreach ($citiesWithBranches as $item) {
            $city = City::firstOrCreate(
                ['name_en' => $item['city']['name_en']],
                ['name_ar' => $item['city']['name_ar'], 'is_active' => true]
            );

            foreach ($item['branches'] as $branchData) {
                $branch = Branch::firstOrCreate(
                    ['city_id' => $city->id, 'name_en' => $branchData['name_en']],
                    [
                        'name_ar' => $branchData['name_ar'],
                        'address_ar' => $branchData['address_ar'],
                        'address_en' => $branchData['address_en'],
                        'latitude' => $branchData['latitude'],
                        'longitude' => $branchData['longitude'],
                        'phone' => $branchData['phone'],
                        'is_active' => true,
                    ]
                );

                $this->seedWorkingHours($branch, $branchData['type'] ?? 'standard');
            }
        }
    }

    protected function seedWorkingHours(Branch $branch, string $type): void
    {
        $days = DayOfWeekEnum::cases();

        foreach ($days as $day) {
            $schedule = match ($type) {
                'airport' => [
                    'is_open' => true,
                    'opening_time' => '00:00:00',
                    'closing_time' => '23:59:59',
                    'is_24_hours' => true,
                ],
                'friday_off' => match ($day) {
                    DayOfWeekEnum::FRIDAY => [
                        'is_open' => false,
                        'opening_time' => null,
                        'closing_time' => null,
                        'is_24_hours' => false,
                    ],
                    default => [
                        'is_open' => true,
                        'opening_time' => '08:00:00',
                        'closing_time' => '23:00:00',
                        'is_24_hours' => false,
                    ],
                },
                'friday_saturday_off' => match ($day) {
                    DayOfWeekEnum::FRIDAY, DayOfWeekEnum::SATURDAY => [
                        'is_open' => false,
                        'opening_time' => null,
                        'closing_time' => null,
                        'is_24_hours' => false,
                    ],
                    default => [
                        'is_open' => true,
                        'opening_time' => '09:00:00',
                        'closing_time' => '22:00:00',
                        'is_24_hours' => false,
                    ],
                },
                default => match ($day) {
                    DayOfWeekEnum::FRIDAY => [
                        'is_open' => false,
                        'opening_time' => null,
                        'closing_time' => null,
                        'is_24_hours' => false,
                    ],
                    default => [
                        'is_open' => true,
                        'opening_time' => '01:00:00',
                        'closing_time' => '20:00:00',
                        'is_24_hours' => false,
                    ],
                },
            };

            BranchWorkingHour::updateOrCreate(
                [
                    'branch_id' => $branch->id,
                    'day_of_week' => $day->value,
                ],
                $schedule
            );
        }
    }
}

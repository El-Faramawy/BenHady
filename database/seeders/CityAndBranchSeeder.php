<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchWorkingHour;
use App\Models\City;
use Illuminate\Database\Seeder;

class CityAndBranchSeeder extends Seeder
{
    public function run(): void
    {
        // Standard Working Hours helper maps with open_at, close_at, reservation_start_at, reservation_close_at
        $standardWorkHours = [
            'saturday'  => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '23:00:00'],
            'sunday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '23:00:00'],
            'monday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '23:00:00'],
            'tuesday'   => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '23:00:00'],
            'wednesday' => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '23:00:00'],
            'thursday'  => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '23:00:00'],
            'friday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '16:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '16:00:00', 'reservation_close_at' => '23:00:00'],
        ];

        $twentyFourHours = [
            'saturday'  => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
            'sunday'    => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
            'monday'    => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
            'tuesday'   => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
            'wednesday' => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
            'thursday'  => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
            'friday'    => ['is_open' => true, 'is_24_hours' => true, 'open_at' => '00:00:00', 'close_at' => '23:59:59', 'reservation_start_at' => '00:00:00', 'reservation_close_at' => '23:59:59'],
        ];

        $fridayClosedHours = array_merge($standardWorkHours, [
            'friday' => ['is_open' => false, 'is_24_hours' => false, 'open_at' => null, 'close_at' => null, 'reservation_start_at' => null, 'reservation_close_at' => null],
        ]);

        $friday15Hours = array_merge($standardWorkHours, [
            'friday' => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '15:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '15:00:00', 'reservation_close_at' => '23:00:00'],
        ]);

        // Cities & Branches Setup
        $citiesWithBranches = [
            [
                'city' => ['name_ar' => 'جدة', 'name_en' => 'Jeddah'],
                'branches' => [
                    [
                        'name_ar' => 'فرع أبحر الشمالية',
                        'name_en' => 'Obhur Al Shamaliyah Branch',
                        'address_ar' => 'شارع عابر القارات ، أبحر الشمالية',
                        'address_en' => 'Aber Al Qarat St, Obhur Al Shamaliyah, Jeddah',
                        'latitude' => 21.7588000,
                        'longitude' => 39.1170000,
                        'phone' => '0555993463',
                        'working_hours' => [
                            'saturday'  => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '13:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '13:00:00', 'reservation_close_at' => '22:00:00'],
                            'sunday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '13:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '13:00:00', 'reservation_close_at' => '22:00:00'],
                            'monday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '13:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '13:00:00', 'reservation_close_at' => '22:00:00'],
                            'tuesday'   => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '13:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '13:00:00', 'reservation_close_at' => '22:00:00'],
                            'wednesday' => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '13:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '13:00:00', 'reservation_close_at' => '22:00:00'],
                            'thursday'  => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '13:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '13:00:00', 'reservation_close_at' => '22:00:00'],
                            'friday'    => ['is_open' => false, 'is_24_hours' => false, 'open_at' => null, 'close_at' => null, 'reservation_start_at' => null, 'reservation_close_at' => null],
                        ],
                    ],
                    [
                        'name_ar' => 'فرع السلامة',
                        'name_en' => 'Al Salamah Branch',
                        'address_ar' => 'مركز قريش التجاري بشارع صقر قريش، حي السلامة',
                        'address_en' => 'Quraish Commercial Center, Saqer Quraish St, Al Salamah, Jeddah',
                        'latitude' => 21.5859640,
                        'longitude' => 39.1629920,
                        'phone' => '0126833894',
                        'working_hours' => $standardWorkHours,
                    ],
                    [
                        'name_ar' => 'فرع السليمانية',
                        'name_en' => 'Al Sulaimaniyah Branch',
                        'address_ar' => 'شارع السليمانية مجمع بالبيد',
                        'address_en' => 'Al Sulaimaniyah St, Balbeed Complex, Jeddah',
                        'latitude' => 21.5102784,
                        'longitude' => 39.2476047,
                        'phone' => '0554556006',
                        'working_hours' => $standardWorkHours,
                    ],
                    [
                        'name_ar' => 'فرع صالة مطار الملك عبدالعزيز الدولي 1',
                        'name_en' => 'King Abdulaziz Airport Terminal 1 Branch',
                        'address_ar' => 'مطار الملك عبدالعزيز الدولي صالة 1',
                        'address_en' => 'King Abdulaziz International Airport Terminal 1, Jeddah',
                        'latitude' => 21.6616773,
                        'longitude' => 39.1741167,
                        'phone' => '0551263863',
                        'working_hours' => $twentyFourHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'خميس مشيط', 'name_en' => 'Khamis Mushait'],
                'branches' => [
                    [
                        'name_ar' => 'فرع خميس مشيط',
                        'name_en' => 'Khamis Mushait Branch',
                        'address_ar' => 'بجوار فندق الترانزيت الشارع العام، خميس مشيط',
                        'address_en' => 'Next to Transit Hotel, Main St, Khamis Mushait',
                        'latitude' => 18.3057366,
                        'longitude' => 42.7158043,
                        'phone' => '0172222333',
                        'working_hours' => $standardWorkHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'أبها', 'name_en' => 'Abha'],
                'branches' => [
                    [
                        'name_ar' => 'فرع الحديقة أبها',
                        'name_en' => 'Al Hadeeqa Abha Branch',
                        'address_ar' => 'شارع الحديقة، النزهة، أبها 62521',
                        'address_en' => 'Al Hadeeqa St, Al Nuzha, Abha 62521',
                        'latitude' => 18.2089914,
                        'longitude' => 42.5142366,
                        'phone' => '0555034827',
                        'working_hours' => $standardWorkHours,
                    ],
                    [
                        'name_ar' => 'فرع مطار أبها الإقليمي',
                        'name_en' => 'Abha Regional Airport Branch',
                        'address_ar' => 'مطار أبها الإقليمي',
                        'address_en' => 'Abha Regional Airport, Abha',
                        'latitude' => 18.2404000,
                        'longitude' => 42.6566000,
                        'phone' => '0172277778',
                        'working_hours' => $twentyFourHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'جازان', 'name_en' => 'Jazan'],
                'branches' => [
                    [
                        'name_ar' => 'فرع جازان',
                        'name_en' => 'Jazan Branch',
                        'address_ar' => 'شارع المطار - أمام بوابة مطار الملك عبدالله المدني',
                        'address_en' => 'Airport St, Opposite King Abdullah Airport Gate, Jazan',
                        'latitude' => 16.8972000,
                        'longitude' => 42.5853000,
                        'phone' => '0532264441',
                        'working_hours' => $standardWorkHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'نجران', 'name_en' => 'Najran'],
                'branches' => [
                    [
                        'name_ar' => 'فرع نجران',
                        'name_en' => 'Najran Branch',
                        'address_ar' => 'طريق الملك سعود بن عبدالعزيز، حي الأمير مشعل ، نجران 8219 66251',
                        'address_en' => 'King Saud Bin Abdulaziz Rd, Al Amir Mishal, Najran 8219 66251',
                        'latitude' => 17.5558800,
                        'longitude' => 44.2266655,
                        'phone' => '0553431444',
                        'working_hours' => $standardWorkHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'القطيف', 'name_en' => 'Qatif'],
                'branches' => [
                    [
                        'name_ar' => 'فرع القطيف',
                        'name_en' => 'Qatif Branch',
                        'address_ar' => 'طريق الرياض، المجيدية، القطيف 32632',
                        'address_en' => 'Riyadh Rd, Al Majeediah, Qatif 32632',
                        'latitude' => 26.5492000,
                        'longitude' => 50.0245000,
                        'phone' => '0553020710',
                        'working_hours' => $fridayClosedHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'الدمام', 'name_en' => 'Dammam'],
                'branches' => [
                    [
                        'name_ar' => 'فرع الدمام',
                        'name_en' => 'Dammam Branch',
                        'address_ar' => 'طريق الأمير نايف بن عبدالعزيز، الروضة، الدمام 32257',
                        'address_en' => 'Prince Naif Bin Abdulaziz Rd, Al Rawdah, Dammam 32257',
                        'latitude' => 26.4117370,
                        'longitude' => 50.0793560,
                        'phone' => '0138463510',
                        'working_hours' => $friday15Hours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'تبوك', 'name_en' => 'Tabuk'],
                'branches' => [
                    [
                        'name_ar' => 'فرع مطار الأمير سلطان بن عبدالعزيز',
                        'name_en' => 'Prince Sultan Airport Branch',
                        'address_ar' => 'مطار الأمير سلطان بن عبدالعزيز، صالة القدوم، تبوك 47511',
                        'address_en' => 'Prince Sultan Bin Abdulaziz Airport, Arrival Terminal, Tabuk 47511',
                        'latitude' => 28.3719083,
                        'longitude' => 36.5983554,
                        'phone' => '0553550422',
                        'working_hours' => $twentyFourHours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'الأحساء', 'name_en' => 'Al Ahsa'],
                'branches' => [
                    [
                        'name_ar' => 'فرع الهفوف',
                        'name_en' => 'Al Hofuf Branch',
                        'address_ar' => 'شارع الأمير طلال بن عبدالعزيز، الهفوف 36361',
                        'address_en' => 'Prince Talal Bin Abdulaziz St, Al Hofuf 36361',
                        'latitude' => 25.3725000,
                        'longitude' => 49.5841000,
                        'phone' => '0135875427',
                        'working_hours' => $friday15Hours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'الرياض', 'name_en' => 'Riyadh'],
                'branches' => [
                    [
                        'name_ar' => 'فرع القدس',
                        'name_en' => 'Al Quds Branch',
                        'address_ar' => 'الأمير سعود بن عبدالعزيز آل سعود الكبير، القدس، الرياض 13214',
                        'address_en' => 'Prince Saud Bin Abdulaziz Al Saud Al Kabeer St, Al Quds, Riyadh 13214',
                        'latitude' => 24.7591509,
                        'longitude' => 46.7569835,
                        'phone' => '0553231050',
                        'working_hours' => $fridayClosedHours,
                    ],
                    [
                        'name_ar' => 'فرع الفلاح',
                        'name_en' => 'Al Falah Branch',
                        'address_ar' => 'سعد بن محمد، الفلاح، الرياض 13314',
                        'address_en' => 'Saad Bin Mohammad St, Al Falah, Riyadh 13314',
                        'latitude' => 24.7996083,
                        'longitude' => 46.6975144,
                        'phone' => '0112771515',
                        'working_hours' => $friday15Hours,
                    ],
                    [
                        'name_ar' => 'فرع الياسمين',
                        'name_en' => 'Al Yasmin Branch',
                        'address_ar' => 'طريق الملك عبدالعزيز الفرعي، الياسمين، الرياض 13321',
                        'address_en' => 'King Abdulaziz Branch Rd, Al Yasmin, Riyadh 13321',
                        'latitude' => 24.8185047,
                        'longitude' => 46.6434367,
                        'phone' => '0552321479',
                        'working_hours' => $friday15Hours,
                    ],
                    [
                        'name_ar' => 'فرع الروضة',
                        'name_en' => 'Al Rawdah Branch',
                        'address_ar' => 'طريق الدائري الشرقي، أمام متحف الطيران، الرياض',
                        'address_en' => 'Eastern Ring Rd, Opposite Aviation Museum, Riyadh',
                        'latitude' => 24.7555703,
                        'longitude' => 46.7416082,
                        'phone' => '0112771515',
                        'working_hours' => $friday15Hours,
                    ],
                ],
            ],
            [
                'city' => ['name_ar' => 'المدينة المنورة', 'name_en' => 'Madinah'],
                'branches' => [
                    [
                        'name_ar' => 'فرع مطار الأمير محمد بن عبدالعزيز',
                        'name_en' => 'Prince Mohammad Bin Abdulaziz Airport Branch',
                        'address_ar' => 'مطار الأمير محمد بن عبدالعزيز الدولي، المدينة المنورة',
                        'address_en' => 'Prince Mohammad Bin Abdulaziz International Airport, Madinah',
                        'latitude' => 24.5534000,
                        'longitude' => 39.7051000,
                        'phone' => '0558020501',
                        'working_hours' => $twentyFourHours,
                    ],
                    [
                        'name_ar' => 'فرع البلد',
                        'name_en' => 'Al Balad Branch',
                        'address_ar' => 'طريق الملك عبدالعزيز، العريض، المدينة المنورة 52366',
                        'address_en' => 'King Abdulaziz Rd, Al Areed, Madinah 52366',
                        'latitude' => 24.4672000,
                        'longitude' => 39.6112000,
                        'phone' => '0558020822',
                        'working_hours' => [
                            'saturday'  => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '09:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '09:00:00', 'reservation_close_at' => '22:00:00'],
                            'sunday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '22:00:00'],
                            'monday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '22:00:00'],
                            'tuesday'   => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '22:00:00'],
                            'wednesday' => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '22:00:00'],
                            'thursday'  => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '08:00:00', 'close_at' => '22:00:00', 'reservation_start_at' => '08:00:00', 'reservation_close_at' => '22:00:00'],
                            'friday'    => ['is_open' => true, 'is_24_hours' => false, 'open_at' => '15:00:00', 'close_at' => '23:00:00', 'reservation_start_at' => '15:00:00', 'reservation_close_at' => '23:00:00'],
                        ],
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

                if (isset($branchData['working_hours'])) {
                    $this->seedWorkingHours($branch, $branchData['working_hours']);
                }
            }
        }
    }

    /**
     * @param array<string, array{is_open: bool, is_24_hours?: bool, open_at?: ?string, close_at?: ?string, reservation_start_at?: ?string, reservation_close_at?: ?string}> $hoursMap
     */
    protected function seedWorkingHours(Branch $branch, array $hoursMap): void
    {
        foreach ($hoursMap as $dayName => $dayConfig) {
            BranchWorkingHour::updateOrCreate(
                [
                    'branch_id' => $branch->id,
                    'day_of_week' => $dayName,
                ],
                [
                    'is_open' => (bool) ($dayConfig['is_open'] ?? true),
                    'is_24_hours' => (bool) ($dayConfig['is_24_hours'] ?? false),
                    'open_at' => $dayConfig['open_at'] ?? null,
                    'close_at' => $dayConfig['close_at'] ?? null,
                    'reservation_start_at' => $dayConfig['reservation_start_at'] ?? $dayConfig['open_at'] ?? null,
                    'reservation_close_at' => $dayConfig['reservation_close_at'] ?? $dayConfig['close_at'] ?? null,
                ]
            );
        }
    }
}

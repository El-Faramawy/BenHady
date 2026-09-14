<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Branch;
use Database\Seeders\CityAndBranchSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_city_and_branch_seeder_populates_branches_and_working_hours(): void
    {
        $this->seed(CityAndBranchSeeder::class);

        $this->assertDatabaseCount('branches', 19);

        // Verify Obhur branch working hours timing
        $obhurBranch = Branch::where('name_en', 'Obhur Al Shamaliyah Branch')->first();
        $this->assertNotNull($obhurBranch);

        $saturdayHours = $obhurBranch->workingHours()->where('day_of_week', 'saturday')->first();
        $this->assertNotNull($saturdayHours);
        $this->assertEquals('13:00:00', $saturdayHours->open_at);
        $this->assertEquals('22:00:00', $saturdayHours->close_at);
        $this->assertEquals('13:00:00', $saturdayHours->reservation_start_at);
        $this->assertEquals('22:00:00', $saturdayHours->reservation_close_at);

        // Verify Airport 24h branch working hours timing
        $airportBranch = Branch::where('name_en', 'King Abdulaziz Airport Terminal 1 Branch')->first();
        $this->assertNotNull($airportBranch);
        $airportHours = $airportBranch->workingHours()->where('day_of_week', 'saturday')->first();
        $this->assertNotNull($airportHours);
        $this->assertEquals('00:00:00', $airportHours->open_at);
        $this->assertEquals('23:59:59', $airportHours->close_at);
        $this->assertEquals('00:00:00', $airportHours->reservation_start_at);
        $this->assertEquals('23:59:59', $airportHours->reservation_close_at);
    }
}

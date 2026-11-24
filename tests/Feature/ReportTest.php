<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_report_page_loads_successfully()
    {
        // ARRANGE
        $user = User::create(['name' => 'A', 'email' => 'a@a.com', 'password' => '123']);
        $this->actingAs($user);

        // ACT
        $response = $this->get('/reports/monthly');

        // ASSERT
        $response->assertStatus(200);
        $response->assertSee('Laporan Rekap Bulanan');
    }
}
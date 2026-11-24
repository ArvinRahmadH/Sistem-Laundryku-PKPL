<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_be_created_with_duplicate_phone()
    {
        // ARRANGE
        $user = User::create(['name' => 'A', 'email' => 'a@a.com', 'password' => '123']);
        $this->actingAs($user);
        
        // Buat customer pertama
        Customer::create(['name' => 'Budi', 'phone' => '08123456789', 'tier' => 'regular']);

        // ACT
        // Coba buat customer kedua dengan nomor HP yang sama
        $response = $this->post('/customers', [
            'name' => 'Andi',
            'phone' => '08123456789', 
            'tier' => 'silver'
        ]);

        // ASSERT
        $response->assertSessionHasErrors('phone'); // Harus error
        $this->assertEquals(1, Customer::count()); // Tetap hanya ada 1 di database
    }
}
<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Http\Controllers\LaundryOrderController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ReflectionMethod;

class DiscountTest extends TestCase
{
    use RefreshDatabase;

    private function invokeCalculateDiscount(array &$data)
    {
        $controller = new LaundryOrderController();
        $method = new ReflectionMethod(LaundryOrderController::class, 'calculateDiscount');
        $method->setAccessible(true);
        $method->invokeArgs($controller, [&$data]);
    }

    public function test_calculate_discount_without_customer()
    {
        $data = ['weight' => 2, 'customer_id' => null];
        $this->invokeCalculateDiscount($data);
        $this->assertEquals(20000, $data['total_price']);
        $this->assertEquals(0, $data['discount_percent']);
    }

    public function test_calculate_discount_with_invalid_customer()
    {
        $data = ['weight' => 2, 'customer_id' => 999];
        $this->invokeCalculateDiscount($data);
        $this->assertEquals(20000, $data['total_price']);
        $this->assertEquals(0, $data['discount_percent']);
    }

    public function test_calculate_discount_with_regular_customer()
    {
        // Menggunakan Customer::create() agar tidak butuh Factory
        $customer = Customer::create([
            'name' => 'Regular User',
            'phone' => '081111111111',
            'tier' => 'regular'
        ]);
        
        $data = ['weight' => 2, 'customer_id' => $customer->id];
        $this->invokeCalculateDiscount($data);
        
        $this->assertEquals(20000, $data['total_price']); // Diskon 0%
    }

    public function test_calculate_discount_with_silver_customer()
    {
        $customer = Customer::create([
            'name' => 'Silver User',
            'phone' => '082222222222',
            'tier' => 'silver'
        ]);
        
        $data = ['weight' => 2, 'customer_id' => $customer->id];
        $this->invokeCalculateDiscount($data);
        
        $this->assertEquals(19000, $data['total_price']); // Diskon 5%
    }

    public function test_calculate_discount_with_gold_customer()
    {
        $customer = Customer::create([
            'name' => 'Gold User',
            'phone' => '083333333333',
            'tier' => 'gold'
        ]);
        
        $data = ['weight' => 2, 'customer_id' => $customer->id];
        $this->invokeCalculateDiscount($data);
        
        $this->assertEquals(18000, $data['total_price']); // Diskon 10%
    }

    public function test_calculate_discount_with_platinum_customer()
    {
        $customer = Customer::create([
            'name' => 'Platinum User',
            'phone' => '084444444444',
            'tier' => 'platinum'
        ]);
        
        $data = ['weight' => 2, 'customer_id' => $customer->id];
        $this->invokeCalculateDiscount($data);
        
        $this->assertEquals(17000, $data['total_price']); // Diskon 15%
    }

        public function test_order_is_saved_to_database()
    {
        // ARRANGE
        $user = \App\Models\User::create(['name' => 'A', 'email' => 'a@a.com', 'password' => '123']);
        $this->actingAs($user);

        $data = [
            'customer_name' => 'Budi',
            'service_type' => 'kiloan',
            'weight' => 101,
            'price' => 10000,
        ];

        // ACT
        $response = $this->post('/orders', $data);

        // ASSERT
        $response->assertRedirect('/orders');
        $this->assertDatabaseHas('laundry_orders', [
            'customer_name' => 'Budi',
            'weight' => 101
        ]);
    }
}
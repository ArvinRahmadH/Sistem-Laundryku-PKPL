<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ================= FITUR LOGIN =================
    public function test_user_can_login_with_valid_credentials()
    {
        // ARRANGE
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123')
        ]);

        // ACT
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        // ASSERT
        $response->assertRedirect('/'); 
        $this->assertAuthenticatedAs($user); 
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        // ARRANGE
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123')
        ]);

        // ACT
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password_salah',
        ]);

        // ASSERT
        $response->assertSessionHasErrors('email'); // Muncul error
        $this->assertGuest(); // Tidak terautentikasi
    }

    // ================= FITUR REGISTER =================
    public function test_user_can_register_with_valid_data()
    {
        // ARRANGE
        $data = [
            'name' => 'User Baru',
            'email' => 'baru@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        // ACT
        $response = $this->post('/register', $data);

        // ASSERT
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'baru@test.com']);
    }
}
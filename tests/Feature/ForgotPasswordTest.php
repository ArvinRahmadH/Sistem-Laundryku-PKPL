<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_with_registered_email()
    {
        // ARRANGE
        $user = User::create([
            'name' => 'Lupa Pass',
            'email' => 'lupa@test.com',
            'password' => Hash::make('passwordLama')
        ]);

        // ACT
        $response = $this->post('/reset-password', [
            'email' => 'lupa@test.com',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123'
        ]);

        // ASSERT
        $response->assertRedirect('/login');
        
        // Cek apakah password di database berubah
        $user->refresh();
        $this->assertTrue(Hash::check('passwordBaru123', $user->password));
    }
}
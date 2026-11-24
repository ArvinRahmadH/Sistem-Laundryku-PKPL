<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_file_can_be_uploaded()
    {
        // ARRANGE
        Storage::fake('public'); // Gunakan Fake Storage
        $user = User::create(['name' => 'A', 'email' => 'a@a.com', 'password' => '123']);
        $this->actingAs($user);
        $file = UploadedFile::fake()->create('bukti.pdf', 1000); // File 1MB

        // ACT
        $response = $this->post('/orders', [
            'customer_name' => 'Budi',
            'service_type' => 'kiloan',
            'weight' => 2,
            'file' => $file
        ]);

        // ASSERT
        $response->assertRedirect('/orders');
        Storage::disk('public')->assertExists('laundry/' . $file->hashName());
    }
}
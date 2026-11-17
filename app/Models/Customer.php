<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'tier',
    ];

    /**
     * Relasi: 1 Customer punya banyak Order
     */
    public function orders()
    {
        return $this->hasMany(LaundryOrder::class);
    }

    /**
     * Ambil persentase diskon berdasarkan tier
     */
    public function getDiscountPercentAttribute(): float
    {
        return match ($this->tier) {
            'silver'   => 5,
            'gold'     => 10,
            'platinum' => 15,
            default    => 0,  // regular
        };
    }

    /**
     * Label tier dengan warna badge (untuk view)
     */
    public function getTierBadgeAttribute(): string
    {
        return match ($this->tier) {
            'silver'   => '<span class="badge bg-secondary">Silver</span>',
            'gold'     => '<span class="badge bg-warning text-dark">Gold</span>',
            'platinum' => '<span class="badge bg-dark">Platinum</span>',
            default    => '<span class="badge bg-light text-dark">Regular</span>',
        };
    }
}
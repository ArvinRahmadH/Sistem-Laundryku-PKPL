<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaundryOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laundry_orders';

    protected $fillable = [
        'customer_id',       // ✅ BARU
        'customer_name',
        'phone',
        'address',
        'service_type',
        'weight',
        'status',
        'price',
        'subtotal',          // ✅ BARU
        'discount_percent',  // ✅ BARU
        'discount_amount',   // ✅ BARU
        'total_price',       // ✅ BARU
        'notes',
        'file_path',
    ];

    protected $casts = [
        'weight'           => 'float',
        'price'            => 'integer',
        'subtotal'         => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'total_price'      => 'decimal:2',
    ];

    /**
     * Relasi: Order milik 1 Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
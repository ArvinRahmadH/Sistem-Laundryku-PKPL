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
        'customer_name',
        'phone',
        'address',
        'service_type',
        'weight',
        'status',
        'price',
        'notes',
        'file_path'
    ];

    protected $casts = [
        'weight' => 'float',
        'price' => 'integer',
    ];
}

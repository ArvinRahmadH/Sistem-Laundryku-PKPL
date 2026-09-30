<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundryOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('laundry_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->enum('service_type', ['kiloan', 'dry_cleaning', 'antar_jemput', 'setrika_premium'])->default('kiloan');
            $table->decimal('weight', 6, 2)->default(0); 
            $table->enum('status', ['pending', 'in_progress', 'done', 'delivered'])->default('pending');
            $table->unsignedBigInteger('price')->default(0); 
            $table->text('notes')->nullable();
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laundry_orders');
    }
}

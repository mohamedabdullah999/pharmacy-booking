<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_national_id'); 
            
            $table->decimal('total_amount', 12, 2); 
            
            $table->enum('status', ['pending_payment', 'paid', 'expired', 'cancelled', 'completed'])->default('pending_payment');
            
            $table->timestamp('expires_at')->nullable(); 

            $table->index('status');
            $table->index('customer_national_id');
            $table->index('created_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

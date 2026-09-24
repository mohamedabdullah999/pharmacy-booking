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
            
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pricing_rule_id')->constrained('item_pricing_rules')->cascadeOnDelete();
            
            $table->string('customer_name');
            $table->string('customer_national_id', 14);
            $table->string('customer_phone');
            $table->string('customer_email');
            
            $table->decimal('requested_amount', 8, 2); 
            $table->decimal('total_price', 10, 2); 
            
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending');
            
            $table->timestamp('expires_at')->nullable();
            
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

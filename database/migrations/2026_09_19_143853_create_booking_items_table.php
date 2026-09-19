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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_pricing_rule_id')->nullable()->constrained()->nullOnDelete();
            
            $table->integer('quantity')->default(1); 
            
            $table->timestamp('start_time')->nullable(); 
            $table->timestamp('end_time')->nullable();
            
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->index(['item_id', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};

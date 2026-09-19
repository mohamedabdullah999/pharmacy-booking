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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['sale', 'rental']); 
            $table->string('brand')->nullable();
            $table->string('model')->nullable(); 
            $table->integer('stock_quantity')->default(1); 
            $table->boolean('is_active')->default(true); 
            $table->index(['is_active', 'type']);
            $table->index('department_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

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
        Schema::create('uni_unit', function (Blueprint $table) {
            $table->id();
            $table->string('unit', 50)->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uni_unit');
    }
}; 
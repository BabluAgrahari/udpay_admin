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
        Schema::create('uni_product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->longText('details')->nullable();
            $table->longText('key_ings')->nullable();
            $table->longText('uses')->nullable();
            $table->longText('result')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('uni_products')->onDelete('cascade');
            $table->index(['product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uni_product_details');
    }
}; 
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
        Schema::create('sales_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_product_id')->constrained('sales_products');
            $table->string('cep');
            $table->string('state');
            $table->string('city');
            $table->string('district');
            $table->string('street');
            $table->integer('number');
            $table->string('complement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_addresses');
    }
};

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
    Schema::create('service_price_ranges', function (Blueprint $table) {
        $table->id();
        $table->foreignId('service_id')->nullable()->constrained()->cascadeOnDelete();
        $table->foreignId('service_frequency_id')->nullable()->constrained()->cascadeOnDelete();
        $table->unsignedInteger('min_sqm');
        $table->unsignedInteger('max_sqm');
        $table->unsignedInteger('price');
        $table->unsignedInteger('sort_order')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_price_ranges');
    }
};

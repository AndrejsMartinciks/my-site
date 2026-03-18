<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('booking_date');
            $table->time('time_from');
            $table->time('time_to');

            $table->boolean('is_active')->default(true);
            $table->boolean('is_booked')->default(false);

            $table->unsignedInteger('sort_order')->default(1);
            $table->string('internal_note')->nullable();

            $table->timestamps();

            $table->index(['service_id', 'booking_date']);
            $table->index(['booking_date', 'is_active', 'is_booked']);

            $table->unique([
                'service_id',
                'booking_date',
                'time_from',
                'time_to',
            ], 'booking_slots_unique_service_date_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_slots');
    }
};
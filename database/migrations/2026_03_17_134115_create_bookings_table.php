<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('booking_slot_id')
                ->nullable()
                ->constrained('booking_slots')
                ->nullOnDelete();

            $table->date('booking_date');
            $table->time('time_from');
            $table->time('time_to');

            $table->string('customer_name');
            $table->text('personnummer'); // будет храниться encrypted cast
            $table->string('personnummer_last4', 4)->nullable();

            $table->string('address');
            $table->string('phone', 50);
            $table->string('email');

            $table->unsignedInteger('sqm')->nullable();
            $table->unsignedInteger('quoted_price')->nullable();

            $table->json('addons')->nullable();
            $table->text('calculator_summary')->nullable();

            $table->string('status', 20)->default('new');
            $table->text('manager_note')->nullable();

            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('done_at')->nullable();

            $table->timestamps();

            $table->index('booking_date');
            $table->index('status');
            $table->index('email');
            $table->index('personnummer_last4');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
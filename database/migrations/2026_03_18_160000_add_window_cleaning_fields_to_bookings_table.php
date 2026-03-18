<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_type', 50)->nullable()->after('service_id');
            $table->string('postcode', 20)->nullable()->after('address');
            $table->unsignedInteger('window_count')->nullable()->after('sqm');
            $table->string('cleaning_scope', 20)->nullable()->after('window_count');

            $table->index('booking_type');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['booking_type']);
            $table->dropColumn([
                'booking_type',
                'postcode',
                'window_count',
                'cleaning_scope',
            ]);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'booking_type')) {
                $table->string('booking_type', 50)->nullable()->after('service_id');
                $table->index('booking_type');
            }

            if (! Schema::hasColumn('bookings', 'postcode')) {
                $table->string('postcode', 20)->nullable()->after('address');
            }

            if (! Schema::hasColumn('bookings', 'window_count')) {
                $table->unsignedInteger('window_count')->nullable()->after('sqm');
            }

            if (! Schema::hasColumn('bookings', 'cleaning_scope')) {
                $table->string('cleaning_scope', 20)->nullable()->after('window_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'booking_type')) {
                $table->dropIndex(['booking_type']);
                $table->dropColumn('booking_type');
            }

            if (Schema::hasColumn('bookings', 'postcode')) {
                $table->dropColumn('postcode');
            }

            if (Schema::hasColumn('bookings', 'window_count')) {
                $table->dropColumn('window_count');
            }

            if (Schema::hasColumn('bookings', 'cleaning_scope')) {
                $table->dropColumn('cleaning_scope');
            }
        });
    }
};
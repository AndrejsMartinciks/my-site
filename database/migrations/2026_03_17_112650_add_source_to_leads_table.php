<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('source', 20)->default('form')->after('calculator_summary');
            $table->index('source');
        });

        DB::table('leads')
            ->update([
                'source' => DB::raw("
                    CASE
                        WHEN calculator_summary IS NOT NULL AND calculator_summary != ''
                            THEN 'calculator'
                        ELSE 'form'
                    END
                "),
            ]);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['source']);
            $table->dropColumn('source');
        });
    }
};
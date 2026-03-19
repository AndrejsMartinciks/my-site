<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_point_1')->nullable();
            $table->string('hero_point_2')->nullable();
            $table->string('hero_point_3')->nullable();

            $table->string('hero_badge_1')->nullable();
            $table->string('hero_badge_2')->nullable();
            $table->string('hero_badge_3')->nullable();

            $table->string('trust_eyebrow')->nullable();
            $table->string('trust_title')->nullable();

            $table->string('trust_item_1_title')->nullable();
            $table->text('trust_item_1_text')->nullable();
            $table->string('trust_item_2_title')->nullable();
            $table->text('trust_item_2_text')->nullable();
            $table->string('trust_item_3_title')->nullable();
            $table->text('trust_item_3_text')->nullable();
            $table->string('trust_item_4_title')->nullable();
            $table->text('trust_item_4_text')->nullable();

            $table->string('about_eyebrow')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_text_1')->nullable();
            $table->text('about_text_2')->nullable();
            $table->string('about_check_title')->nullable();
            $table->string('about_check_1')->nullable();
            $table->string('about_check_2')->nullable();
            $table->string('about_check_3')->nullable();
            $table->string('about_check_4')->nullable();

            $table->string('rut_eyebrow')->nullable();
            $table->string('rut_title')->nullable();
            $table->text('rut_text_1')->nullable();
            $table->text('rut_text_2')->nullable();

            $table->text('footer_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_point_1',
                'hero_point_2',
                'hero_point_3',
                'hero_badge_1',
                'hero_badge_2',
                'hero_badge_3',
                'trust_eyebrow',
                'trust_title',
                'trust_item_1_title',
                'trust_item_1_text',
                'trust_item_2_title',
                'trust_item_2_text',
                'trust_item_3_title',
                'trust_item_3_text',
                'trust_item_4_title',
                'trust_item_4_text',
                'about_eyebrow',
                'about_title',
                'about_text_1',
                'about_text_2',
                'about_check_title',
                'about_check_1',
                'about_check_2',
                'about_check_3',
                'about_check_4',
                'rut_eyebrow',
                'rut_title',
                'rut_text_1',
                'rut_text_2',
                'footer_text',
            ]);
        });
    }
};
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
    Schema::create('site_settings', function (Blueprint $table) {
        $table->id();
        $table->string('company_name');
        $table->string('phone_primary')->nullable();
        $table->string('phone_secondary')->nullable();
        $table->string('email')->nullable();
        $table->string('address')->nullable();
        $table->string('postal_code')->nullable();
        $table->string('city')->nullable();
        $table->string('org_number')->nullable();

        $table->string('hero_eyebrow')->nullable();
        $table->string('hero_title')->nullable();
        $table->text('hero_text')->nullable();
        $table->string('hero_primary_button_text')->nullable();
        $table->string('hero_secondary_button_text')->nullable();

        $table->string('seo_title')->nullable();
        $table->text('seo_description')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};

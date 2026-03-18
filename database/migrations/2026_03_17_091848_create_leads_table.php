<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('service');
            $table->text('message')->nullable();
            $table->text('calculator_summary')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('service');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
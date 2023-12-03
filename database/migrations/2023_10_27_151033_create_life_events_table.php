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
        Schema::create('life_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('week_id');
            $table->date('date');
            $table->string('title');
            $table->string('type');
            $table->string('description')->nullable();
            $table->longText('notes')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('life_events');
    }
};

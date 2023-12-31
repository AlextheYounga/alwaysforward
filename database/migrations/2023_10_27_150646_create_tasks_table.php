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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('type');
            $table->integer('priority')->default(0);
            $table->datetime('due_date')->nullable();
            $table->datetime('date_completed')->nullable();
            $table->longText('notes')->nullable();
            $table->string('status')->default('backlog');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

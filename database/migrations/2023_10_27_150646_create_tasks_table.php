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
            $table->foreignId('week_id')->nullable();
            $table->foreignId('goal_id')->nullable();
            $table->foreignId('board_id')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('priority')->default(0);
            $table->integer('duration')->nullable();
            $table->integer('time_spent')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->json('subtasks')->nullable();
            $table->longText('notes')->nullable();
            $table->string('status')->default('todo');
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

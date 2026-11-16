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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('challenge_type', 50); // daily, weekly, monthly, team, event
            $table->string('goal_type', 50); // steps, distance, active_minutes
            $table->integer('goal_value');
            $table->integer('reward_points')->default(0);
            $table->string('badge_reward', 100)->nullable();
            $table->integer('participant_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 50)->default('draft'); // draft, active, completed, archived
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

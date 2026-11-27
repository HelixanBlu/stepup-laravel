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
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('user_collections');
        Schema::dropIfExists('collection_challenges');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('collections');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id('collection_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('difficulty', 50)->default('easy');
            $table->string('estimated_duration', 100)->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });

        Schema::create('badges', function (Blueprint $table) {
            $table->id('badge_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('badge_icon')->nullable();
            $table->string('category', 50)->default('challenge');
            $table->string('rarity', 50)->default('common');
            $table->string('unlock_requirement')->nullable();
            $table->foreignId('related_challenge_id')->nullable()->constrained('challenges', 'challenge_id')->nullOnDelete();
            $table->foreignId('related_collection_id')->nullable()->constrained('collections', 'collection_id')->nullOnDelete();
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });

        Schema::create('collection_challenges', function (Blueprint $table) {
            $table->id('collection_challenge_id');
            $table->foreignId('collection_id')->constrained('collections', 'collection_id')->cascadeOnDelete();
            $table->foreignId('challenge_id')->constrained('challenges', 'challenge_id')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id('user_badge_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges', 'badge_id')->cascadeOnDelete();
            $table->timestamp('earned_at')->useCurrent();
            $table->timestamps();
            $table->unique(['user_id', 'badge_id']);
        });

        Schema::create('user_collections', function (Blueprint $table) {
            $table->id('user_collection_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('collection_id')->constrained('collections', 'collection_id')->cascadeOnDelete();
            $table->integer('progress_percentage')->default(0);
            $table->string('status', 50)->default('joined');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
        });
    }
};

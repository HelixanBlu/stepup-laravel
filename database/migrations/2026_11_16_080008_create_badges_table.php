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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('badge_icon')->nullable();
            $table->string('category', 50)->default('challenge'); // challenge, collection, activity, streak, special_event
            $table->string('rarity', 50)->default('common'); // common, rare, epic, legendary
            $table->string('unlock_requirement')->nullable();
            $table->foreignId('related_challenge_id')->nullable()->constrained('challenges')->nullOnDelete();
            $table->foreignId('related_collection_id')->nullable()->constrained('collections')->nullOnDelete();
            $table->string('status', 50)->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};

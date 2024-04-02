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
        Schema::create('study_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('study_id');
            $table->foreign('participant_id')->references('id')->on('participants')->cascadeOnDelete();
            $table->foreign('study_id')->references('id')->on('studies')->cascadeOnDelete();
            $table->unique(['participant_id','study_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_participants');
    }
};

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
        Schema::create('study_data_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_id');
            $table->unsignedBigInteger('data_type_id');
            $table->string('description')->nullable()->default(null);
            $table->foreign('study_id')->references('id')->on('studies')->cascadeOnDelete();
            $table->foreign('data_type_id')->references('id')->on('data_types')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_types');
    }
};

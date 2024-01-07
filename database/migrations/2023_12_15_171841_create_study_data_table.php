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
        Schema::create('study_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_id');
            //Uncomment once studies table is updated with id
            //$table->foreign('study_id')->references('id')->on('studies');
            $table->string('type');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_data');
    }
};

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
        Schema::create('data_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_id');
            $table->enum('type',[
                "assessment",
                "behavioral",
                "neurosignal",
                "biospecimen"
            ]);
            $table->string('description')->nullable()->default(null);
            $table->foreign('study_id')->references('id')->on('studies');
            
            // Look into removing the following:
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
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

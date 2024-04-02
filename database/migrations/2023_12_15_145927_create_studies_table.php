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
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pi_user_id')->index()->nullable();
            $table->text('title');
            $table->text('description')->nullable()->default(null);
            $table->date('start_date')->nullable()->default(null);// should these be nullable?
            $table->date('end_date')->nullable()->default(null);// should these be nullable?
            $table->enum('location',[
                "In-person",
                "Virtual",
                "Hybrid"
            ]);
            $table->enum('design',[
                "Cross-sectional",
                "Longitudinal"
            ]);
            $table->enum('sample_type',[
                "Neurotypical",
                "Neurodivergent",
                "Neurodiverse",
                "Unspecified"
            ]);
            $table->unsignedBigInteger('created_by')->index()->nullable();
            $table->unsignedBigInteger('updated_by')->index()->nullable();
            $table->foreign('pi_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            //data types
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};

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
        Schema::create('study_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('study_permission',[
                "view_study",
                "manage_study"
            ]);
            $table->foreign('study_id')->references('id')->on('studies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_permissions');
    }
};

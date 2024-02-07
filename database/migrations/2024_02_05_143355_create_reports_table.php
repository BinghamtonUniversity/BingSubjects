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
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('');
            $table->longText('description')->nullable()->default(null);
            $table->json('report')->nullable()->default(null);
            $table->unsignedBigInteger('owner_user_id')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('owner_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

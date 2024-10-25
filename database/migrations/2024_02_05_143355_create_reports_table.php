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
            $table->json('permissions')->nullable();
            $table->unsignedBigInteger('owner_user_id')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
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

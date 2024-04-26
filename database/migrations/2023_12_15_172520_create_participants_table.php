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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('sex',[
                'male',
                'female',
                'intersex'
            ])->nullable()->default(null);
            $table->enum('gender',[
                'man',
                'woman',
                'non_binary'
            ])->nullable()->default(null);
            $table->enum('race',[
                'american_native',
                'asian',
                'black',
                'pacific_islander',
                'white'
            ])->nullable()->default(null);
            $table->enum('ethnicity',[
                'hispanic',
                'not_hispanic'
            ])->nullable()->default(null);
            $table->string('city_of_birth')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone_number')->nullable()->default(null);
            $table->text('participant_comments')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};

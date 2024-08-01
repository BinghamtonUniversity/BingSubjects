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
        Schema::table('participants', function (Blueprint $table) {
            $table->string('email')->change();
            $table->unique(['email','date_of_birth','first_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('email')->nullable()->default(null)->change();
            // Remove the unique constraint
            $table->dropUnique(['email', 'date_of_birth', 'first_name']);

        });
    }
};

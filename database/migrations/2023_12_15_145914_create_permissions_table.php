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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('permission',[
                "view_users", //view list of all users and their info
                "manage_users", //create or update any user
                "view_permissions", //view list of all users' permissions
                "manage_permissions", //create or update any user's permissions
                "view_studies", //view any study's info (incl. data types) and its participants
                "create_studies", //create studies, automatically assigning yourself admin to that study
                "manage_studies", //update any study's info (incl. data types) and its participants (and viewers)
                "manage_data_types", //create, update, or delete data types from database
                "view_participants", //view list of all participants (excl. study relationships)
                "manage_participants", //create participants and update participant info (excl. study relationships)
                "manage_deletions", //manage permanent deletion of any entity (studies, participants, data types, users)
            ]);
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

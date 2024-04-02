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
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->enum('permission',[
                "view_users", //view list of all users
                "manage_users", //view, create, update, or delete any user
                "view_permissions", //view list of all users' permissions
                "manage_permissions", //create or update all users' permissions
                "view_studies", //view any study's info (incl. data types) and its participants
                "create_studies", //create studies - automatically assigning this user a manager to that study
                "manage_studies", //update or delete any study's info (incl. data types), its participants, and its users
                "manage_data_types", //create, update, or delete data types
                "view_participants", //view list of all participants (excl. study relationships)
                "update_participants", //create participants and update participant info (excl. study relationships)
                "manage_participants" //delete participants
            ]);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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

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
                "view_users",
                "manage_users",
                "view_permissions",
                "manage_permissions",
                "view_studies", //view list of all study info (incl. data types), without participant relationships
                "create_studies", //create new studies - automatically assigning the user 'manage_study' upon creation
                "view_participants",
                "manage_participants", //create and update participant info
                "delete_participants", //permanently delete participants
                "view_studies_participants", //view any study participant relationship
                //"view_data_types", ////////may remove in place of view studies
                "create_data_types", //create data types
                "manage_data_types", //permanently delete data types
                "studies_admin", //manage any study's info, data type, and participant relationship
                "super_user"
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

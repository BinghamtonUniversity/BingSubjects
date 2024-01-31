<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $default_user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => '12345'
        ]);

        \App\Models\Participant::create([
            'first_name' => 'Participant',
            'last_name' => 'One',
            'email' => 'test@example.com',
            'date_of_birth' => '2024-01-01',
            'sex' => 'male',
            'race' => 'white',
            'city_of_birth' => 'albany',
            'email' => 'example@example.com',
            'phone_number' => '123-456-7890',
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);

        \App\Models\Study::create([
            'pi_user_id' => $default_user->id,
            'title' => 'Test Study',
            'description' => 'Good test',
            'location' => 'BU',
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-01',
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);

        // Start default user with view and manage users and permissions permissions
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_users'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_users',
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_permissions'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_permissions'
        ]);
    }
}

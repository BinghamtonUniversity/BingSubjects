<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Participant;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Starter Users
        $default_user = \App\Models\User::create([
            'first_name' => 'Lindsey',
            'last_name' => 'Braun',
            'email' => 'lbraun2@binghamton.edu',
            'password' => '12345'
        ]);
        \App\Models\User::create([
            'first_name' => 'Tim',
            'last_name' => 'Cortesi',
            'email' => 'tcortesi@binghamton.edu',
            'password' => '12345'
        ]);
        \App\Models\User::create([
            'first_name' => 'Ali Kemal',
            'last_name' => 'Tanriverdi',
            'email' => 'atanrive@binghamton.edu',
            'password' => '12345'
        ]);

        // Assign default user all view permissions
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_users'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_permissions'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_studies'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_participants'
        ]);
        // \App\Models\Permission::create([
        //     'user_id' => $default_user->id,
        //     'permission' => 'view_data_types'
        // ]);

        // Assign second user all view permissions
        \App\Models\Permission::create([
            'user_id' => 2,
            'permission' => 'view_users'
        ]);
        \App\Models\Permission::create([
            'user_id' => 2,
            'permission' => 'view_permissions'
        ]);
        \App\Models\Permission::create([
            'user_id' => 2,
            'permission' => 'view_studies'
        ]);
        \App\Models\Permission::create([
            'user_id' => 2,
            'permission' => 'view_participants'
        ]);
        // \App\Models\Permission::create([
        //     'user_id' => 2,
        //     'permission' => 'view_data_types'
        // ]);

        // Starter Participants
        Participant::factory(20)->create([
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id,
        ]);

        // Starter Studies
        \App\Models\Study::create([
            'pi_user_id' => $default_user->id,
            'title' => "Childhood Adversity and Romantic Relationship Functioning Among Lesbian, Gay, Bisexual, and Queer Individuals",
            'description' => "The CAST Lab want to learn more about how different types of minority stress (e.g., prejudice, discrimination, unsupportive family and friends) affect us and our romantic relationships. We are looking for lesbian, gay, bisexual or otherwise non-heterosexual (LGBQ+) couples in a committed relationship for six months or longer to participate in a study conducted by researchers at Binghamton University. We are interested in couples who experience a broad range of everyday stress, including people who have experienced highly stressful events. Individuals will be compensated for participation, and you can participate in the comfort of your home. This survey study takes approximately 30 minutes to complete.",
            'location' => "Virtual",
            'start_date' => '2024-04-01',
            'end_date' => '2024-08-01',
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\Study::create([
            'pi_user_id' => 2,
            'title' => "Dating, Literary Sexual Encounters and Brain Stimulation Study",
            'description' => "This is a research study being conducted at Binghamton University's Clearview Hall by the Center for Transdisciplinary Research on Intimate Relationships. The purpose of this work is to explore different elements of people's attitudes and beliefs about dating, institutional policies and certain groups (e.g., women and trans people).",
            'location' => "Clearview Hall",
            'start_date' => '2024-03-01',
            'end_date' => '2024-05-01',
            'created_by' => 2,
            'updated_by' => 2
        ]);
        \App\Models\Study::create([
            'pi_user_id' => 2,
            'title' => "Nurses' Perspectives on Barriers while Serving Individuals with Dysphagia and Cognitive Communication Disorders",
            'description' => "Registered nurses (RNs), licensed practical nurses (LPNs) and certified nursing assistants (CNAs) are needed for a research study. Participate in a 30-60 minute, semi-structured interview about nurses' experiences working with patients who experience dysphagia and cognitive communication disorder. The interview will be conducted via Zoom and audio-recorded for data analysis purposes.",
            'location' => 'Virtual',
            'start_date' => '2024-02-01',
            'end_date' => '2024-04-01',
            'created_by' => 2,
            'updated_by' => 2
        ]);

        // Starter Data Types
        \App\Models\DataType::create([
            'type' => "Interview",
            'description' => "Stress and trauma evaluation",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'type' => "Survey",
            'description' => "Relationship history and satisfaction rating",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'type' => "Questionnaire",
            'description' => "Quality of life inventory",
            'created_by' => 2,
            'updated_by' => 2
        ]);
        \App\Models\DataType::create([
            'type' => "Interview",
            'description' => "Cognitive processing evaluation",
            'created_by' => 2,
            'updated_by' => 2
        ]);
        \App\Models\DataType::create([
            'type' => "MRI",
            'description' => "Brain mapping",
            'created_by' => 2,
            'updated_by' => 2
        ]);

        // Assign participants 1-10 to the first study
        for ($index = 1; $index < 11; $index++) {
            \App\Models\StudyParticipant::create([
                'participant_id' => $index,
                'study_id' => 1,
            ]);
        }
        // Assign participants 6-15 to the second study
        for ($index = 6; $index < 16; $index++) {
            \App\Models\StudyParticipant::create([
                'participant_id' => $index,
                'study_id' => 2,
            ]);
        }
        // Assign participants 16-20 to the third study
        for ($index = 16; $index < 21; $index++) {
            \App\Models\StudyParticipant::create([
                'participant_id' => $index,
                'study_id' => 3,
            ]);
        }

        // Assign data types 1-3 to the first study
        for ($index = 1; $index < 4; $index++) {
            \App\Models\StudyDataType::create([
                'study_id' => 1,
                'data_type_id' => $index,
            ]);
        }
        // Assign data types 2-4 to the second study
        for ($index = 2; $index < 5; $index++) {
            \App\Models\StudyDataType::create([
                'study_id' => 2,
                'data_type_id' => $index,
            ]);
        }
        // Assign data types 4-5 to the third study
        for ($index = 4; $index < 6; $index++) {
            \App\Models\StudyDataType::create([
                'study_id' => 3,
                'data_type_id' => $index,
            ]);
        }

        // Assign default user manage 1st study and view 2nd study permissions
        \App\Models\StudyPermission::create([
            'user_id' => $default_user->id,
            'study_id' => 1,
            'study_permission' => 'manage_study'
        ]);
        \App\Models\StudyPermission::create([
            'user_id' => $default_user->id,
            'study_id' => 2,
            'study_permission' => 'view_study'
        ]);

        // Assign second user manage 2nd and 3rd study permissions
        \App\Models\StudyPermission::create([
            'user_id' => 2,
            'study_id' => 2,
            'study_permission' => 'manage_study'
        ]);
        \App\Models\StudyPermission::create([
            'user_id' => 2,
            'study_id' => 3,
            'study_permission' => 'manage_study'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Participant;
use \App\Models\StudyDataType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Starter Users
        $default_user = \App\Models\User::create([
            'first_name' => 'Ali Kemal',
            'last_name' => 'Tanriverdi',
            'email' => 'atanrive@binghamton.edu',
            'bnumber' => 'B00450942',
            'active' => 1,
        ]);

        \App\Models\User::create([
            'first_name' => 'Tim',
            'last_name' => 'Cortesi',
            'email' => 'tcortesi@binghamton.edu',
            'bnumber' => 'B00505893',
            'active' => 1,
        ]);


        // Assign default user all basic view permissions
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_users'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_users'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_permissions'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_studies'
        ]);

        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_studies'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_data_types'
        ]);
        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'view_participants'
        ]);

        \App\Models\Permission::create([
            'user_id' => $default_user->id,
            'permission' => 'manage_participants'
        ]);

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
            'start_date' => '2024-04-01',
            'end_date' => '2024-08-01',
            'location' => 'Virtual',
            'design' => 'Cross-sectional',
            'sample_type' => 'Neurodivergent',
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);

        \App\Models\Study::create([
            'pi_user_id' => 2,
            'title' => "Dating, Literary Sexual Encounters and Brain Stimulation Study",
            'description' => "This is a research study being conducted at Binghamton University's Clearview Hall by the Center for Transdisciplinary Research on Intimate Relationships. The purpose of this work is to explore different elements of people's attitudes and beliefs about dating, institutional policies and certain groups (e.g., women and trans people).",
            'start_date' => '2024-03-01',
            'end_date' => '2024-05-01',
            'location' => 'In-person',
            'design' => 'Longitudinal',
            'sample_type' => 'Neurotypical',
            'created_by' => 2,
            'updated_by' => 2
        ]);
        \App\Models\Study::create([
            'pi_user_id' => 2,
            'title' => "Nurses' Perspectives on Barriers while Serving Individuals with Dysphagia and Cognitive Communication Disorders",
            'description' => "Registered nurses (RNs), licensed practical nurses (LPNs) and certified nursing assistants (CNAs) are needed for a research study. Participate in a 30-60 minute, semi-structured interview about nurses' experiences working with patients who experience dysphagia and cognitive communication disorder. The interview will be conducted via Zoom and audio-recorded for data analysis purposes.",
            'start_date' => '2024-02-01',
            'end_date' => '2024-04-01',
            'location' => 'Hybrid',
            'design' => 'Longitudinal',
            'sample_type' => 'Neurodiverse',
            'created_by' => 2,
            'updated_by' => 2
        ]);

        // Starter Data Types
        \App\Models\DataType::create([
            'category' => "Assessment",
            'type' => "Survey",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'category' => "Assessment",
            'type' => "Questionnaire",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'category' => "Behavioral",
            'type' => "Interview",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'category' => "Neurosignal",
            'type' => "EEG",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'category' => "Neurosignal",
            'type' => "Resting (f)MRI",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);
        \App\Models\DataType::create([
            'category' => "Biospecimen",
            'type' => "Blood Test",
            'created_by' => $default_user->id,
            'updated_by' => $default_user->id
        ]);

        // Starter Study Data Types
        \App\Models\StudyDataType::create([
            'study_id' => 1,
            'data_type_id' => 1,
            'description' => "Relationship history and satisfaction rating"
        ]);
        \App\Models\StudyDataType::create([
            'study_id'=> 1,
            'data_type_id' => 2,
            'description' => "Quality of life inventory"
        ]);
        \App\Models\StudyDataType::create([
            'study_id'=> 1,
            'data_type_id' => 6,
            'description' => "Comprehensive metabolic panel"
        ]);
        \App\Models\StudyDataType::create([
            'study_id'=> 2,
            'data_type_id' => 2,
            'description' => "Quality of life inventory"
        ]);
        \App\Models\StudyDataType::create([
            'study_id'=> 2,
            'data_type_id' => 3,
            'description' => "Stress and trauma evaluation"
        ]);
        \App\Models\StudyDataType::create([
            'study_id'=> 3,
            'data_type_id' => 4,
            'description' => null
        ]);
        \App\Models\StudyDataType::create([
            'study_id'=> 3,
            'data_type_id' => 5,
            'description' => "Brain mapping"
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

        // Assign default user manage 1st study and view 2nd study permissions
        \App\Models\StudyUser::create([
            'study_id' => 1,
            'user_id' => $default_user->id,
            'type' => 'manager'
        ]);
        \App\Models\StudyUser::create([
            'study_id' => 2,
            'user_id' => $default_user->id,
            'type' => 'viewer'
        ]);
        \App\Models\StudyUser::create([
            'study_id' => 1,
            'user_id' => 2,
            'type' => 'viewer'
        ]);

        // Assign PI manager to their study
        \App\Models\StudyUser::create([
            'study_id' => 2,
            'user_id' => 2,
            'type' => 'manager'
        ]);
        \App\Models\StudyUser::create([
            'study_id' => 3,
            'user_id' => 2,
            'type' => 'manager'
        ]);
    }
}

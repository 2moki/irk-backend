<?php

namespace Database\Seeders;

use App\Enums\ExamTypeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserCertificate;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserCertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $i = 1;

        foreach ($users as $user) {
            //Create certificate for each user
            //Only new matura for now, because it doesnt require additional grade mapping
            $cert = UserCertificate::create([
                'user_id' => $user->id,
                'exam_type' => ExamTypeEnum::NEW_MATURA,
                'school_id' => null,
                'school_custom_name' => 'Name',
                'issue_date' => today(),
                'is_annex' => false,
                'document_number' => $i,
                'is_verified' => true,
                'document_id' => null,
            ]);
            $i++;
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserCertificate;
use App\Models\Qualification;
use App\Models\QualificationScore;

class QualificationScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake();

        //Create a score for all certificates
        $certs = UserCertificate::all();
        foreach ($certs as $cert) {
            //Each certificate has all possible scores
            $quals = Qualification::all();
            foreach ($quals as $qual) {
                QualificationScore::create([
                    'user_certificate_id' => $cert->id,
                    'qualification_id' => $qual->id,
                    'value' => $faker->numberBetween(0, 100),
                    'is_bilingual' => false,
                ]);
            }
        }
    }
}

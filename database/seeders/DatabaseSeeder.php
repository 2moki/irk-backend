<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            // Auth & Users
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

            // Dictionaries
            StudyLevelSeeder::class,
            StudyModeSeeder::class,
            DegreeTitleSeeder::class,
            LanguageSeeder::class,
            SchoolSeeder::class,

            GradeMappingSeeder::class,
            GradeSeeder::class,
            AcademicYearSeeder::class,

            // Core
            MajorSeeder::class,
            RecruitmentSeeder::class,

            // Candidates
            CandidateSeeder::class,
        ]);
    }
}

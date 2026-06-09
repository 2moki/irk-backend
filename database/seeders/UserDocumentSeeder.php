<?php

namespace Database\Seeders;

use App\Models\UserDocument;
use Illuminate\Database\Seeder;

class UserDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserDocument::factory()
            ->count(50)
            ->create();
    }
}

<?php

namespace Database\Seeders;

use App\Enums\ExamTypeEnum;
use App\Models\ExamType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ExamTypeEnum::cases() as $type) {
            ExamType::create([
                'id' => $type->id(),
                'name' => $type->rawString()
            ]);
        }
    }
}

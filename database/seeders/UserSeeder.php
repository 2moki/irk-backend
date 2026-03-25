<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\RoleType;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create();

        User::factory()
            ->create([
                'email' => 'admin@localhost',
                'password' => 'password',
            ])->assignRole(RoleType::ADMIN->value);
    }
}

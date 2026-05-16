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
            ->create()
            ->each(function (User $user): void {
                $user->assignRole(RoleType::CANDIDATE->value);
            });

        User::factory()
            ->create([
                'email' => 'admin@local.test',
                'password' => 'password',
            ])->assignRole(RoleType::ADMIN->value);
    }
}

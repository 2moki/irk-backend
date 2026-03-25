<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\RoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => RoleType::ADMIN->value])
            ->givePermissionTo(Permission::all());

        // TODO: assign permissions
        Role::create(['name' => RoleType::EMPLOYEE->value]);

        Role::create(['name' => RoleType::CANDIDATE->value]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

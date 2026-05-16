<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\PermissionType;
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

        Role::create(['name' => RoleType::EMPLOYEE->value])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => RoleType::CANDIDATE->value])
            ->givePermissionTo(PermissionType::RECRUITMENT_APPLICATION_ACCESS_OWN->value)
            ->givePermissionTo(PermissionType::RECRUITMENT_APPLICATION_MANAGE_OWN->value)
            ->givePermissionTo(PermissionType::RECRUITMENT_ACCESS->value)
            ->givePermissionTo(PermissionType::MAJOR_ACCESS->value);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\PermissionType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // TODO: remove phpstan-ignore-next-line after declaring permissions' enum
        /** @phpstan-ignore-next-line  */
        foreach (PermissionType::cases() as $permissionType) {
            Permission::create(['name' => $permissionType->value]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

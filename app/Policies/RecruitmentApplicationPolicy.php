<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Auth\PermissionType;
use App\Models\Pivots\RecruitmentApplication;
use App\Models\User;

class RecruitmentApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionType::RECRUITMENT_APPLICATION_ACCESS_ANY->value)
            || $user->can(PermissionType::RECRUITMENT_APPLICATION_ACCESS_OWN->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        if ($user->can(PermissionType::RECRUITMENT_APPLICATION_ACCESS_ANY->value)) {
            return true;
        }

        return $recruitmentApplication->application->user_id === $user->id
            && $user->can(PermissionType::RECRUITMENT_APPLICATION_ACCESS_OWN->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionType::RECRUITMENT_APPLICATION_MANAGE_ANY->value)
            || $user->can(PermissionType::RECRUITMENT_APPLICATION_MANAGE_OWN->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        if ($user->can(PermissionType::RECRUITMENT_APPLICATION_MANAGE_ANY->value)) {
            return true;
        }

        return $recruitmentApplication->application->user_id === $user->id
            && $user->can(PermissionType::RECRUITMENT_APPLICATION_MANAGE_OWN->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        if ($user->can(PermissionType::RECRUITMENT_APPLICATION_MANAGE_ANY->value)) {
            return true;
        }

        return $recruitmentApplication->application->user_id === $user->id
            && $user->can(PermissionType::RECRUITMENT_APPLICATION_MANAGE_OWN->value);
    }
}

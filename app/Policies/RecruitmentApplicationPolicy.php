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
        return $user->can(PermissionType::RECRUITMENT_APPLICATION_ACCESS->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        return $recruitmentApplication->application->user_id === $user->id
            && $user->can(PermissionType::RECRUITMENT_APPLICATION_ACCESS->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RecruitmentApplication $recruitmentApplication): bool
    {
        return false;
    }
}

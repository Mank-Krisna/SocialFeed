<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->is_admin) {
            return true;
        }
        return null;
    }

    /**
     * Only group creator or pivot-role=admin can update group settings.
     */
    public function update(User $user, Group $group): bool
    {
        return $user->id === $group->user_id
            || $group->isAdmin($user);
    }

    /**
     * Only group creator or pivot-role=admin can delete the group.
     */
    public function delete(User $user, Group $group): bool
    {
        return $user->id === $group->user_id
            || $group->isAdmin($user);
    }

    /**
     * Any authenticated user can join a public group.
     * Private groups: only if invited (simplified: always allowed, invite flow future).
     */
    public function join(User $user, Group $group): bool
    {
        return ! $group->isMember($user);
    }

    /**
     * Any member (except creator) can leave the group.
     */
    public function leave(User $user, Group $group): bool
    {
        return $group->isMember($user)
            && $user->id !== $group->user_id;
    }
}

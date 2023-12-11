<?php

namespace App\Interfaces;

interface UserGroupPermissionInterface
{
    public function indexUserGroupPermission(): array;
    public function indexUserGroupPermissionByUserGroupId(int $user_group_id): array;
    public function createUserGroupPermission(array $request): array;
    public function updateUserGroupPermission(array $request, int $user_group_permission_id): array;
    public function showUserGroupPermission(int $user_group_permission_id): array;
    public function deleteUserGroupPermission(int $user_group_permission_id): array;
    public function createMultipleUserGroupPermission(array $permissions, int $user_group_id): array;
    public function updateUserGroupPermissionByUserGroupId(array $permissions, int $user_group_id): array;
    public function deleteUserGroupPermissionByUserGroupId(int $user_group_id): array;
}

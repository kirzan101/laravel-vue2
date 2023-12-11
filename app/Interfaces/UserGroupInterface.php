<?php

namespace App\Interfaces;

interface UserGroupInterface
{
    public function indexUserGroup(): array;
    public function createUserGroup(array $request): array;
    public function updateUserGroup(array $request, int $user_group_id): array;
    public function showUserGroup(int $user_group_id): array;
    public function deleteUserGroup(int $user_group_id): array;
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserGroup;
use App\Models\UserGroupPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_groups = [
            [
                'name' => 'admin',
                'code' => 'ADMIN',
                'description' => 'admin account'
            ]
        ];

        $permissions = Permission::all();

        foreach ($user_groups as $user_group) {
            $result = UserGroup::create($user_group);

            if ($result) {
                // create user group permissions
                foreach ($permissions as $permission) {
                    UserGroupPermission::create([
                        'user_group_id' => $result->id,
                        'permission_id' => $permission->id
                    ]);
                }
            }
        }
    }
}

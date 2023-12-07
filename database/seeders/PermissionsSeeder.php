<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'module' => 'users',
                'type' => 'create'
            ],
            [
                'module' => 'users',
                'type' => 'read'
            ],
            [
                'module' => 'users',
                'type' => 'update'
            ],
            [
                'module' => 'users',
                'type' => 'delete'
            ],
            [
                'module' => 'user_groups',
                'type' => 'create'
            ],
            [
                'module' => 'user_groups',
                'type' => 'read'
            ],
            [
                'module' => 'user_groups',
                'type' => 'update'
            ],
            [
                'module' => 'user_groups',
                'type' => 'delete'
            ],
        ];

        foreach($permissions as $permission) {
            Permission::create($permission);
        }
    }
}

<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create roles with assigned permissions
        $roles = [
            'superadmin' => [
                'permission list',
                'permission create',
                'permission edit',
                'permission delete',
                'role list',
                'role create',
                'role edit',
                'role delete',
                'user list',
                'user create',
                'user edit',
                'user delete',
                'send for approval',
                'view all license applications',
                'approve license applications',
                'finalize license approval',
                'assign provisional license',
                'create exams results',
                'view exams results',
                'edit exams results',
                'delete exams results',

            ],
            'personnel' => [
                'register for license',
                'upload documents',
                'view own applications',
                'view own exam results',
            ],
            'admin' => [
                'screen documents',
                'send for approval',
                'view all license applications',
                'finalize license approval',
                'assign provisional license',
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }

    }
}

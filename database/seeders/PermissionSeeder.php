<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for each user type
        $permissions = [
            'register for license',
            'upload documents',
            'view own exam results',
            'view own applications',
            'screen documents',
            'send for approval',
            'view all license applications',
            'approve license applications',
            'finalize license approval',
            'assign provisional license',
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
            'create exams results',
            'view exams results',
            'edit exams results',
            'delete exams results',
            'create exams',
            'view exams',
            'edit exams',
            'delete exams',
            'publish exams',
            'create subjects',
            'view subjects',
            'edit subjects',
            'delete subjects',
            'assign subjects',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}

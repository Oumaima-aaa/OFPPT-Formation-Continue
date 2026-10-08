<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $viewPermissions = [
            'regions.view',
            'domaines.view',
            'themes.view',
            'etablissements.view',
            'plans.view',
            'actions.view',
            'catalog.view',
        ];

        $modulePermissions = [
            'regions.manage', 'domaines.manage', 'themes.manage', 'etablissements.manage',
            'entreprises.manage', 'plans.manage', 'actions.manage', 'intervenants.manage',
            'competences.manage', 'diplomes.manage', 'certifications.manage', 'statistics.view',
        ];

        $userPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.activate',
            'users.deactivate',
        ];

        $permissions = array_merge($viewPermissions, $modulePermissions, $userPermissions);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'Super Admin' => $permissions,
            'Regional Manager' => [
                'regions.view',
                'domaines.view', 'themes.view',
                'etablissements.manage',
                'entreprises.manage', 'plans.manage', 'actions.manage',
                'intervenants.manage', 'competences.manage', 'diplomes.manage', 'certifications.manage',
                'statistics.view', 'catalog.view',
            ],
            'Local Manager' => [
                'domaines.view', 'themes.view',
                'etablissements.view',
                'entreprises.manage', 'plans.manage', 'actions.manage',
                'intervenants.manage', 'competences.manage', 'diplomes.manage', 'certifications.manage',
                'statistics.view', 'catalog.view',
            ],
            'Company' => ['plans.manage', 'actions.view', 'catalog.view', 'statistics.view'],
            'Trainer' => [
                'plans.view', 'actions.view', 'catalog.view',
                'intervenants.manage', 'competences.manage', 'diplomes.manage', 'certifications.manage',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}

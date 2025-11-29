<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // Organizations
            'view_organizations',
            'create_organizations',
            'edit_organizations',
            'delete_organizations',
            
            // Group Heads
            'view_group_heads',
            'create_group_heads',
            'edit_group_heads',
            'delete_group_heads',
            
            // Programs
            'view_programs',
            'create_programs',
            'edit_programs',
            'delete_programs',
            
            // Activities
            'view_activities',
            'create_activities',
            'edit_activities',
            'delete_activities',
            'approve_activities',
            
            // Questionnaires
            'view_questionnaires',
            'create_questionnaires',
            'edit_questionnaires',
            'delete_questionnaires',
            
            // Participants
            'view_participants',
            'create_participants',
            'edit_participants',
            'delete_participants',
            
            // Responses
            'view_responses',
            'export_responses',
            
            // Analytics
            'view_analytics',
            'export_analytics',
            
            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Settings
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'super_admin' => Permission::all(),
            'admin' => [
                'view_organizations', 'create_organizations', 'edit_organizations',
                'view_group_heads', 'create_group_heads', 'edit_group_heads',
                'view_programs', 'create_programs', 'edit_programs',
                'view_users', 'create_users', 'edit_users',
                'view_analytics', 'export_analytics',
            ],
            'organization_admin' => [
                'view_organizations', 'edit_organizations',
                'view_group_heads', 'create_group_heads', 'edit_group_heads',
                'view_programs', 'create_programs', 'edit_programs',
                'view_participants', 'create_participants', 'edit_participants',
                'view_analytics', 'export_analytics',
            ],
            'group_head' => [
                'view_programs', 'create_programs', 'edit_programs',
                'view_activities', 'view_participants',
                'view_analytics', 'export_analytics',
            ],
            'program_admin' => [
                'view_programs', 'edit_programs',
                'view_activities', 'create_activities', 'edit_activities',
                'view_questionnaires', 'create_questionnaires', 'edit_questionnaires',
                'view_participants', 'create_participants', 'edit_participants',
                'view_responses', 'export_responses',
                'view_analytics', 'export_analytics',
            ],
            'program_manager' => [
                'view_programs',
                'view_activities', 'approve_activities',
                'view_participants',
                'view_responses',
                'view_analytics',
            ],
            'program_moderator' => [
                'view_programs',
                'view_activities',
                'view_questionnaires',
                'view_participants',
                'view_responses',
            ],
            'participant_general' => [],
            'participant_guest' => [],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            if (is_array($permissions)) {
                $role->givePermissionTo($permissions);
            } else {
                $role->givePermissionTo($permissions);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the roles, permissions, and role_permissions tables.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Editor', 'slug' => 'editor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Author', 'slug' => 'author', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);

        // Define permission groups and their permissions
        $permissionGroups = [
            'menu' => ['menu.view', 'menu.create', 'menu.edit', 'menu.delete'],
            'page' => ['page.view', 'page.create', 'page.edit', 'page.delete', 'page.publish'],
            'news' => ['news.view', 'news.create', 'news.edit', 'news.delete', 'news.publish'],
            'event' => ['event.view', 'event.create', 'event.edit', 'event.delete', 'event.publish'],
            'vacancy' => ['vacancy.view', 'vacancy.create', 'vacancy.edit', 'vacancy.delete', 'vacancy.publish'],
            'tender' => ['tender.view', 'tender.create', 'tender.edit', 'tender.delete', 'tender.publish'],
            'service' => ['service.view', 'service.create', 'service.edit', 'service.delete', 'service.publish'],
            'explore' => ['explore.view', 'explore.create', 'explore.edit', 'explore.delete', 'explore.publish'],
            'program' => ['program.view', 'program.create', 'program.edit', 'program.delete', 'program.publish'],
            'resource' => ['resource.view', 'resource.create', 'resource.edit', 'resource.delete', 'resource.publish'],
            'gallery' => ['gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete'],
            'government' => ['government.view', 'government.create', 'government.edit', 'government.delete'],
            'contact' => ['contact.view', 'contact.edit', 'contact.submissions'],
            'forum' => ['forum.view', 'forum.create', 'forum.edit', 'forum.delete', 'forum.moderate'],
            'poll' => ['poll.view', 'poll.create', 'poll.edit', 'poll.delete'],
            'setting' => ['setting.view', 'setting.edit', 'setting.maintenance'],
            'social' => ['social.view', 'social.edit'],
            'subscriber' => ['subscriber.view', 'subscriber.export'],
            'slider' => ['slider.view', 'slider.create', 'slider.edit', 'slider.delete'],
            'user' => ['user.view', 'user.create', 'user.edit', 'user.delete', 'user.assign_role'],
        ];

        $permissions = [];
        foreach ($permissionGroups as $group => $perms) {
            foreach ($perms as $perm) {
                $permissions[] = [
                    'name' => $perm,
                    'group' => $group,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('permissions')->insert($permissions);

        // Fetch IDs
        $superAdminId = DB::table('roles')->where('slug', 'super_admin')->value('id');
        $editorId = DB::table('roles')->where('slug', 'editor')->value('id');
        $authorId = DB::table('roles')->where('slug', 'author')->value('id');
        $allPermissions = DB::table('permissions')->pluck('id', 'name');

        // Super Admin gets ALL permissions
        $superAdminPerms = [];
        foreach ($allPermissions as $name => $id) {
            $superAdminPerms[] = ['role_id' => $superAdminId, 'permission_id' => $id];
        }
        DB::table('role_permissions')->insert($superAdminPerms);

        // Editor gets all except user management and settings
        $editorPerms = [];
        foreach ($allPermissions as $name => $id) {
            if (!str_starts_with($name, 'user.') && !str_starts_with($name, 'setting.')) {
                $editorPerms[] = ['role_id' => $editorId, 'permission_id' => $id];
            }
        }
        DB::table('role_permissions')->insert($editorPerms);

        // Author gets view + create on content types only
        $authorAllowed = [
            'news.view', 'news.create',
            'event.view', 'event.create',
            'vacancy.view', 'vacancy.create',
            'tender.view', 'tender.create',
            'page.view', 'page.create',
            'program.view', 'program.create',
            'resource.view', 'resource.create',
            'gallery.view', 'gallery.create',
            'forum.view', 'forum.create',
            'poll.view',
        ];
        $authorPerms = [];
        foreach ($authorAllowed as $permName) {
            if (isset($allPermissions[$permName])) {
                $authorPerms[] = ['role_id' => $authorId, 'permission_id' => $allPermissions[$permName]];
            }
        }
        DB::table('role_permissions')->insert($authorPerms);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            // Posts
            ['name' => 'Create Post', 'slug' => 'post.create', 'group' => 'posts'],
            ['name' => 'Edit Post', 'slug' => 'post.edit', 'group' => 'posts'],
            ['name' => 'Delete Post', 'slug' => 'post.delete', 'group' => 'posts'],
            ['name' => 'Publish Post', 'slug' => 'post.publish', 'group' => 'posts'],

            // Categories
            ['name' => 'Manage Categories', 'slug' => 'category.manage', 'group' => 'categories'],

            // Tags
            ['name' => 'Manage Tags', 'slug' => 'tag.manage', 'group' => 'tags'],

            // Users & Roles
            ['name' => 'Manage Users', 'slug' => 'user.manage', 'group' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'role.manage', 'group' => 'roles'],

            // Settings
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'group' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name'], 'group_name' => $permission['group']]
            );
        }

        // 2. Create Roles
        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $editorRole = Role::firstOrCreate(['slug' => 'editor'], ['name' => 'Editor']);
        $authorRole = Role::firstOrCreate(['slug' => 'author'], ['name' => 'Author']);

        // 3. Assign Permissions to Roles
        // Admin gets all
        $adminRole->permissions()->sync(Permission::all());

        // Editor gets Post/Category/Tag permissions
        $editorPermissions = Permission::whereIn('group_name', ['posts', 'categories', 'tags'])->get();
        $editorRole->permissions()->sync($editorPermissions);

        // Author gets Create/Edit Post (but maybe separate "edit own" logic is needed in code, usually permission is generic 'post.create')
        // For basic RBAC, let's give them create. Policy will handle "own".
        $authorPermissions = Permission::whereIn('slug', ['post.create', 'post.edit'])->get();
        $authorRole->permissions()->sync($authorPermissions);


        // 4. Migrate Existing Users
        $users = User::all();
        foreach ($users as $user) {
            // Check if user already has roles to avoid duplicates if re-run
            if ($user->roles()->exists()) {
                continue;
            }

            // Map existing 'type' to Role
            // UserType enum: Admin='admin', SuperAdmin='superAdmin'
            // Assuming string comparison or enum value

            if ($user->userType === \App\UserType::SuperAdmin || $user->userType === \App\UserType::Admin) {
                $user->roles()->attach($adminRole);
            } else {
                // Default to Author if not admin
                $user->roles()->attach($authorRole);
            }
        }
    }
}

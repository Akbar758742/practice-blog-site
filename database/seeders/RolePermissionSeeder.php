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
            ['name' => 'View Post', 'slug' => 'post.view', 'group' => 'posts'], // Added missing view perm based on Policy checking

            // Categories
            ['name' => 'Manage Categories', 'slug' => 'category.manage', 'group' => 'categories'],

            // Tags
            ['name' => 'Manage Tags', 'slug' => 'tag.manage', 'group' => 'tags'],

            // Users & Roles
            ['name' => 'Manage Users', 'slug' => 'user.manage', 'group' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'role.manage', 'group' => 'roles'],

            // Settings
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'group' => 'settings'],

            // Comments
            ['name' => 'View Comments', 'slug' => 'comment.view', 'group' => 'comments'],
            ['name' => 'Moderate Comments', 'slug' => 'comment.moderate', 'group' => 'comments'],
            ['name' => 'Delete Comments', 'slug' => 'comment.delete', 'group' => 'comments'],

            // Pages (Static Pages) - Admin Only usually, but let's make permission
            ['name' => 'Manage Pages', 'slug' => 'page.manage', 'group' => 'pages'],
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

        // Editor gets Post/Category/Tag permissions + Comment Moderate
        $editorPermissions = Permission::whereIn('group_name', ['posts', 'categories', 'tags', 'comments'])->get();
        $editorRole->permissions()->sync($editorPermissions);

        // Author gets Create/Edit Post (but maybe separate "edit own" logic is needed in code, usually permission is generic 'post.create')
        // For basic RBAC, let's give them create. Policy will handle "own".
        // Author gets Create/Edit Post (but maybe separate "edit own" logic is needed in code, usually permission is generic 'post.create')
        // For basic RBAC, let's give them create. Policy will handle "own".
        // Also give view permission
        $authorPermissions = Permission::whereIn('slug', ['post.create', 'post.edit', 'post.view', 'comment.view'])->get();
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

        // 5. Explicitly Assign Roles to Seeded Users
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->roles()->sync($adminRole);
        }

        $editorUser = User::where('email', 'editor@example.com')->first();
        if ($editorUser) {
            $editorUser->roles()->sync($editorRole);
        }

        $authorUser = User::where('email', 'author@example.com')->first();
        if ($authorUser) {
            $authorUser->roles()->sync($authorRole);
        }
    }
}

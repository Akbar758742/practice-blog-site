<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the storage directory exists
        Storage::disk('public')->makeDirectory('site');

        // Define source images from the backend vendors folder
        $sourceLogoPath = public_path('backend/vendors/images/deskapp-logo.svg');
        $sourceFaviconPath = public_path('backend/vendors/images/favicon-32x32.png');
        $sourceSigninImagePath = public_path('backend/vendors/images/login-page-img.png');

        // Define destination paths in storage
        $logoStoragePath = null;
        $faviconStoragePath = null;
        $signinImageStoragePath = null;

        // Copy logo to storage if source exists
        if (File::exists($sourceLogoPath)) {
            $logoFileName = 'site/site-logo-' . time() . '.svg';
            Storage::disk('public')->put($logoFileName, File::get($sourceLogoPath));
            $logoStoragePath = $logoFileName;
        }

        // Copy favicon to storage if source exists
        if (File::exists($sourceFaviconPath)) {
            $faviconFileName = 'site/site-favicon-' . time() . '.png';
            Storage::disk('public')->put($faviconFileName, File::get($sourceFaviconPath));
            $faviconStoragePath = $faviconFileName;
        }

        // Copy signin image to storage if source exists
        if (File::exists($sourceSigninImagePath)) {
            $signinImageFileName = 'site/signin-image-' . time() . '.png';
            Storage::disk('public')->put($signinImageFileName, File::get($sourceSigninImagePath));
            $signinImageStoragePath = $signinImageFileName;
        }

        // Create or update the general settings record
        GeneralSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Blog Admin',
                'site_email' => 'admin@example.com',
                'site_phone' => '+1234567890',
                'site_address' => '123 Main Street, City, Country',
                'site_logo' => $logoStoragePath,
                'site_favicon' => $faviconStoragePath,
                'signin_image' => $signinImageStoragePath,
                'site_meta_keywords' => 'blog, admin, cms, content management',
                'site_meta_description' => 'A powerful blog administration panel for managing your content.',
            ]
        );

        $this->command->info('Settings seeded successfully!');
        $this->command->info('Logo: ' . ($logoStoragePath ?: 'Not found'));
        $this->command->info('Favicon: ' . ($faviconStoragePath ?: 'Not found'));
        $this->command->info('Sign-in Image: ' . ($signinImageStoragePath ?: 'Not found'));
    }
}

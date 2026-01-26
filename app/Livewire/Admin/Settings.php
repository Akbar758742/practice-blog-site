<?php

namespace App\Livewire\Admin;

use GuzzleHttp\Psr7\Request;
use App\Models\GeneralSetting;
use App\Traits\AlertTrait;
use App\Helpers\Settings as SettingsHelper;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
     use AlertTrait, WithFileUploads;

    public $tab=null;
    public $default_tab='general_settings';
    protected $queryString = ['tab'=>['keep'=>true]];

     public $site_title, $site_email, $site_phone, $site_address, $site_meta_keywords, $site_meta_description;

    // Existing logo/favicon paths from database
    public $existing_site_logo;
    public $existing_site_favicon;

    // New file uploads
    public $site_logo;
    public $site_favicon;

    public function selectTab($tab)
    {
        $this->tab=$tab;
    }
    public function mount()
    {
       $this->tab = Request('tab') ? Request('tab') : $this->default_tab;

       $settings = GeneralSetting::take(1)->first();
       if(!is_null($settings)){
           $this->site_title = $settings->site_name;
           $this->site_email = $settings->site_email;
           $this->site_phone = $settings->site_phone;
           $this->site_address = $settings->site_address;
           $this->existing_site_logo = $settings->site_logo;
           $this->existing_site_favicon = $settings->site_favicon;
           $this->site_meta_keywords = $settings->site_meta_keywords;
           $this->site_meta_description = $settings->site_meta_description;
       }
    }



       public function updateSettings()
         {
              $this->validate([
                'site_title'=>'required',
                'site_email'=>'required|email',
                'site_phone'=>'required',
                // 'site_address'=>'required',
              ]);

                  $settings = GeneralSetting::take(1)->first();
                  $data=[
                    'site_name'=>$this->site_title,
                    'site_email'=>$this->site_email,
                    'site_phone'=>$this->site_phone,
                    'site_address'=>$this->site_address,
                    'site_meta_keywords'=>$this->site_meta_keywords,
                    'site_meta_description'=>$this->site_meta_description,
                  ];

              if(!is_null($settings)){
                $query =$settings->update($data);
              }else{
                $query = GeneralSetting::insert($data);
              }
                SettingsHelper::clearCache();
                $this->successAlert('Success', 'Settings updated successfully.');
         }

    public function updateLogo()
    {
        $this->validate([
            'site_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $settings = GeneralSetting::take(1)->first();

        // Delete old logo if exists
        if ($settings && $settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
            Storage::disk('public')->delete($settings->site_logo);
        }

        // Store new logo
        $logoPath = $this->site_logo->store('site', 'public');

        if (!is_null($settings)) {
            $settings->update(['site_logo' => $logoPath]);
        } else {
            GeneralSetting::create(['site_logo' => $logoPath]);
        }

        $this->existing_site_logo = $logoPath;
        $this->site_logo = null;
        SettingsHelper::clearCache();
        $this->successAlert('Success', 'Site logo updated successfully.');
    }

    public function updateFavicon()
    {
        $this->validate([
            'site_favicon' => 'required|image|mimes:ico,png,jpg,jpeg,gif,svg|max:1024',
        ]);

        $settings = GeneralSetting::take(1)->first();

        // Delete old favicon if exists
        if ($settings && $settings->site_favicon && Storage::disk('public')->exists($settings->site_favicon)) {
            Storage::disk('public')->delete($settings->site_favicon);
        }

        // Store new favicon
        $faviconPath = $this->site_favicon->store('site', 'public');

        if (!is_null($settings)) {
            $settings->update(['site_favicon' => $faviconPath]);
        } else {
            GeneralSetting::create(['site_favicon' => $faviconPath]);
        }

        $this->existing_site_favicon = $faviconPath;
        $this->site_favicon = null;
        SettingsHelper::clearCache();
        $this->successAlert('Success', 'Favicon updated successfully.');
    }


    public function render()
    {
        return view('livewire.admin.settings');
    }
}

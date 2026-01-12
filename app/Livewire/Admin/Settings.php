<?php

namespace App\Livewire\Admin;

use GuzzleHttp\Psr7\Request;
use App\Models\GeneralSetting;
use App\Traits\AlertTrait;
use Livewire\Component;

class Settings extends Component
{
     use AlertTrait;
    public $tab=null;
    public $default_tab='general_settings';
    protected $queryString = ['tab'=>['keep'=>true]];

     public $site_title, $site_email, $site_phone, $site_address,$site_logo, $site_favicon, $site_meta_keywords, $site_meta_description;

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
           $this->site_logo = $settings->site_logo;
           $this->site_favicon = $settings->site_favicon;
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


            //   try {
                  $settings = GeneralSetting::take(1)->first();
                  $data=[
                    'site_name'=>$this->site_title,
                    'site_email'=>$this->site_email,
                    'site_phone'=>$this->site_phone,
                    'site_address'=>$this->site_address,
                    'site_logo'=>$this->site_logo,
                    'site_favicon'=>$this->site_favicon,
                    'site_meta_keywords'=>$this->site_meta_keywords,
                    'site_meta_description'=>$this->site_meta_description,
                  ];



              if(!is_null($settings)){
                $query =$settings->update($data);
              }else{
                $query = GeneralSetting::insert($data);
              }
                $this->successAlert('Success', 'Settings updated successfully.');
                // $this->redirectRoute('admin.settings');
            //   } catch (\Exception $e) {
            //       $this->errorAlert('Error', 'An error occurred while updating settings.');
            //       return;
            //   }



         }


    public function render()
    {
        return view('livewire.admin.settings');
    }
}

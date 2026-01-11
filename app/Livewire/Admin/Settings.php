<?php

namespace App\Livewire\Admin;

use GuzzleHttp\Psr7\Request;
use App\Models\GeneralSetting;
use Livewire\Component;

class Settings extends Component
{
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
    }
    public function render()
    {
        return view('livewire.admin.settings');
    }
}

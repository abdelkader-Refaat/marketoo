<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Http\Controllers\Controller;
use App\Services\Core\SettingService;
use App\Models\PublicSettings\SiteSetting;

class IntroSetting extends Controller
{
    public function index()
    {
        $data =  SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        return view('admin.landing_page_management.intro_setting.index' , compact('data'));
    }
}

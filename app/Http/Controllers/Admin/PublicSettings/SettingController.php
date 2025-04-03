<?php

namespace App\Http\Controllers\Admin\PublicSettings;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Core\SettingService;

class SettingController extends Controller
{

    public function __construct(private SettingService $settingService)
    {
    }

    public function switchLang($lang)
    {
        if (in_array($lang, languages())) {
            if (session()->has('lang')) {
                session()->forget('lang');
            }
            session()->put('lang', $lang);
        } else {
            if (session()->has('lang')) {
                session()->forget('lang');
            }
            session()->put('lang', 'ar');
        }
        return redirect()->back();
    }

    public function index()
    {
        $data = $this->settingService->get();
        return view('admin.public-settings.settings.index', compact('data'));
    }


    public function update(Request $request)
    {
        $data = $this->settingService->edit($request);
        return back()->with($data['key'], $data['msg']);
    }

}

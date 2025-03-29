<?php

namespace App\Http\Controllers\Admin\PublicSettings;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Core\SettingService;

class SettingController extends Controller
{
    private $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
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

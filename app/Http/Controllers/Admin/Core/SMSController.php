<?php

namespace App\Http\Controllers\Admin\Core;

use App\Models\Core\SMS;
use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublicSettings\Sms\StoreRequest;


class SMSController extends Controller
{
    public function index()
    {
        $smss = SMS::latest()->get();
        return view('admin.sms.index', compact('smss'));
    }

    public function change(Request $request)
    {
        $sms = SMS::findOrFail($request->id) ;
        $disableAll = SMS::get()->each->update(['active' => 0]);
        if ($disableAll)
            $sms->update(['active' => 1]);

        return response()->json();
    }

    public function update(StoreRequest $request, $id)
    {
        SMS::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('تعديل باقة رسائل') ;
        return response()->json();
    }
}

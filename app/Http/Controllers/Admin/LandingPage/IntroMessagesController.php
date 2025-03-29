<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroMessages;

class IntroMessagesController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $messages = IntroMessages::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_messages.table' ,compact('messages'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_messages.index');
    }

    public function show($id)
    {
        $message = IntroMessages::findOrFail($id);
        return view('admin.landing_page_management.intro_messages.show', compact('message'));
    }

    public function destroy($id)
    {
        IntroMessages::findOrFail($id)->delete();
        ReportTrait::addToLog('حذف رسالة خاصه من الرسائل المرسلة للموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroMessages::whereIn('id' , $ids)->get()->each->delete()) {
            ReportTrait::addToLog('حذف العديد من الرسائل الخاصه من الرسائل المرسلة للموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

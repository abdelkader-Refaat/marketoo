<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroService;
use App\Http\Requests\Admin\LandingPage\IntroServices\StoreRequest;

class IntroServiceController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $services = IntroService::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_services.table' ,compact('services'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_services.index');
    }


    public function create()
    {
        return view('admin.landing_page_management.intro_services.create');
    }
    public function store(StoreRequest $request)
    {
        IntroService::create($request->validated());
        ReportTrait::addToLog('  اضافه خدمة الي قسم خدماتنا بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introservices.index')]);
    }

    public function edit($id)
    {
        $service = IntroService::findOrFail($id);
        return view('admin.landing_page_management.intro_services.edit' , ['service' => $service]);
    }

    public function update(StoreRequest $request, $id)
    {
        IntroService::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('  تعديل خدمة في قسم خدماتنا بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introservices.index')]);
    }


    public function show($id)
    {
        $service = IntroService::findOrFail($id);
        return view('admin.landing_page_management.intro_services.show' , ['service' => $service]);
    }

    public function destroy($id)
    {
        IntroService::findOrFail($id)->delete();
        ReportTrait::addToLog('  حذف خدمة من قسم خدماتنا بالموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroService::whereIn('id' , $ids)->get()->each->delete()) {
            ReportTrait::addToLog('  حذف مجموعه من الخدمات من قسم خدماتنا بالموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

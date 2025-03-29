<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroPartner;
use App\Http\Requests\Admin\LandingPage\IntroPartners\StoreRequest;
use App\Http\Requests\Admin\LandingPage\IntroPartners\UpdateRequest;

class IntroPartnerController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $partners = IntroPartner::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_partners.table' ,compact('partners'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_partners.index');
    }

    public function create()
    {
        return view('admin.landing_page_management.intro_partners.create');
    }


    public function store(StoreRequest $request)
    {
        IntroPartner::create($request->validated());
        ReportTrait::addToLog('  اضافه شريك لقسم شركائنا في العمل') ;
        return response()->json(['url' => route('admin.introparteners.index')]);
    }
    public function edit($id)
    {
        $partner = IntroPartner::findOrFail($id);
        return view('admin.landing_page_management.intro_partners.edit' , ['partner' => $partner]);
    }

    public function update(UpdateRequest $request, $id)
    {
        IntroPartner::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('  تعديل شريك  في قسم شركائنا في العمل') ;
        return response()->json(['url' => route('admin.introparteners.index')]);
    }
    public function show($id)
    {
        $partner = IntroPartner::findOrFail($id);
        return view('admin.landing_page_management.intro_partners.show' , ['partner' => $partner]);
    }

    public function destroy($id)
    {
        IntroPartner::findOrFail($id)->delete();
        ReportTrait::addToLog('  حذف شريك  من قسم شركائنا في العمل') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroPartner::whereIn('id' , $ids)->get()->each->delete()) {
            ReportTrait::addToLog('  حذف مجموعه من الشركاء  من قسم شركائنا في العمل') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

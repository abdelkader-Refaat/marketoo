<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroSocial;
use App\Http\Requests\Admin\LandingPage\IntroSocials\StoreRequest;

class IntroSocialController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $socials = IntroSocial::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_socials.table' ,compact('socials'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_socials.index');
    }


    public function create()
    {
        return view('admin.landing_page_management.intro_socials.create');
    }

    public function store(StoreRequest $request)
    {
        IntroSocial::create($request->validated());
        ReportTrait::addToLog('  اضافه وسيلة تواصل لقسم وسائل التواصل الخاصة بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introsocials.index')]);
    }

    public function edit($id)
    {
        $social = IntroSocial::findOrFail($id);
        return view('admin.landing_page_management.intro_socials.edit' , ['social' => $social]);
    }

    public function update(StoreRequest $request, $id)
    {
        IntroSocial::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('  تعديل وسيلة تواصل  في قسم وسائل التواصل الخاصة بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introsocials.index')]);
    }

    public function show($id)
    {
        $social = IntroSocial::findOrFail($id);
        return view('admin.landing_page_management.intro_socials.show' , ['social' => $social]);
    }
    public function destroy($id)
    {
        IntroSocial::findOrFail($id)->delete();
        ReportTrait::addToLog('  حذف وسيلة تواصل  من قسم وسائل التواصل الخاصة بالموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroSocial::whereIn('id' , $ids)->get()->each->delete()) {
            ReportTrait::addToLog('  حذف محموعه من وسائل التواصل  من قسم وسائل التواصل الخاصة بالموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

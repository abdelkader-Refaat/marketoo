<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroSlider;
use App\Http\Requests\Admin\LandingPage\IntroSliders\StoreRequest;
use App\Http\Requests\Admin\LandingPage\IntroSliders\UpdateRequest;

class IntroSliderController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $sliders = IntroSlider::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_sliders.table' ,compact('sliders'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_sliders.index');
    }


    public function create()
    {
        return view('admin.landing_page_management.intro_sliders.create');
    }

    public function store(StoreRequest $request)
    {
        IntroSlider::create($request->validated());
        ReportTrait::addToLog('اضافة صورة لقسم البنرات الخاص بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introsliders.index')]);
    }

    public function edit($id)
    {
        $slider = IntroSlider::findOrFail($id);
        return view('admin.landing_page_management.intro_sliders.edit' , ['slider' => $slider]);
    }

    public function update(UpdateRequest $request, $id)
    {
        IntroSlider::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('تعديل صورة في قسم البنرات الخاص بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introsliders.index')]);
    }

    public function show($id)
    {
        $slider = IntroSlider::findOrFail($id);
        return view('admin.landing_page_management.intro_sliders.show' , ['slider' => $slider]);
    }

    public function destroy($id)
    {
        IntroSlider::findOrFail($id)->delete();
        ReportTrait::addToLog('حذف صورة من قسم البنرات الخاص بالموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroSlider::whereIn('id' , $ids)->get()->each->delete($ids)) {
            ReportTrait::addToLog('حذف مجموعه من الصور في قسم البنرات الخاص بالموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

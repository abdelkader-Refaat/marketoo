<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroHowWork;
use App\Http\Requests\Admin\LandingPage\IntroHowWorks\StoreRequest;
use App\Http\Requests\Admin\LandingPage\IntroHowWorks\UpdateRequest;

class IntroHowWorkController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $howWorks = IntroHowWork::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_how_works.table' ,compact('howWorks'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_how_works.index');
    }

    public function create()
    {
        return view('admin.landing_page_management.intro_how_works.create');
    }
    public function store(StoreRequest $request)
    {
        IntroHowWork::create($request->validated() ) ;
        ReportTrait::addToLog('  اضافه طريقة عمل لقسم كيفيه عمل الموقع التعريفي') ;
        return response()->json(['url' => route('admin.introhowworks.index')]);
    }
    public function edit($id)
    {
        $howWork = IntroHowWork::findOrFail($id);
        return view('admin.landing_page_management.intro_how_works.edit' , ['howWork' => $howWork]);
    }

    public function update(UpdateRequest $request, $id)
    {
        IntroHowWork::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('  تعديل طريقة عمل لقسم كيفيه عمل الموقع التعريفي') ;
        return response()->json(['url' => route('admin.introhowworks.index')]);
    }
    public function show($id)
    {
        $howWork = IntroHowWork::findOrFail($id);
        return view('admin.landing_page_management.intro_how_works.show' , ['howWork' => $howWork]);
    }

    public function destroy($id)
    {
        IntroHowWork::findOrFail($id)->delete();
        ReportTrait::addToLog('  حذف طريقة عمل لقسم كيفيه عمل الموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroHowWork::whereIn('id' , $ids)->get()->each->delete($ids)) {
            ReportTrait::addToLog('  حذف العديد من طرق العمل لقسم كيفيه عمل الموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

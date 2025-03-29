<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroFqs;
use App\Models\LandingPage\IntroFqsCategory;
use App\Http\Requests\Admin\LandingPage\IntroFqs\StoreRequest;

class IntroFqsController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $fqss = IntroFqs::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_fqs.table' ,compact('fqss'))->render() ;
            return response()->json(['html' => $html]);
        }
        $categories = IntroFqsCategory::get() ;
        return view('admin.landing_page_management.intro_fqs.index' , compact('categories'));
    }
    public function create()
    {
        $categories = IntroFqsCategory::get() ;
        return view('admin.landing_page_management.intro_fqs.create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        IntroFqs::create($request->validated() ) ;
        ReportTrait::addToLog('  اضافه سؤال شائع الخاص بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introfqs.index')]);
    }

    public function edit($id)
    {
        $fqs = IntroFqs::findOrFail($id);
        $categories = IntroFqsCategory::get() ;

        return view('admin.landing_page_management.intro_fqs.edit' , ['fqs' => $fqs , 'categories' => $categories]);
    }
    public function update(StoreRequest $request, $id)
    {
        IntroFqs::findOrFail($id)->update($request->validated() ) ;
        ReportTrait::addToLog('  تعديل سؤال شائع الخاص بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introfqs.index')]);
    }

    public function show($id)
    {
        $fqs = IntroFqs::findOrFail($id);
        $categories = IntroFqsCategory::get() ;
        return view('admin.landing_page_management.intro_fqs.show' , ['fqs' => $fqs , 'categories' => $categories]);
    }

    public function destroy($id)
    {
        IntroFqs::findOrFail($id)->delete();
        ReportTrait::addToLog('  حذف سؤال شائع الخاص بالموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroFqs::whereIn('id' , $ids)->get()->each->delete()) {
            ReportTrait::addToLog('  حذف العديد من الاسئلة الشائعة الخاصة بالموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

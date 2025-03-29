<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroFqsCategory;
use App\Http\Requests\Admin\LandingPage\IntroFqsCategories\StoreRequest;

class IntroFqsCategoryController extends Controller
{
    public function index($id = null)
    {
        if (request()->ajax()) {
            $categories = IntroFqsCategory::search(request()->searchArray)->paginate(30);
            $html = view('admin.landing_page_management.intro_fqs_categories.table' ,compact('categories'))->render() ;
            return response()->json(['html' => $html]);
        }
        return view('admin.landing_page_management.intro_fqs_categories.index');
    }

    public function create()
    {
        return view('admin.landing_page_management.intro_fqs_categories.create');
    }
    public function store(StoreRequest $request)
    {
        IntroFqsCategory::create($request->validated()) ;
        ReportTrait::addToLog('  اضافه قسم للاسئلة الشائعه الخاصه بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introfqscategories.index')]);
    }

    public function edit($id)
    {
        $category = IntroFqsCategory::findOrFail($id);
        return view('admin.landing_page_management.intro_fqs_categories.edit' , ['category' => $category]);
    }

    public function update(StoreRequest $request, $id)
    {
        IntroFqsCategory::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('  تعديل قسم للاسئلة الشائعه الخاصه بالموقع التعريفي') ;
        return response()->json(['url' => route('admin.introfqscategories.index')]);
    }
    public function show($id)
    {
        $category = IntroFqsCategory::findOrFail($id);
        return view('admin.landing_page_management.intro_fqs_categories.show' , ['category' => $category]);
    }

    public function destroy($id)
    {
        IntroFqsCategory::findOrFail($id)->delete();
        ReportTrait::addToLog('  حذف قسم للاسئلة الشائعه الخاصه بالموقع التعريفي') ;
        return response()->json(['id' =>$id]);
    }

    public function destroyAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (IntroFqsCategory::whereIn('id' , $ids)->delete()) {
            ReportTrait::addToLog('  حذف العديد من الاقسام للاسئلة الشائعه الخاصه بالموقع التعريفي') ;
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}

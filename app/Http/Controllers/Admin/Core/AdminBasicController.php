<?php

namespace App\Http\Controllers\Admin\Core;

use App\Traits\ReportTrait;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBasicController extends Controller
{

    public function __construct(
        protected $model,
        protected $storeRequest,
        protected $updateRequest,
        protected string $directoryName,
        protected object $serviceName,
        protected string $indexScopes = '',
        protected array $indexConditions = [],
        protected array $indexCompactVariables = [],
        protected array $createCompactVariables = [],
        protected array $editCompactVariables = [],
        protected array $showCompactVariables = [],
        protected array $destroyOneConditions = [],
        protected array $destroyRelationsToCheck = []
    ) {}

    protected function modelName(): string
    {
        return Str::replace('app\models\\', '', strtolower($this->model));
    }
    protected function getClassNameTranslated(): string
    {
        return __('admin.' . $this->modelName());
    }

    protected function pluralModelName()
    {
        return Str::plural($this->modelName());
    }
    public function index()
    {
        if (request()->ajax()) {
            $rows = $this->serviceName->limit(paginateNum: 30, scopes: $this->indexScopes, conditions: $this->indexConditions??[]);
            $html = view('admin.' . $this->directoryName . '.table', compact('rows'))->render();
            return response()->json(['html' => $html]);
        }
        return view('admin.' . $this->directoryName . '.index', $this->indexCompactVariables ?? []);
    }

    public function create()
    {
        return view('admin.' . $this->directoryName . '.create', $this->createCompactVariables ?? []);
    }

    public function store()
    {
        $this->storeRequest = app($this->storeRequest);

        $this->serviceName->create($this->storeRequest->validated());
        ReportTrait::addToLog('  اضافه ' . $this->getClassNameTranslated());

        return response()->json(['url' => route($this->currentRouteName() . '.index')]);
    }

    public function update($id)
    {
        $this->updateRequest = app($this->updateRequest);

        $this->serviceName->update(data: $this->updateRequest->validated(), id: $id);
        ReportTrait::addToLog('  تعديل بلد');

        return response()->json(['url' => route($this->currentRouteName() . '.index')]);
    }

    public function edit($id)
    {
        $row = $this->serviceName->find($id);
        return view('admin.' . $this->directoryName . '.edit', array_merge(['row' => $row], $this->editCompactVariables ?? []));
    }

    public function show($id)
    {
        $row = $this->serviceName->find(id: $id);
        return view('admin.' . $this->directoryName . '.show', array_merge(['row' => $row], $this->showCompactVariables ?? []));
    }

    public function destroy($id)
    {
        $result = $this->serviceName->delete(id: $id, relationsToCheck: $this->destroyRelationsToCheck ?? [], conditions: $this->destroyOneConditions ?? []);
        ReportTrait::addToLog('  حذف ' . $this->getClassNameTranslated());
        return response()->json(['key' =>  $result['key'], 'msg' => $result['msg']]);
    }

    public function destroyAll(Request $request)
    {
        $result = $this->serviceName->deleteMultiple(request: $request, relationsToCheck: $this->destroyRelationsToCheck ?? []);
        ReportTrait::addToLog('  حذف العديد من  ' . $this->getClassNameTranslated());
        return response()->json(['key' =>  $result['key'], 'msg' => $result['msg']]);
    }

    protected function currentRouteName()
    {
        $currentRouteName = request()->route()->getName();
        return substr($currentRouteName, 0, strrpos($currentRouteName, '.'));
    }
}

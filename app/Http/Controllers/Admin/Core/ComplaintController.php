<?php

namespace App\Http\Controllers\Admin\Core;

use Illuminate\Http\Request;
use App\Services\PublicSections\ComplaintService;
use App\Models\PublicSections\Complaint;

class ComplaintController extends AdminBasicController
{

    public function __construct()
    {
        // parent constructor parameters
        $this->model = Complaint::class;
        $this->directoryName = 'public-sections.complaints';
        $this->serviceName = new ComplaintService();
        $this->indexScopes = 'search';
    }

    public function replay($id, Request $request)
    {
        $request->validate(['replay' => 'required']);
        $this->serviceName->replay($id, $request->all());
        return response()->json(['url' => route('admin.complaints.show', ['id' => $id])]);
    }
}

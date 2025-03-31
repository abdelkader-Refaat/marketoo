<?php

namespace App\Http\Controllers\Admin\AllUsers;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\PublicSettings\Role;
use App\Services\AllUsers\AdminService;
use App\Services\CountryCities\CountryService;
use App\Services\Core\NotificationService;
use App\Http\Requests\Admin\AllUsers\Admin\StoreRequest;
use App\Http\Requests\Admin\AllUsers\Admin\UpdateRequest;
use Modules\Admins\App\Models\Admin;

class AdminController extends AdminBasicController
{

    use ResponseTrait, ReportTrait;

    protected $countryService, $adminService;

    public function __construct()
    {
        $this->countryService = new CountryService();
        // parent constructor parameters
        $this->model = Admin::class;
        $this->storeRequest = StoreRequest::class;
        $this->updateRequest = UpdateRequest::class;
        $this->directoryName = 'admins';
        $this->serviceName = new AdminService();
        $this->indexScopes = 'search';
        $this->indexConditions = ['type' => 'admin'];
        $this->createCompactVariables = [
            'roles' => Role::latest()->get(),
            'countries' => $this->countryService->all(),
        ];
        $this->editCompactVariables = [
            'roles' => Role::latest()->get(),
            'countries' => $this->countryService->all(),
        ];
        $this->destroyOneConditions = [['id', '!=', 1]];
    }

    public function block(Request $request)
    {
        $response = $this->serviceName->toggleBlock(id: $request->id);
        return response()->json(['message' => $response['msg']]);
    }

    public function notifications(NotificationService $notificationService)
    {
        $notificationService->markAsReadNotifications(auth('admin')->user());
        return view('admin.admins.notifications');
    }

    public function deleteNotifications(Request $request, NotificationService $notificationService)
    {
        $data = $notificationService->deleteSelected(user: auth('admin')->user(), request: $request);
        return $this->successMsg($data['msg']);
    }
}

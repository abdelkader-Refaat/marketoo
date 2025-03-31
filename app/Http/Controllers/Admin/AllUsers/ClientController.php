<?php

namespace App\Http\Controllers\Admin\AllUsers;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Services\AllUsers\ClientService;
use App\Services\Core\NotificationService;
use App\Services\CountryCities\CityService;
use App\Services\CountryCities\CountryService;
use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Http\Requests\Admin\AllUsers\Client\StoreRequest;
use App\Http\Requests\Admin\AllUsers\Client\UpdateRequest;
use App\Http\Requests\Admin\Core\Notification\SendRequest;
use App\Http\Requests\Admin\Core\Wallet\UpdateBalanceRequest;
use Modules\Users\App\Models\User;

class ClientController extends AdminBasicController
{

    protected $countryService;
    protected $cityService;

    public function __construct()
    {
        $this->countryService = new CountryService();
        $this->cityService = new CityService();
        // parent constructor parameters
        $this->model = User::class;
        $this->storeRequest = StoreRequest::class;
        $this->updateRequest = UpdateRequest::class;
        $this->directoryName = 'clients';
        $this->serviceName = new ClientService();
        $this->indexScopes = 'search';
        $this->createCompactVariables = [
            'countries' => $this->countryService->all(withCount: ['cities']),
        ];
        $this->editCompactVariables = [
            'countries' => $this->countryService->all(withCount: ['cities']),
        ];
        $this->destroyOneConditions = [['id', '!=', 1]];
    }

    public function block(Request $request)
    {
        $data = $this->serviceName->toggleBlock($request->id);
        return response()->json(['message' => $data['msg']]);
    }


    public function notify(SendRequest $request, NotificationService $notificationService)
    {
        $notificationService->send($request);
        return response()->json();
    }

    public function updateBalance(UpdateBalanceRequest $request, $id)
    {
        $data = $this->serviceName->updateBalance(type: $request->type, id: $id, balance: $request->balance);
        return response()->json(['msg' => $data['msg'], 'balance' => $data['balance'].' '.__('site.currency')]);
    }

    public function show($id)
    {
        $row = $this->serviceName->find($id);

        if (request()->ajax()) {
            $data = $this->serviceName->details(user: $row);
            return response()->json(['html' => $data['html']]);
        }

        return view('admin.clients.show', ['row' => $row]);
    }
}

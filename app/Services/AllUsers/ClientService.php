<?php

namespace App\Services\AllUsers;


use App\Models\PublicSections\Complaint;
use App\Models\Wallet\WalletTransaction;
use App\Services\Core\BaseService;
use Modules\Users\App\Models\User;

class ClientService extends BaseService
{
    public function __construct()
    {
        $this->model = User::class;
    }

    public function details($user)
    {
        if (request()->type == 'main_data') {
            $html = view('admin.clients.parts.main_data', ['row' => $user])->render();
        }

        if (request()->type == 'complaints') {
            $complaints = Complaint::where([
                'complaintable_id' => $user->id, 'complaintable_type' => get_class($user)
            ])->paginate(10);
            $html = view('admin.clients.parts.complaints', compact('complaints'))->render();
        }
        if (request()->type == 'wallet') {
            $transactions = WalletTransaction::where('wallet_id', $user->wallet->id)->paginate(10);
            $html = view('admin.clients.parts.transactions', compact('transactions'))->render();
        }
        if (request()->type == 'orders') {
            $orders = $user->orders ?? collect([]);
            $html = view('admin.clients.parts.orders', compact('orders'))->render();
        }

        return ['html' => $html];
    }

    public function findOrNew($data = [])
    {
        $user = User::query()->firstOrCreate($data, $data);
        return ['key' => 'success', 'msg' => 'success', 'data' => $user];
    }

    public function isRegistered($user): bool
    {
        return !isset($user->name, $user->email, $user->city_id, $user->country_id);
    }
}

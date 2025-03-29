<?php

namespace App\Http\Controllers\Admin\Core;


use App\Http\Controllers\Controller;
use App\Services\Core\NotificationService;
use App\Http\Requests\Admin\Core\Notification\SendRequest;

class NotificationController extends Controller
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return view('admin.notifications.index');
    }

    public function sendNotifications(SendRequest $request)
    {
        $this->notificationService->send($request);
        return response()->json();
    }
}

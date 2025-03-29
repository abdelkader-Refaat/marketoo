<?php

namespace App\Http\Controllers\Api\General;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Core\NotificationService;
use App\Http\Resources\Api\General\Notifications\NotificationsCollection;

class NotificationController extends Controller
{
    use ResponseTrait;

    public function __construct(private NotificationService $notificationService)
    {
    }

    public function switchNotificationStatus(): JsonResponse
    {
        $data = $this->notificationService->switchNotificationStatus(user: auth()->user());
        return $this->response($data['key'], $data['msg'], $data['data']);
    }

    public function getNotifications(): JsonResponse
    {
        $this->notificationService->markAsReadNotifications(user: auth()->user());
        $notifications = $this->notificationService->all(user: auth()->user(), paginateNum: $this->paginateNum());

        return $this->successData(['notifications' => new NotificationsCollection($notifications['notifications'])]);
    }

    public function countUnreadNotifications(): JsonResponse
    {
        $data = $this->notificationService->unreadNotificationsCount(user: auth()->user());

        return $this->successData(['count' => $data['count']]);
    }

    public function deleteNotification($notification_id): JsonResponse
    {
        $data = $this->notificationService->deleteOne(user: auth()->user(), id: $notification_id);
        return $this->successMsg($data['msg']);
    }

    public function deleteNotifications(): JsonResponse
    {
        $data = $this->notificationService->deleteAll(user: auth()->user());
        return $this->successMsg($data['msg']);
    }
}

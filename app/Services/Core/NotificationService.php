<?php

namespace App\Services\Core;

use App\Jobs\Notify;
use App\Jobs\SendSms;
use App\Jobs\AdminNotify;
use App\Jobs\SendEmailJob;
use App\Models\AllUsers\User;
use App\Models\AllUsers\Admin;


class NotificationService
{

    public function send($request): array
    {

        if ($request->user_type === 'all') {
            $this->sendNotificationToAll($request);
        } else {
            $rows = $this->getRows($request->user_type, $request->id);
            match ($request->type) {
                'notify' => $this->sendNotification($rows, $request->user_type, $request),
                'email' => $this->sendMail($rows, $request),
                'sms' => $this->sendSms($rows->pluck('phone')->toArray(), $request->message),
                default => $this->sendNotification($rows, $request->user_type, $request),
            };
        }
        return ['key' => 'success', 'msg' => __('apis.success')];
    }

    protected function sendNotification($rows, $type, $request): void
    {
        $job = $type === 'admins' ? new AdminNotify($rows, $request) : new Notify($rows, $request);
        dispatch($job);
    }

    protected function sendMail($rows, $request): void
    {
        dispatch(new SendEmailJob($rows->pluck('email'), $request));
    }

    protected function sendSms($phones, $message): void
    {
        dispatch(new SendSms($phones, $message));
    }

    protected function getRows($type, $id = null)
    {
        return match ($type) {
            'users' => $id ? User::findOrFail($id) : User::all(),
            'admins' => Admin::all(),
            default => collect(), // Return an empty collection if the $type doesn't match any case
        };
    }

    protected function sendNotificationToAll($request): void
    {
        dispatch(new Notify(User::all(), $request));
        dispatch(new AdminNotify(Admin::all(), $request));
    }

    public function all($user, $paginateNum = 10): array
    {
        $notifications = $user->notifications()->paginate($paginateNum);
        return ['key' => 'success', 'notifications' => $notifications, 'msg' => __('apis.success')];
    }

    public function markAsReadNotifications($user): array
    {
        $notifications = $user->unreadNotifications->markAsRead();
        return ['key' => 'success', 'notifications' => $notifications, 'msg' => __('apis.success')];
    }

    public function unreadNotificationsCount($user): array
    {
        $notificationsCount = $user->unreadNotifications->count();
        return ['key' => 'success', 'count' => $notificationsCount, 'msg' => __('apis.success')];
    }

    public function deleteSelected($user, $request): array
    {
        $requestIds = array_column(json_decode($request->data), 'id');
        $user->notifications()->whereIn('id', $requestIds)->delete();
        return ['msg' => __('apis.deleted'), 'key' => 'success'];
    }

    public function deleteAll($user): array
    {
        $user->notifications()->delete();
        return ['msg' => __('apis.deleted'), 'key' => 'success'];
    }

    public function switchNotificationStatus($user): array
    {
        $user->update(['is_notify' => !$user->is_notify]);
        return ['key' => 'success', 'msg' => __('apis.updated'), 'data' => $user->refresh()->is_notify];
    }

    public function deleteOne($user, $id): array
    {
        $user->notifications()->whereId($id)->delete();
        return ['msg' => __('apis.deleted'), 'key' => 'success'];
    }
}

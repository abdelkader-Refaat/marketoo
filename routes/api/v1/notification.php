<?php

use app\Http\Controllers\Api\V1\General\NotificationController;
use Illuminate\Support\Facades\Route;

// notifications
Route::controller(NotificationController::class)->group(function () {
    Route::patch('switch-notify', 'switchNotificationStatus');
    Route::get('notifications', 'getNotifications');
    Route::get('count-notifications', 'countUnreadNotifications');
    Route::delete('delete-notification/{notification_id}', 'deleteNotification');
    Route::delete('delete-notifications', 'deleteNotifications');
});
// notifications

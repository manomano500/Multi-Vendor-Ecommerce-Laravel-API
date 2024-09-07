<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use http\Env\Request;

class NotificationController extends Controller
{
    public function index()
    {

        // Fetch all notifications for the user

        // Mark all unread notifications as read
        $notifications= auth()->user()->notifications->take(20);
        return NotificationResource::collection($notifications);
//        return new NotificationResource($notification);
    }




}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index()
    {
        $notification= auth()->user()->notifications;
        return NotificationResource::collection($notification);
        return new NotificationResource($notification);
    }

}

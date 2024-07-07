<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notification= auth()->user()->notifications;
        return NotificationResource::collection($notification);
        return new NotificationResource($notification);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminOrderResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {

        $orders=  Order::with('orderProducts','user')->get();

        return AdminOrderResource::collection($orders);
    }
    //
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {

        $orders=  Order::with('orderProducts')->get();

        return OrderResource::collection($orders);
    }
    //
}

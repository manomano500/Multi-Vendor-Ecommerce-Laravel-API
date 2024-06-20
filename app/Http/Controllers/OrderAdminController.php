<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index()
    {

        $orders=  Order::paginate(10);

        return $orders;
    }


    public function show($id)
    {
        $order = Order::with('orderProducts','user')->find($id);
        return $order;
    }
    //
}

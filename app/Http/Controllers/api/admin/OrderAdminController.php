<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

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

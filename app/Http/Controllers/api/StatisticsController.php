<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function adminStatistic()
    {
        try {
            // Get the total number of users
            $totalUsers = User::count();

            // Get the total number of stores
            $totalStores = Store::count();

            // Get the total number of products
            $totalProducts = Product::count();

            // Get the total number of orders
            $totalOrders = Order::count();

            // Get total sales amount

            $totalSales = DB::table('order_product')
                ->select(DB::raw('SUM(quantity * price) as total_sales'))
                ->pluck('total_sales')
                ->first();

            // Get the number of new users in the last 30 days
            $newUsersLast30Days = User::where('created_at', '>=', now()->subDays(30))->count();

            // Get the number of new stores in the last 30 days
            $newStoresLast30Days = Store::where('created_at', '>=', now()->subDays(30))->count();

            // Get the number of new products in the last 30 days
            $newProductsLast30Days = Product::where('created_at', '>=', now()->subDays(30))->count();

            // Get the number of new orders in the last 30 days
            $newOrdersLast30Days = Order::where('created_at', '>=', now()->subDays(30))->count();

            // Prepare the statistics data
            $statistics = [
                'total_users' => $totalUsers,
                'total_stores' => $totalStores,
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_sales' => $totalSales,
                'new_users_last_30_days' => $newUsersLast30Days,
                'new_stores_last_30_days' => $newStoresLast30Days,
                'new_products_last_30_days' => $newProductsLast30Days,
                'new_orders_last_30_days' => $newOrdersLast30Days,
            ];

            return response()->json(['data' => $statistics], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching statistics', 'error' => $e->getMessage()], 500);
        }
    }
    public function vendorStatistic()
    {

        $user = Auth::user();
        $store = $user->store;
        $storeId = $store->id;

        if (!$store) {
            return response()->json(['message' => 'No store found for the user'], 404);
        }

        // Gather statistics
        $totalProducts = Product::where('store_id', $store->id)?->count();
        $lowStockProducts = Product::where('store_id', $store->id)->where('quantity', '<', 10)->count();
        $orders = Order::withStoreProducts($store->id)->count();
        $totalSales = Order::whereHas('products', function ($query) use ($storeId) {
            $query->whereHas('store', function ($query) use ($storeId) {
                $query->where('id', $storeId);
            });
        })
            ->where('status', '!=', 'completed')
            ->with('products')
            ->get()
            ->sum(function ($order) {
                return $order->products->sum(function ($product) {
                    return $product->pivot->price * $product->pivot->quantity;
                });
            });

        $balance = Order::withStoreProducts($store->id)
            ->where('status', 'delivered')
            ->sum('order_total');

//        $totalOrders = Order::where('store_id', $store->id)?->count();



        // Return statistics
        return response()->json([
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'orders' => $orders,
'totalSales'=>$totalSales,
'balance'=>$balance
//            'total_orders' => $totalOrders,
//            'total_sales' => $totalSales,,
//            'total_revenue' => $totalRevenue,
        ]);

    }
}

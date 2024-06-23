<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $totalSales = Order::sum('order_total');

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

        if (!$store) {
            return response()->json(['message' => 'No store found for the user'], 404);
        }

        // Gather statistics
        $totalProducts = Product::where('store_id', $store->id)?->count();
        $totalOrders = Order::where('store_id', $store->id)?->count();
        $totalSales = Order::where('store_id', $store->id)?->sum('order_total');
//        $totalRevenue = Order::where('store_id', $store->id)->sum('total_amount'); // Assuming total_amount includes revenue

        // Return statistics
        return response()->json([
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'total_sales' => $totalSales,
//            'total_revenue' => $totalRevenue,
        ]);

    }
}

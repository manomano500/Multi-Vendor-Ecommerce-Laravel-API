<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function getAllProducts()
    {
        $user = Auth::user();

        if ($user->role === 'vendor') {

            return $user->products;
        } elseif ($user->role === 'admin') {
            // Return all products for admin
            return Product::all();
        } else {
            // Return products based on other roles if needed
            return Product::where('is_public', true)->get();
        }
    }

    public function getProductById($id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role === 'vendor' && $product->user_id !== $user->id) {
            abort(403, 'Unauthorized access to product');
        }

        return $product;
    }

    public function createProduct($data)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            abort(403, 'Only vendors can create products');
        }

        $data['user_id'] = $user->id;

        return Product::create($data);
    }

    public function updateProduct($id, $data)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role === 'vendor' && $product->user_id !== $user->id) {
            abort(403, 'Unauthorized access to product');
        }

        $product->update($data);

        return $product;
    }

    public function deleteProduct($id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role === 'vendor' && $product->user_id !== $user->id) {
            abort(403, 'Unauthorized access to product');
        }

        $product->delete();

        return true;
    }
}

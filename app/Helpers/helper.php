<?php
namespace App\Helpers;

use App\Models\Product;
use Exception;

class ProductHelper
{
    public static function deductProductQuantities(Product $product, $quantity): void
    {
        if ($product->quantity < $quantity) {
            throw new Exception('Insufficient quantity for product ' . $product->name);
        }

        $remainingQuantity = $product->quantity - $quantity;
        $product->update(['quantity' => $remainingQuantity]);

        if ($remainingQuantity < 2) {
            $product->update(['status' => 'out_of_stock']);
        }
    }
}

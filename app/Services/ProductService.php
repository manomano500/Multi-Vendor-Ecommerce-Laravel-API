<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductService
{
    public function getAllProducts()
    {
        $user = Auth::user();


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


    }

    public function updateProduct($id, $data)
    {
        Log::info($data);
        Log::info($id);
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role === 'vendor' && $product->user_id !== $user->id) {
            abort(403, 'Unauthorized access to product');
        }

        try {
            $updatedFields = $data->only([
                'name',
                'description',
                'quantity',
                'category_id',
                'price',
                'status'
            ]);

            if($data->hasFile('images')) {
                $images = [];
                foreach ($data->file('images') as $image) {
                    // Create a meaningful filename
                    $filename = Str::slug($data['name']) . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('images/products', $filename, 'public');
                    $images[] = [
                        'product_id' => $product->id,
                        'image' => $path,
                    ];
                }

                // Batch insert images
                ProductImage::insert($images);
            }
            if($data->has('deleted_images')) {

                $deletedImageIds = $data->input('deleted_images');
                $product->deleteImages($deletedImageIds);
            }

            // Update only the fields that the user has edited
            $product->update($updatedFields);

            // Sync variations
            $product->variations()->sync($data->input('variations'));

            return $product;
        } catch (Exception $e) {
throw new Exception('Failed to update product Error: '.$e->getMessage());
        }
        return $product;
    }

    public function deleteProduct($id)
    {
        $user = Auth::user();


    }
}

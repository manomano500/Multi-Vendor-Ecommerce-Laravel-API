<?php
namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get all image files from the storage directory
        $files = Storage::files('public/images/products');

        // Ensure there is at least one image file
        if (count($files) > 0) {
            // Pick a random file by generating a random index
            $randomFile = $files[array_rand($files)];
            $relativePath = str_replace('public/', '', $randomFile);

            Log::info($randomFile);

            return [
                'image' => $relativePath, // Store the relative path
            ];
        }

        // Fallback if no images are found
        return [
            'image' => null, // or some default placeholder image
        ];
    }
}

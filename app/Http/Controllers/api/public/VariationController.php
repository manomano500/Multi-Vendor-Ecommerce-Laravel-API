<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\VariationResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function index()
    {


        return Attribute::with('variations')->get();


    }
    // In your Laravel Controller
    public function getAllVariations()
    {
        // Fetch all unique variation attributes and their values across all products
        $variations = Variation::with('attribute')
            ->get();
        return response()->json(['variations' => $variations]);
    }

    public function filterProducts(Request $request)
    {
        $query = Product::query();

        // Apply variation filters
        if ($request->has('variations')) {
            foreach ($request->input('variations') as $attributeName => $values) {
                $query->whereHas('variations', function ($q) use ($attributeName, $values) {
                    $q->whereHas('attribute', function ($q) use ($attributeName) {
                        $q->where('name', $attributeName);
                    })->whereIn('value', (array)$values);
                });
            }
        }

        // Additional filters (category, price, etc.) can be applied here

        $products = $query->get();

        return response()->json(['products' => $products]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new VariationResource(Variation::create($data));
    }

    public function show($value)
    {
        $variation = Variation::find($value);
        return new VariationResource($variation);
    }

    public function update(Request $request, Variation $value)
    {
        $data = $request->validate([

        ]);

        $value->update($data);

        return new VariationResource($value);
    }

    public function destroy(Variation $value)
    {
        $value->delete();

        return response()->json();
    }




}

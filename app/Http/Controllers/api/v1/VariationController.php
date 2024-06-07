<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\ProductVendorSingleResource;
use App\Http\Resources\VariationResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VariationController extends Controller
{
    public function index()
    {


        return Attribute::with('variations')->get();


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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\VariationResource;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValueController extends Controller
{
    public function index()
    {
       $values = Variation::with('attribute')->get();
       Log::info($values);
        return $values;
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new VariationResource(Variation::create($data));
    }

    public function show(Variation $value)
    {
        return new VariationResource($value);
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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeValuesViewResource;
use App\Models\AttributeValuesView;
use Illuminate\Http\Request;

class AttributeValuesViewController extends Controller
{
    public function index()
    {
        return AttributeValuesViewResource::collection(AttributeValuesView::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new AttributeValuesViewResource(AttributeValuesView::create($data));
    }

    public function show(AttributeValuesView $attributeValuesView)
    {
        return new AttributeValuesViewResource($attributeValuesView);
    }

    public function update(Request $request, AttributeValuesView $attributeValuesView)
    {
        $data = $request->validate([

        ]);

        $attributeValuesView->update($data);

        return new AttributeValuesViewResource($attributeValuesView);
    }

    public function destroy(AttributeValuesView $attributeValuesView)
    {
        $attributeValuesView->delete();

        return response()->json();
    }
}

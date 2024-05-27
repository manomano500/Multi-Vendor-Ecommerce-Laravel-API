<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValuesRequest;
use App\Http\Resources\ValuesResource;
use App\Models\Value;

class ValuesController extends Controller
{
    public function index()
    {
        return ValuesResource::collection(Value::all());
    }

    public function store(ValuesRequest $request)
    {
        return new ValuesResource(Value::create($request->validated()));
    }

    public function show(Value $values)
    {
        return new ValuesResource($values);
    }

    public function update(ValuesRequest $request, Value $values)
    {
        $values->update($request->validated());

        return new ValuesResource($values);
    }

    public function destroy(Value $values)
    {
        $values->delete();

        return response()->json();
    }
}

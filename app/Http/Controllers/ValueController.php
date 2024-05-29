<?php

namespace App\Http\Controllers;

use App\Http\Resources\ValueResource;
use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValueController extends Controller
{
    public function index()
    {
       $values = Value::with('attribute')->get();
       Log::info($values);
        return $values;
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new ValueResource(Value::create($data));
    }

    public function show(Value $value)
    {
        return new ValueResource($value);
    }

    public function update(Request $request, Value $value)
    {
        $data = $request->validate([

        ]);

        $value->update($data);

        return new ValueResource($value);
    }

    public function destroy(Value $value)
    {
        $value->delete();

        return response()->json();
    }
}

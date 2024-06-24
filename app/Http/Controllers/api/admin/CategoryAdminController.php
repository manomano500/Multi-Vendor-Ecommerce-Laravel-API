<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);

        return $categories;

    }

    public function update(Request $request,$id)
    {
        $categoryRequest=CategoryRequest::createFrom($request);
        $validated = \Validator::make($request->all(), $categoryRequest->rules());
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        }

        $category =Category::findOrFail($id);

        $category->update(request()->all());

    }

}

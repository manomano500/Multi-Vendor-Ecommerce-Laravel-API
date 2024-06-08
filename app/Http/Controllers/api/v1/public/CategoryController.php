<?php

namespace App\Http\Controllers\api\v1\public;

use App\Http\Controllers\Controller;

use App\Http\Resources\Categories\CategoryParentChildrenResource;
use App\Http\Resources\Categories\CategoryParentResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('category_id')->get(['id','name']);
        Log::info($categories);
        return  $categories;


    }

    public function indexWhitChildren()
    {
        $categories = Category::with('children')->get();
        Log::info($categories);
        return  CategoryParentChildrenResource::collection($categories);

    }

public function show($id){
       $categories = Category::with('products')->find($id);
        return new CategoryParentResource($categories);

}


   public function addParentCategory(Request $request)
   {
       $validated = Validator::make($request->all(), [
           'name' => 'required|string|max:255,unique:categories',
           'children' => 'array',
              'children.*.name' => 'required|string|max:255'
       ]);
       if($validated->fails()){
           return response()->json($validated->errors(), 400);
       }
       try {
           $category =Category::create($request->only('name')); ;

              foreach ($request->children as $child){
                  $category->children()->create($child);
              }
              return new CategoryParentChildrenResource($category);
       } catch (\Exception $e) {
           return response()->json(['message' => $e->getMessage()], 400);
       }

   }









}

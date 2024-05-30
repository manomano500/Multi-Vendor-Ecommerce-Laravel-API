<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Categories\CategoryCollection;
use App\Http\Resources\Categories\CategoryParentChildrenResource;
use App\Http\Resources\Categories\CategoryParentResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->get();
        Log::info($categories);
        return  CategoryParentChildrenResource::collection($categories);
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


   public function getChildrenCategories(Category $category){
         return CategoryParentChildrenResource::collection($category->children);
   }




    public function getParentCategories()
    {
        $categories = Category::get(['id','name']);
        return CategoryParentResource::collection($categories);


    }


}

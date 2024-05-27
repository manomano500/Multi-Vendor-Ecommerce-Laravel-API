<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

   public function addParentCategory(Request $request)
   {
       $validated = Validator::make($request->all(), [
           'name' => 'required|string|max:255,unique:categories'
       ]);
       $category = new Category();
       $category->name = $request->name;
       $category->save();
       return response()->json($category);
   }





    public function getParentCategories()
    {
        return Category::parents();
    }
}

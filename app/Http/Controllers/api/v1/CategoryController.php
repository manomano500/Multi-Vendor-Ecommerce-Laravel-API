<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoryParentChildrenResource;
use App\Http\Resources\Categories\CategoryParentResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    /**
* public
     */
    public function index()
    {
        $categories = Category::parent()->whereHas('stores')->get(['id','name']);
        Log::info($categories);
        return  $categories;


    }

    /**
     *public
     */

    public function show($id)
    {
        $category = Category::find($id)
//
            ->load('stores');
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }

        return CategoryParentResource::make($category);
    }



    public function indexWhitChildren()
    {
        $categories = Category::with('children')->get();
        Log::info($categories);
        return  CategoryParentChildrenResource::collection($categories);

    }

    /**
     * admin
     */

   public function store(Request $request)
   {
       $this->authorize('create', Category::class);

       $validated = Validator::make($request->all(), [
           'name' => 'required|string|max:255,unique:categories',
           'children' => 'array',
           'children.*.name' => 'required|string|max:255'
       ]);
       if ($validated->fails()) {
           return response()->json($validated->errors(), 400);
       }
       try {
           $category = Category::create($request->only('name'));;

//              foreach ($request->children as $child){
           $category->children()->createMany($request->children);
//              }
           return Response()->json(['message' => 'Category created','data'=>$category], 201);
       } catch (\Exception $e) {
           return response()->json(['message' => $e->getMessage()], 400);
       }
   }



       public function update(Request $request, $id)
       {
           $category = Category::findOrFail($id);
           if(!$category){
               return response()->json(['message' => 'Category not found'], 404);
           }
           $validated = Validator::make($request->all(), [
               'name' => 'sometimes|required|string|max:255,unique:categories',
               'children' => 'sometimes|array',
               'children.*.name' => 'sometimes|required|string|max:255'
           ]);
           if($validated->fails()){
               return response()->json($validated->errors(), 400);
           }
           try {
               $category->update($request->only('name'));
               $category->children()->updateOrInsert($request->children);
              return Response()->json(['message' => 'Category updated'], 200);
           } catch (\Exception $e) {
               return response()->json(['message' => $e->getMessage()], 400);
           }
       }











}

<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoryParentChildrenResource;
use App\Http\Resources\Categories\CategoryParentResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{



   /* public function index()
    {
        // Cache the categories for 60 minutes
        $categories = Cache::remember('categories_with_stores', 60, function () {
            return Category::parent()->whereHas('stores')->get(['id', 'name']);
        });

        // Log the categories
        Log::info($categories);

        return $categories;
    }*/

    public function index()
    {
        // Cache the categories for 60 minutes
        $categories = Cache::remember('categories_with_stores', 60, function () {
            return Category::
          whereHas('stores')
                ->get(['id','name'])
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->getTranslation('name', app()->getLocale())
                    ];
                });
        });
Log::info($categories);

        return response()->json($categories); // Return as JSON if this is an API endpoint
    }



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

















}

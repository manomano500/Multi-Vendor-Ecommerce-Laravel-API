<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->getTranslation('name', app()->getLocale())
                ];
            });
        ;
        return response()->json($categories);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Ensure that the category can be added even if the parent category has subcategories
        // This logic allows nesting of categories.
        $category = Category::create($validated);
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Update the category without restrictions on parent categories having subcategories
        $category->update($validated);
        return response()->json($category);
    }
    public function show(Category $category)
    {
        return response()->json($category->load('children'));
    }


    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            // Delete all children categories recursively
            $this->deleteChildrenRecursively($category);

            // Delete the category itself
            $category->delete();

            DB::commit();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting category', 'error' => $e->getMessage()], 500);
        }
    }

    private function deleteChildrenRecursively(Category $category)
    {
        foreach ($category->children as $child) {
            $this->deleteChildrenRecursively($child);
            $child->delete();
        }
    }
}

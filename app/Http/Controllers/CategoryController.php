<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    
    /**
     * A function that returns all categories
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::select('id','name')->get();

        return response()->json($categories);
    }


    /**
     * A function that returns a specific category
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::select('id', 'name')->where('id', $id)->get();
            return response()->json($category);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Store and validate a newly created category
     * 
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        
        $category = new Category([
            'name' => $validatedData['name']
        ]);

        $category->save();

        return response()->json($category, 201);
    }

    /**
     * 
     */
    public function update(UpdateCategoryRequest  $request, $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Category not found']);
        }

        $validatedData = $request->validated();

        $category->update([
            'name' => $validatedData['name']
        ]);

        return response()->json($category);
    }

    /**
     * Delete the specified category
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted succesfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Category not found'], 401);
        } catch (QueryException $exceoption) {
            return response()->json(['error' => 'Failed to delete category'], 500);
        }
    }
}

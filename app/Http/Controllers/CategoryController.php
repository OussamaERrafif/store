<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing all categories.
     * @throws \Exception If an error occurs while fetching categories.
     */
    public function index()
    {
        try {
            $categories = Category::all(); // Use caching for better performance if necessary.
            return response()->json($categories, 200)->header('Access-Control-Allow-Origin', '*');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories.'], 500);
        }
    }

    /**
     * Store a newly created category in the database.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request containing category data.
     * @return \Illuminate\Http\JsonResponse JSON response containing the created category.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Exception If an error occurs while creating the category.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::create($validatedData);
            return response()->json($category, 201)->header('Access-Control-Allow-Origin', '*');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category.'], 500);
        }
    }


    /**
     * Store multiple categories in bulk.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request containing an array of category data.
     * @return \Illuminate\Http\JsonResponse JSON response containing the created categories.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Exception If an error occurs while creating the categories.
     */
    public function storeBulkCategories(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'categories' => 'required|array',
                'categories.*.name' => 'required|string|max:255',
            ]);

            $createdCategories = [];

            foreach ($validatedData['categories'] as $categoryData) {
                $createdCategories[] = Category::create($categoryData);
            }

            return response()->json($createdCategories, 201)->header('Access-Control-Allow-Origin', '*');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create categories in bulk.'], 500);
        }
    }


    /**
     * Display the specified category by ID.
     *
     * @param  int  $id The ID of the category.
     * @return \Illuminate\Http\JsonResponse JSON response containing the category.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the category is not found.
     * @throws \Exception If an error occurs while retrieving the category.
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category, 200)->header('Access-Control-Allow-Origin', '*');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve category.'], 500);
        }
    }

    /**
     * Update the specified category in the database.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request containing updated data.
     * @param  int  $id The ID of the category to update.
     * @return \Illuminate\Http\JsonResponse JSON response containing the updated category.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the category is not found.
     * @throws \Exception If an error occurs while updating the category.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::findOrFail($id);
            $category->update($validatedData);
            return response()->json($category, 200)->header('Access-Control-Allow-Origin', '*');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update category.'], 500);
        }
    }

    /**
     * Remove the specified category from the database.
     *
     * @param  int  $id The ID of the category to delete.
     * @return \Illuminate\Http\JsonResponse JSON response indicating the result.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the category is not found.
     * @throws \Exception If an error occurs while deleting the category.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(null, 204)->header('Access-Control-Allow-Origin', '*');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete category.'], 500);
        }
    }
}

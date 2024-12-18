<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    /**
     * Display a listing of products with their categories.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing all products with their categories.
     * @throws \Exception If an error occurs while fetching products.
     */
    public function index()
    {
        try {
            $products = Product::with('category')->get(); 
            return response()->json($products, 200)->header('Access-Control-Allow-Origin', '*');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve products.'], 500);
        }
    }

    /**
     * Store a newly created product with optional image upload.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request containing product data.
     * @return \Illuminate\Http\JsonResponse JSON response containing the created product.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Exception If an error occurs while creating the product.
     */
    public function store(Request $request)
    {
        dd($request->all());
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image',
            ]);

            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($validatedData);
            return response()->json($product, 201)->header('Access-Control-Allow-Origin', '*');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create product.'], 500);
        }
    }

    /**
     * Display the specified product with its category.
     *
     * @param  int  $id The ID of the product.
     * @return \Illuminate\Http\JsonResponse JSON response containing the product with its category.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the product is not found.
     * @throws \Exception If an error occurs while retrieving the product.
     */
    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);
            return response()->json($product, 200)->header('Access-Control-Allow-Origin', '*');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve product.'], 500);
        }
    }

    /**
     * Update the specified product with optional image update.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request containing updated product data.
     * @param  int  $id The ID of the product to update.
     * @return \Illuminate\Http\JsonResponse JSON response containing the updated product.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the product is not found.
     * @throws \Exception If an error occurs while updating the product.
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image',
                
            ]);

            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                Storage::delete('public/' . $product->image);
                $validatedData['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($validatedData);
            return response()->json($product, 200)->header('Access-Control-Allow-Origin', '*');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update product.'], 500);
        }
    }

    /**
     * Remove the specified product and its image.
     *
     * @param  int  $id The ID of the product to delete.
     * @return \Illuminate\Http\JsonResponse JSON response indicating the result.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the product is not found.
     * @throws \Exception If an error occurs while deleting the product.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $product->delete();
            return response()->json(null, 204)->header('Access-Control-Allow-Origin', '*');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete product.'], 500);
        }
    }
    
    /**
     * Store multiple products in bulk with optional image upload.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request containing an array of product data.
     * @return \Illuminate\Http\JsonResponse JSON response containing the created products.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Exception If an error occurs while creating the products.
     */
    public function storeBulk(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'products' => 'required|array',
                'products.*.name' => 'required|string|max:255',
                'products.*.description' => 'required',
                'products.*.price' => 'required|numeric|min:0',
                'products.*.category_id' => 'required|exists:categories,id',
                'products.*.image' => 'nullable|image|max:2048',
            ]);

            $createdProducts = [];

            foreach ($validatedData['products'] as $productData) {
                if (isset($productData['image'])) {
                    $productData['image'] = $productData['image']->store('products', 'public');
                }
                $createdProducts[] = Product::create($productData);
            }

            return response()->json($createdProducts, 201)->header('Access-Control-Allow-Origin', '*');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create products in bulk.'], 500);
        }
    }

}

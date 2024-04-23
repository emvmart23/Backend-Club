<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "user" => "required|unique:users",
            "name" => "required",
            "password" => "required|confirmed",
            "role_id" => "required|integer"
        ]);

        $productData = $data;
        $product = Category::create($productData);

        return response()->json([
            "Category created successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                "message" => "Category not found"
            ],404);
        }

        return response()->json([
            "category" => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "message" => "category not found"
            ],404);
        }

        $data = $request->validate([
            "name" => "sometimes|string",
            "description" => "sometimes|string",
        ]);

        $category->update($data);

        return response()->json([
            "message" => "Categoria Actualizada"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "message" => "category not found"
            ],404);
        }

        $category->delete();

        return response()->json([
            "message" => "Categoria Eliminada"
        ]);
    }
}

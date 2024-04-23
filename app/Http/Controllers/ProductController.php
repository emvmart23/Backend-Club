<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "name" => "required",
            "category_id" => "required|confirmed",
            "unit_id" => "required|integer",
            "has_alcohol" => "required|boolean"
        ]);

        $productData = $data;
        $product = Product::create($productData);

        return response()->json([
            "Product created successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "message" => "Product not found"
            ],404);
        }

        return response()->json([
            "product" => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "message" => "Product not found"
            ],404);
        }

        $data = $request->validate([
            "name" => "sometimes|string",
            "description" => "sometimes|string",
        ]);

        $product->update($data);

        return response()->json([
            "message" => "Product updated"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "message" => "Product not found"
            ],404);
        }

        $product->delete();

        return response()->json([
            "message" => "Product deleted"
        ]);
    }
}

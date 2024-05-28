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
            "name" => "required|string",
            "price" => "required|numeric|between:0,999999.999",
            "category_id" => "required|integer",
            "unit_id" => "required|integer",
            "has_alcohol" => "required|boolean"
        ]);

        $productData = $data;
        $product = Product::create($productData);

        return response()->json([
            "product" => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $product = Product::with('category','unitMeasure')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'unit_id' => $product->unit_id,
                'has_alcohol' => $product->has_alcohol,
                'unit_name' => $product->unitMeasure->description,
                'category_name' => $product->category->name,
            ];
        });
        return response()->json(["product" => $product]);
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
            "price" => "sometimes|decimal:2",
            "category_id" => "sometimes|integer",
            "unit_id" => "sometimes|integer",
            "has_alcohol" => "sometimes|boolean"
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

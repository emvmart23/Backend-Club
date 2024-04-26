<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
        ]);

        $customer = Customer::create($data);

        return response()->json([
            "Customer" => $customer
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $customer = Customer::all();

        return response()->json([
            "customer" => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $customer = Customer::find($id);

        if(!$customer){
            return response()->json([
                "message" => "Customer not found"
            ],404);
        }

        $data = $request->validate([
            "name" => "sometimes|string",
        ]);

        $customer->update($data);

        return response()->json([
            "message" => "Customer updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if(!$customer){
            return response()->json([
                "message" => "Customer not found"
            ],404);
        }

        $customer->delete();

        return response()->json([
            "message" => "Customer deleted"
        ]);
    }
}

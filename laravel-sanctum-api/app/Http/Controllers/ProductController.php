<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /***
     * To list down all the products
     * @author: Htoo Maung Thait
     * @since : 2021-10-13
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }


    public function store(Request $request)
    {
        $product = Product::create($request->all());

        $response = [
            "status" => "success",
            "message" => "Product created successfully.",
            "data" => $product
        ];

        return response()->json($response, 201);
    }

    public function show(Product $product)
    {
        $response = [
            "status" => "success",
            "message" => "Product retrieved successfully.",
            "data" => $product
        ];
        return response()->json($response, 200);
    }


    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        $response = [
            "status" => "success",
            "message" => "Product updated successfully.",
            "data" => $product
        ];

        return response()->json($response, 200);
    }


    public function destroy(Product $product)
    {
        $productId = $product->id;
        $product->delete();


        $response = [
            "status" => 'success',
            "message" => "Product with id {$productId} deleted successfully."
        ];

        return response()->json($response, 200);
    }

    public function searchByName(Request $request)
    {
        $name = $request->input('name');
        return Product::where('name', 'like', '%' . $name . '%')->get();
    }
}

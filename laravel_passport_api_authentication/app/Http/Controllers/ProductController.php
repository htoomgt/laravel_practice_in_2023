<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Product::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        return  response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        $product->delete();

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}

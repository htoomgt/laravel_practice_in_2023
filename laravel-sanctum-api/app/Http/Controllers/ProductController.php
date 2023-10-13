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

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product;
    }


    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return response()->json($product, 200);
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}

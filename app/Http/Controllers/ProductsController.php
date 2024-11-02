<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Reviews;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        if ($products->isEmpty()) {
            $response = [
                'message' => "NO Recoreds Found",
                'status' => "400"
            ];
        } else {
            $response = [
                'message' => "Get Products Successfully",
                'status' => "200",
                'data' => $products
            ];
        };
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product =  Products::find($id);
        if ($product == null) {
            $response = [
                'message' => "no product found",
                'status' => "400",
            ];
        } else {
            $response = [
                'message' => "list specific Product  Successfully",
                'status' => "200",
                'data' => $product
            ];
        };

        return response($response, 200);
    }
    public function category_products($id)
    {
        $product =  Products::where('category_id', $id)->get();
        $categoryname = Categories::where('id', $id)->first();
        if ($product->isEmpty()) {
            $response = [
                'message' => "no product found in this category",
                'status' => "400",
            ];
        } else {
            $response = [
                'message' => ' list all Products of ' . $categoryname->name . ' successfully',
                'status' => '200',
                'data' => $product
            ];
        };
        return response($response, 200);
    }
    public function high_rate()
    {

        $high_rate = Reviews::all();
        $products = $high_rate->map(function ($item) {
            return  Products::where('id', $item->product_id)->first();
        });

        if ($products->isEmpty()) {
            $response = [
                'message' => "no product have high rated",
                'status' => "400",
            ];
        } else {
            $response = [
                'message' => ' list all Products high rated successfully',
                'status' => '200',
                'data' => $products
            ];
        };

        return response($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string",
            'description' => "required|min:150",
            'price' => "required|numeric",
            'stock' => "required|numeric",
            'image' => "file|max:2048",
            'category_id' => 'required|exists:categories,id'
        ]);
        $product = new Products();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/upload/products/'), $imageName);
        $imagePath = 'images/upload/products/' . $imageName;
        $product->image =  $imagePath;

        $product->category_id = $request->category_id;
        $product->save();
        $response = [
            'message' => "Create Products Successfully",
            'status' => "201",
            'data' => $product
        ];
        return response($response, 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => "required|string",
            'description' => "required|min:256",
            'price' => "required|numeric",
            'stock' => "required|numeric",
            'image' => "file|max:2048",
            'category_id' => 'required|exists:categories,id'
        ]);
        $product = Products::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $image = $request->file('image');
        if ($image == null) {
            $imagePath = $product->image;
        } else {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/upload/products/'), $imageName);
            $imagePath = 'images/upload/products/' . $imageName;
            unlink(public_path($product->image));
        };
        $product->image =  $imagePath;

        $product->category_id = $request->category_id;
        $product->save();
        $response = [
            'message' => "Update Products Successfully",
            'status' => "200",
            'data' => $product
        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = products::find($id);
        if ($product == null) {
            $response = [
                'message' => "no product found to delet ",
                'status' => "400",
            ];
        } else {
            $product->delete();
            $response = [
                'message' => "delete  Product  Successfully",
                'status' => "200",
                'data' => $product
            ];
        }
        return response($response, 200);
    }
}

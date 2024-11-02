<?php

namespace App\Http\Controllers;

use App\Models\product_images;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $product_image = product_images::where('product_id', $id)->get();
        if ($product_image->isEmpty()) {
            $response = [
                'message' => "NO Recoreds Found",
                'status' => "400"
            ];
        } else {
            $response = [
                'message' => "Get Product images Successfully",
                'status' => "200",
                'data' => $product_image
            ];
        };
        return response($response, 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Store the image
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/upload/products/'), $imageName);
        $imagePath = 'images/upload/products/' . $imageName;
        // Save the image in the database
        product_images::create([
            'product_id' => $product->id,
            'image_path' => $imagePath
        ]);

        return response()->json(['message' => 'Image uploaded successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(product_images $product_images)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $productimage = product_images::find($id);
        $productimage->is_main_image = 1;
        $productimage->save();
        $response = [
            'message' => 'product image updated',
            'status' => '201',
            'data' => $productimage
        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $productimage = product_images::find($id);

        if ($productimage == null) {
            $response = [
                'message' => "image not found",
                'status' => 404
            ];
        } else {
            $productimage->delete();
            $response = [
                'message' => "product image deleted",
                'status' => 201
            ];
        }
        return response($response, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categories::all();
        if ($categories->isEmpty()) {
            $response = [
                'message' => "NO Recoreds Found",
                'status' => "200"
            ];
        } else {
            $response = [
                'message' => "Get all categories Successfully",
                'status' => "200",
                'data' => $categories
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
            'description' => "required|min:256",
            'image' => "file|max:2048",
        ]);
        $categories = new Categories();
        $categories->name = $request->name;
        $categories->description = $request->description;
        if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/upload/categories/'), $imageName);
            $imagePath = 'images/upload/categories/' . $imageName;
            $categories->image = $imagePath;
        }
        $categories->save();
        $response = [
            'message' => "Create category Successfully",
            'status' => "201",
            'data' => $categories
        ];
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Categories::find($id);
        if ($category == null) {
            $response = [
                'message' => "no category foun",
                'status' => "200"
            ];
        } else {
            $response = [
                'message' => "list your category successfully",
                'status' => "200",
                'data' => $category
            ];
        };
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
            'image' => "file|max:2048",
        ]);
        $category = Categories::find($id);

        $category->name = $request->name;
        $category->description = $request->description;
        $image = $request->file('image');
        if (!$image) {
            $imagePath = $category->image;
        } else {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/upload/categories/'), $imageName);
            $imagePath = 'images/upload/categories/' . $imageName;
            unlink(public_path( $category->image));
        };
        $category->image = $imagePath;
        $category->save();
        $response = [
            'message' => "Update Category Successfully",
            'status' => "200",
            'data' => $category
        ];
        return response($response, 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Categories::find($id);
        if ($category == null) {
            $response = [
                'message' => "no category foun",
                'status' => "200"
            ];
        } else {
            $category->delete();
            $response = [
                'message' => "delete category successfully",
                'status' => "200",
                'data' => $category
            ];
        };
        return response($response, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {

        $user = auth()->user();
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        $product = Products::find($id);
        if (!$product) {
            $response = [
                'message' => 'Product not found',
                'status' => '404',
            ];
        } else {
            $review = Reviews::create([
                'product_id' => $id,
                'user_id' => $user->id,
                'rating' => $data['rating'],
                'review' => $data['review'],
            ]);
            $response = [
                'message' => 'review created',
                'status' => '201',
                'data' => $review
            ];
        }

        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function showallreviews($id)
    {
        $reviews = Reviews::where('product_id', $id)->get();

        if ($reviews == null) {
            $response = [
                'message' => 'review not found',
                'status' => '404'
            ];
        } else {
            $response = [
                'message' => ' reviews show successfully',
                'status' => '201',
                'data' => $reviews
            ];
        }

        return response($response, 200);
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        $review = Reviews::find($id);
        if (!$review) {
            $response = [
                'message' => 'review not found',
                'status' => '404',
            ];
        } else {
            $review->rating = $data['rating'];
            $review->review = $data['review'];
            $review->save();
            $response = [
                'message' => 'review updated',
                'status' => '201',
                'data' => $review
            ];
        }

        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = Reviews::find($id);

        if (!$review) {
            $response = [
                'message' => 'review not found',
                'status' => '404',
            ];
        } else {
            $review->delete();
            $response = [
                'message' => 'review deleted',
                'status' => '201',
                'data' => $review
            ];
        }
        return response($response, 200);
    }
}

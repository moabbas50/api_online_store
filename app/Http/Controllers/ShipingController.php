<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Shiping;
use App\Models\User;
use Illuminate\Http\Request;

class ShipingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shiping = Shiping::all();
        if ($shiping->isEmpty()) {
            $response = [
                'message' => "no order to shiping",
                'status' => 401
            ];
        } else {
            $response = [
                'message' => "all shiping get successfully",
                'status' => 201,
                'data' => $shiping
            ];
        }
        return response($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        $userid = Orders::where('id', $id)->get('user_id');
        $user = User::where('id', $userid)->get();
        $shipingAddress = Shiping::create([
            'order_id' => $id,
            'client_name'=>$user->name,
            'address' => $user->address,
            'status' => 'in_transit'
        ]);
        $response = [
            'message' => "Create shiping Successfully",
            'status' => "201",
            'data' => $shipingAddress
        ];
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shiping $shiping)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);
        $shiping = Shiping::find($id);
        if (!$shiping) {
            return response()->json(['message' => 'shiping not found'], 404);
        }
        // Update the order status
        $shiping->status = $request->input('status');
        $shiping->save();
        return response()->json([
            'message' => 'Order status updated successfully',
            'status' => '200',
            'data' => $shiping
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shiping = Shiping::find($id);

        if ($shiping == null) {
            $response = [
                'message' => "shiping not found",
                'status' => 404
            ];
        } else {
            $shiping->delete();
            $response = [
                'message' => "shiping deleted",
                'status' => 201
            ];
        }
        return response($response, 200);
    }
}

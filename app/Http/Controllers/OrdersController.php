<?php

namespace App\Http\Controllers;

use App\Models\Cart_items;
use App\Models\Carts;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Orders::all();
        if ($orders->isEmpty()) {
            $response = [
                'message' => "NO Recoreds Found",
                'status' => "400"
            ];
        } else {
            $response = [
                'message' => "Get orders Successfully",
                'status' => "200",
                'data' => $orders
            ];
        };
        return response($response, 200);
    }
    public function vieworderitem($id)
    {
        $order_item = Order_items::Where('order_id', $id)->get();

        if ($order_item->isEmpty()) {
            $response = [
                'message' => "NO Recoreds Found",
                'status' => "400"
            ];
        } else {
            $orderDetails = $order_item->map(function ($item) {
                $product = Products::where('id', $item->product_id)->get('name')->first();
                return [
                    'id' => $item->id,
                    'order_id' => $item->order_id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->total,
                ];
            });
            $totalPrice = $order_item->sum(function ($item) {
                return $item->total;
            });
            $response = [
                'message' => "Get orders Successfully",
                'status' => "200",
                'data' => $orderDetails,
                'total_amount'=>$totalPrice
            ];
        };
        return response($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { // Get the current user
        $user = auth()->user();

        // Retrieve the cart associated with the user
        $cart = Carts::where('user_id', $user->id)->first();
        $car_item = Cart_items::where('cart_id', $cart->id)->get();

        if (!$cart || $car_item->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Calculate total price
        $totalPrice = $car_item->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        // Create an order
        $order = Orders::create([
            'user_id' => $user->id,
            'total_amount' => $totalPrice,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($car_item as $cartItem) {
            Order_items::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
                'total' => $cartItem->quantity * $cartItem->product->price,
            ]);
        }

        // Clear the cart after the order is placed
        $car_item = Cart_items::where('cart_id', $cart->id)->delete();
        $response = [
            'message' => "create order Successfully",
            'status' => "200",
            'data' => $order
        ];
        return response($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
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

        // Find the order by ID
        $order = Orders::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update the order status
        $order->status = $request->input('status');
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully',
            'status' => '200',
            'data' => $order
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Orders::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Delete the order items first
        $order_item = Order_items::Where('order_id', $id);
        $order_item->delete();

        // Delete the order
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}

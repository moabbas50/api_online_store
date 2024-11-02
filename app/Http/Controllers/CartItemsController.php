<?php

namespace App\Http\Controllers;

use App\Models\Cart_items;
use App\Models\Carts;
use App\Models\Products;
use Illuminate\Http\Request;

class CartItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = auth()->user();
        // $cart = Carts::where('user_id', $user->id)->first();
        // $cartItem = Cart_items::where('cart_id', $cart->id)->first();
        // if (!$cart) {
        //     return response()->json(['message' => 'No cart found for this user'], 404);
        // }

        // return response()->json($cart, 200);
        $user = auth()->user();

        // Retrieve the user's cart with cart items and associated product data
        $cart = Carts::where('user_id', $user->id)->first();
        $cart_item = Cart_items::where('cart_id', $cart->id)->get();
        // $product = Products::where('id', $cart_item->product_id)->get();

        if (!$cart || !$cart_item) {
            $response = [
                'message' => "you don`t have any items in the cart ",
                'status' => " 204",
            ];
        } else {
            $cartDetails = $cart_item->map(function ($item) {
                $product = Products::where('id', $item->product_id)->get('name')->first();
                return [
                    'id' => $item->id,
                    'cart_id' => $item->cart_id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ];
            });

            $totalPrice = $cart_item->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            $response = [
                'message' => "you get all items in the cart ",
                'status' => "200",
                'data' => $cartDetails,
                'total_amount' => $totalPrice,
            ];
        }
        return response($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $user = auth()->user();
        $cart = Carts::where('user_id', $user->id)->first();
        if (!$cart) {
            $cart = new Carts();
            $cart->user_id = $user->id;
            $cart->save();
        };
        // create cart if not found
        // $cart = Carts::firstOrCreate(['user_id' => $user->id]);
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);
        $quantity = $request->quantity;
        $product = Products::find($id);
        // check if product already exist
        $cartItem = Cart_items::where('cart_id', $cart->id)->where('product_id', $id)->first();
        if ($cartItem == null) {
            // Add new product to cart
            $cartItem = new Cart_items();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = $id;
            $cartItem->quantity = $quantity;
            $cartItem->price = $product->price;
            $cartItem->save();
            $response = [
                'message' => "product  add to cart   Successfully",
                'status' => "201",
                'data' => $cartItem
            ];
        } else {
            // Update quantity if product already exists
            $cartItem->quantity = $quantity;
            $cartItem->price =  $product->price; // Update price
            $cartItem->save();
            $response = [
                'message' => "product  quantity updated   Successfully",
                'status' => "200",
                'data' => $cartItem
            ];
        }
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart_items $cart_items)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)

    {
        $cart_items = Cart_items::find($id);

        if ($cart_items == null) {
            $response = [
                'message' => "no product found to delet ",
                'status' => "200",
            ];
        } else {
            $cart_items->delete();
            $response = [
                'message' => "delete  Product  Successfully",
                'status' => "200",
                'data' => $cart_items
            ];
        }
        return response($response, 200);
    }
}

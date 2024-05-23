<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::all());
    }

    public function store(StoreOrderRequest $request)
    {
        $cart = Cart::findOr(1, fn() => missingRoute());
        $cart->load('products');

        $cart->products->each(function (Product $product) {
            $product->quantity -= $product->pivot->quantity;
            $product->save();
        });


        $newOrder = Order::create([
            'user_id' => Auth::id(),
            'cart_id' => $request->post('cart_id'),
            'location_id' => $request->post('location_id'),
            'status' => "pending"
        ]);
        // Test Order creation and implement delivered orders

        return new OrderResource($newOrder);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([

        ]);

        $order->update($data);

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAvailableException;
use App\Http\Requests\StoreItemsToCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * @throws NotAvailableException
     */
    public function storeItems(StoreItemsToCartRequest $request)
    {
        // TODO: Refactor product counting logic later for better performance( adding product_numbers in cart table)

        $user = Auth::user();

        /**
         * @var Cart $cart
         */
        $cart = $user->getCart();
//        Fix: cannot add product with two variants


        $request->getProducts()->each(function ($product) use ($cart) {
            $a = "b";
            $cart->products()->attach([$product['id']]);
        });


        $cart->updateTotalPrice($request->getAttributes(), $request->getQuantity());


        return response([
            'message' => "Items added to cart",
            'resource' => new CartResource($cart),
            'code' => 201
        ], 201);
    }

    public function show()
    {
        // TODO: fix attributes in the cart for products
        $user = Auth::user();
        $cart = Cart::findOr($user->cart_id, fn() => missingRoute());
        // TODO: paginate if needed
        $cart->load('products');
        return resource(new CartResource($cart));

    }

    public function removeItem($id)
    {
        // TODO: Refactor later
//        $product = Product::find($id);

        $cart = Auth::user()->getCart();
        $product = $cart->products()->firstWhere('id', $id);
        if (!$cart->hasProduct($product)) return missingRoute();

        $newQuantity = $cart->removeProduct($product);

        return ['message' => $product->name . " removed from cart", 'new_quantity' => $newQuantity];

    }

    public function clear()
    {
        $user = Auth::user();

        $cart = Cart::find($user->cart_id)->first();
        $cart->products()->detach();
        $cart->updateTotalPrice();

        return ['message' => "Cart cleared successfully", 'code' => 200];


    }

    /*public function destroy()
    {
        $user = Auth::user();
        $isDeleted = Cart::destroy($user->cart_id);
        if (!$isDeleted) return missingRoute();
        return [
            'message' => "cart cleared",
            'code' => 200
        ];
    }*/
}

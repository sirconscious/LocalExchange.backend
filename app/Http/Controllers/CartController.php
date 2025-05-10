<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsRessource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Add the product to the cart
        $cart = auth()->user()->carts()->create([
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $cart,
        ]);
    }

    public function removeFromCart($id)
    {
        // Find the cart item
        $cartItem = auth()->user()->carts()->where('product_id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        // Delete the cart item
        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart successfully',
        ]);
    } 
 public function getCart()
{
    // Get the user's cart items
    $cartItems = auth()->user()->carts()->with('product')->get();

    // Transform the cart items using ProductResource
    $transformedCartItems = $cartItems->map(function ($cartItem) {
        return [
            'id' => $cartItem->id,
            'product' => new ProductsRessource($cartItem->product),
        ];
    });

    return response()->json([
        'status' => true,
        'cart' => $transformedCartItems,
    ]);
}
}

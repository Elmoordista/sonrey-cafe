<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cart_detail;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class CartDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart_detail  $cart_detail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return Order::where('id', $id)->with('order_detail')->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart_detail  $cart_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart_detail $cart_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart_detail  $cart_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart_detail $cart_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart_detail  $cart_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart_detail $cart_detail)
    {
        //
    }
    public function deleteProduct(Request $request)
    {
        try {
            $cart = Cart::where('id', $request->cart_id)->first();
            $data = array(
                'cart_total' =>$cart->cart_total - $request->total,    
            );
            $cart = Cart::where('id',$cart->id)->update($data);

            return Cart_detail::where('cart_id', $request->cart_id)->where('product_id',$request->product_id)->delete();
           
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function updateCart(Request $request)
    {
        try {
            $cart = Cart::where('id', $request->cart_id)->first();
            $totalCart = ($cart->cart_total - $request->old_total) + $request->total;
            $data = array(
                'cart_total' => $totalCart,    
            );
            $cart = Cart::where('id',$cart->id)->update($data);

            $cartDetail = array(
                'quantity' => $request->quantity,    
                'total' => $request->total,    
            );

            return Cart_detail::where('cart_id', $request->cart_id)->where('product_id',$request->product_id)->update($cartDetail);
           
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

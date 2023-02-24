<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cart_detail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cart::where('status', 1)->where('client_id', 1)->with('cart_detail')->first();
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
        try {
            $cart = [];
            $cart_status = Cart::where('status', 1)->where('client_id', 1)->first();
            if(empty($cart_status)){
                $data = array(
                    'client_id' => 1,   
                    'cart_total' => $request->total,    
                    'status' => 1, 
                );
                $cart = Cart::create($data);
            }
            else{
                $data = array(
                    'cart_total' => $request->total + $cart_status->cart_total,    
                );
                $cart = Cart::where('id',$cart_status->id)->update($data);
            }
           
            if($cart && $cart_status){
                $cart_id = isset($cart->id) ? $cart->id : $cart_status->id;
                $cart_product = Cart_detail::where('cart_id', $cart_id)->where('product_id',$request->id)->first();

                if(empty($cart_product)){
                    $cart_detail = array(
                        'cart_id' =>   $cart_id,    
                        'product_id' => $request->id,   
                        'quantity' => $request->quantity,    
                        'total' => $request->total,    
                    );
                    return Cart_detail::create($cart_detail);
                }
                else{
                    $cart_detail = array(   
                        'quantity' => $request->quantity + $cart_product->quantity,    
                        'total' => $request->total + $cart_product->total,    
                    );
                    return Cart_detail::where('cart_id', $cart_id)->where('product_id',$request->id)->update($cart_detail);
                }

            }

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
    public function usercart()
    {
       return Auth::user();
    }

    public function addtocart(Request $request)
    {
        try {
            $cart = [];
            $cart_status = Cart::where('status', 1)->where('client_id', Auth::user()->id)->first();
            
            if(empty($cart_status)){
                $data = array(
                    'client_id' => Auth::user()->id,   
                    'cart_total' => $request->total,    
                    'status' => 1, 
                );
                $cart = Cart::create($data);
            }
            else{
                $data = array(
                    'cart_total' => $request->total + $cart_status->cart_total,    
                );
                Cart::where('id',$cart_status->id)->update($data);
                $cart = Cart::where('id',$cart_status->id)->update($data);
            }
            
           
            if($cart || $cart_status){
                $cart_id = isset($cart->id) ? $cart->id : $cart_status->id;
                $cart_product = Cart_detail::where('cart_id', $cart_id)->where('product_id',$request->product_id)->first();

                if(empty($cart_product)){
                    $cart_detail = array(
                        'cart_id' =>   $cart_id,    
                        'product_id' => $request->product_id,   
                        'quantity' => $request->quantity,    
                        'total' => $request->total,    
                    );
                    return Cart_detail::create($cart_detail);
                }
                else{
                    $cart_detail = array(   
                        'quantity' => $request->quantity + $cart_product->quantity,    
                        'total' => $request->total + $cart_product->total,    
                    );
                    return Cart_detail::where('cart_id', $cart_id)->where('product_id',$request->product_id)->update($cart_detail);
                }

            }

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
      
    }
    public function getcart()
    {
       $user = Auth::user();
       return Cart::where('client_id', $user->id)->where('status',1)->with('cart_detail')->first();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cart_detail;
use App\Models\Order;
use App\Models\Order_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with('client')->get();
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
        $request['order_ref'] = $this->generateRandomString();
        $request['total'] = $request->cart_total;
        $status = 0;
        $cart = Cart::where('id',$request->id)->with('cart_detail')->first();
        
        if($cart){
            $status = count($cart->cart_detail) == count($request->cart_detail) ? 0 : 1;
            $cart->update(['status'=>$status]);
        }
        $order = Order::create($request->toArray());

        foreach($request->cart_detail as $cart_details){
            $var = array(
                'order_id'=> $order->id,
                'quantity'=> $cart_details['quantity'],
                'product_id'=> $cart_details['product_id'],
                'total'=> $cart_details['total'],
            );
            $orderdetail = Order_detail::create($var);
        }

        return $order;
    }

    public static function generateRandomString($length = 10) {
        $string = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        if(Order::where('order_ref',$string)->exists()){
            self::generateRandomString();
        }
        else{
            return $string;
        }
    }
    public function update_status(Request $request) {
        return Order::where('id',$request->id)->update(['status'=>$request->status]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::where('status',$id)->with('client')->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Order::where('id',$id)->delete();
    }

    public function addorder(Request $request)
    {
        $request['total'] = $request->cart_total;
        $status = 0;
        $cart = Cart::where('id',$request->cart_id)->with('cart_detail')->first();
        
        if($cart){
            $status = count($cart->cart_detail) == count($request->cart_order) ? 0 : 1;
            $cart->update(['status'=>$status]);
        }

        $request['client_id'] = Auth::user()->id;
        $order = Order::create($request->toArray());

        foreach($request->cart_order as $cart_details){
            $var = array(
                'order_id'=> $order->id,
                'quantity'=> $cart_details['quantity'],
                'product_id'=> $cart_details['product_id'],
                'total'=> $cart_details['total'],
            );            
            $orderdetail = Order_detail::create($var);

            if($orderdetail){
                $cart->update(['cart_total'=>($cart->cart_total - $cart_details['total'])]);
                Cart_detail::where('product_id',$cart_details['product_id'])->where('cart_id',$cart->id)->delete();
            }
        }

        return $order;
    }
    public function getuserorder()
    {
       $user = Auth::user();

        return Order::where('client_id', $user->id)->where('status', 0)->with('order_detail')->first();
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cart_detail;
use App\Models\Client;
use App\Models\Fcmtoken;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function getOrderList()
    {
        $now = Carbon::now()->format('Y-m-d');
        $data = Order::where('status',1)->Orwhere('status',3)->whereDate('created_at', $now)->with('order_detail')->get();
        foreach($data as $key => $datas){
            $data[$key]['order_num'] = $key + 1;
        }
        return $data;
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

    public function sendNotification()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = Fcmtoken::whereNotNull('fcm_token')->pluck('fcm_token')->all();

        $serverKey = 'AAAADtOcBZU:APA91bFqoJtVLiT0GVk2hSHlpxJ5VMZWEMIwNjLHjuQ2xr00A_7-BeJsXDL_N08QIcq2j6W-a1-0VSYBB-8EhhtdqSE7wXhWW_0XOSWPvkRczAb8Y3ZdeON48B02Vq9XpMxOD4U4c5-r'; // ADD SERVER KEY HERE PROVIDED BY FCM
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => 'New order added',
                "body" => 'New order added',  
            ]
        ];

        $encodedData = json_encode($data);
    

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // // FCM response
        // dd($result);
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
        if($request->status == 1 || $request->status == 3){
            $this->sendNotification();
        }
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
    public function productreport()
    {
       $user = Auth::user();

        return Order::where('client_id', $user->id)->where('status', 0)->with('order_detail')->first();
        
    }

    public function orderReport($date, Request $request)
    {

        // return Product::with('order_detail', function ($prog)  use ($request){
        //     $prog->where('status', 3);
        // })->get();

        $orders = [];
        $type = $date;
        if($date == 'week' || $date=='range') {
            $label_request = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ];
            $period = CarbonPeriod::create(Carbon::parse($request->date_request['start']), Carbon::parse($request->date_request['end']));
     
            $week_dates = [];
            foreach ($period as $date) {
                $week_dates[]= $date->format('Y-m-d');
            }

            foreach($week_dates as $key => $dates) {
                    $sum = Order::whereDate('created_at', $dates)->where('status',3)->sum('total');
                    $orders['label'][] = $type == 'week' ? $label_request[$key] : $dates;
                    $orders['data'][] = (int)$sum ? (int) $sum : 0;
            }

            return response()->json(["total" =>  $orders, "most_order" => $this->mostOrderedFood(), "top_product_order" => $this->mostOrderedFoods()]);

        }
        else if($date == 'year') {
            $label_month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ];

            $dateFormatYear = date('Y', strtotime($request->month_request));
            $y = Carbon::createFromFormat('Y', $dateFormatYear)->format('Y');
            $dates = [];
            for($i=1; $i<=12; $i++){
                $dates[] = [
                        Carbon::create($y, $i)->startOfMonth()->format('Y-m-d'),
                        Carbon::create($y, $i)->lastOfMonth()->format('Y-m-d'),
                ];
            };
            
            foreach ($dates as $key => $date) {
                $sum = Order::whereBetween('created_at',$date)->where('status',3)->sum('total');
                $orders['label'][] = $label_month[$key];
                $orders['data'][] = (int)$sum ? (int) $sum : 0;
              
            }
            return response()->json(["total" =>  $orders, "most_order" => $this->mostOrderedFood(), "top_product_order" => $this->mostOrderedFoods()]);

        }
        else {
            return "sad";
        }

        // return Order::with('orderer')->get();
    }

    // public function orderReport($date, Request $request)
    // {
    //     $orders = [];
    //     $final_order = [];
    //     if($date == 'week' || $date=='range') {
    //         $label_request = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ];
    //         $period = CarbonPeriod::create(Carbon::parse($request->date_request['start']), Carbon::parse($request->date_request['end']));
     
    //         $week_dates = [];
    //         foreach ($period as $date) {
    //             $week_dates[]= $date->format('Y-m-d');
    //         }

    //         foreach($week_dates as $dates) {
    //             $order = Order::whereDate('created_at',$dates)->with('orderer','details')->get();
    //             if(count($order)){
    //                 $orders [] = $order;
    //             }
    //         }

    //         return $week_dates;

            
    //         foreach($orders as $getOrder){
    //             foreach($getOrder as $data){
    //                 foreach($data['details'] as $datas){
    //                     $final_order[] = array(
    //                         'time_order' => $data->created_at,
    //                         'quantity' => $datas['quantity'],
    //                         'customer_name' => $data['orderer']['fullname'],
    //                         'product' => $datas['product']['name'],
    //                         'price' => $datas['product']['price'],
    //                         'total' => $datas['total'],
    //                     );
    //                 }
    //             }
    //         }
    //         $total_amount = 0;
    //         foreach(array_unique($final_order, SORT_REGULAR) as $getamount){
    //             $total_amount = $total_amount + $getamount['total'];
    //         }
    //         // return array_unique($final_order);
    //         return response()->json(["data" => array_unique($final_order, SORT_REGULAR), "total" => $total_amount]);
    //         $label = $label_request;
    //     }
    //     else if($date == 'year') {
    //         $label_month = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ];

    //         $dateFormatYear = date('Y', strtotime($request->month_request));
    //         $y = Carbon::createFromFormat('Y', $dateFormatYear)->format('Y');
    //         $dates = [];
    //         for($i=1; $i<=12; $i++){
    //             $dates[] = [
    //                 [
    //                     Carbon::create($y, $i)->startOfMonth()->format('Y-m-d'),
    //                     Carbon::create($y, $i)->lastOfMonth()->format('Y-m-d'),
    //                 ],
    //             ];
    //         };

    //         foreach ($dates as $key => $date) {
    //             $order= Order::whereBetween('created_at',$dates)->with('orderer','details')->get();
    //             if(count($order)){
    //                 $orders [] = $order;
    //             }
              
    //         }
    //         foreach($orders as $getOrder){
    //             foreach($getOrder as $data){
    //                 foreach($data['details'] as $datas){
    //                     $final_order[] = array(
    //                         'time_order' => $data->created_at,
    //                         'quantity' => $datas['quantity'],
    //                         'customer_name' => $data['orderer']['fullname'],
    //                         'product' => $datas['product']['name'],
    //                         'price' => $datas['product']['price'],
    //                         'total' => $datas['total'],
    //                     );
                        
    //                 }
    //             }
    //         }
    //         $total_amount = 0;
    //         foreach(array_unique($final_order, SORT_REGULAR) as $getamount){
    //             $total_amount = $total_amount + $getamount['total'];
    //         }
    //         // return array_unique($final_order);
    //         return response()->json(["data" => array_unique($final_order, SORT_REGULAR), "total" => $total_amount]);
    //     }
    //     else {
    //         return "sad";
    //     }

    //     // return Order::with('orderer')->get();
    // }
    public function mostOrderedFood(){   
        return DB::table('orders')
                ->leftJoin('order_details','order_details.order_id','=','orders.id')
                ->leftJoin('products','products.id','=','order_details.product_id')
                ->selectRaw('products.*, sum(orders.total) total')
                ->where('orders.status',3)
                ->groupBy('order_details.product_id')
                ->orderBy('total','desc')
                ->take(1)
                ->first();
    }
    public function mostOrderedFoods(){
        $prods = [];   
        $datas = DB::table('orders')
                ->leftJoin('order_details','order_details.order_id','=','orders.id')
                ->leftJoin('products','products.id','=','order_details.product_id')
                ->selectRaw('products.*, sum(orders.total) total')
                ->where('orders.status',3)
                ->groupBy('order_details.product_id')
                ->orderBy('total','desc')
                ->take(5)
                ->get();

        foreach($datas as $key => $data) {
                $prods['label'][] = $data->product_name;
                $prods['data'][] = (int)$data->total? (int)$data->total : 0;
                $prods['backgroundColor'][] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                
        }

        return $prods;
    }
}

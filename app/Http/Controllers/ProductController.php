<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Product::whereHas('category', function ($data) {
                $data->where('status', 1);
            })->get();

          
        } catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
        }
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
        // try {
            if (preg_match('#^data:image.*?base64,#', $request->image)) {
                 $request['image'] = $this->uploadImage($request);
            }

            $request->validate([
                'product_name' => 'required',
                'price' => 'required',
                'category_id' => 'required',
            ]);

            if(isset($request->id)){
                return Product::where('id', $request->id)->update($request->toArray());
            }
            else{
                return Product::create($request->toArray());
            }
        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 500);
        // }
    }
    public function searchproduct(Request $request)
    {
        try {
           $sql = Product::query();
           if($request->name){
                $sql->where('product_name','LIKE',"%".$request->name."%");
           }
           if($request->cat_id){
                $sql->where('category_id',$request->cat_id);
           }
           return $sql->get();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function uploadImage($request){
        $path = 'images';
        $image = $request['image'];  // your base64 encoded
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $data = base64_decode($image);
        $imageName = rand(1, 100) . '.' . $extension;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (file_put_contents(public_path() . '/' .  $path . '/' . $imageName, $data)) {
            return asset('/') .  $path  . '/' . $imageName;
        }
    }

    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
      
    }
    public function deleteProduct($id)
    {
       return Product::where('id', $id)->delete();
    }

    
}

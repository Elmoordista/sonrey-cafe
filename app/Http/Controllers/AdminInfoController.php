<?php

namespace App\Http\Controllers;

use App\Models\AdminInfo;
use Illuminate\Http\Request;

class AdminInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AdminInfo::latest()->first();
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
       
        if (preg_match('#^data:image.*?base64,#', $request->logo)) {
                $request['logo'] = $this->uploadImage($request);
        }
        

        $request->validate([
            'gcash_name' => 'required',
            'gcash_number' => 'required|numeric|digits:11',
            'contact_number' => 'required|numeric|digits:11',
        ]);

        if(isset($request->id)){
           return AdminInfo::where('id', $request->id)->update($request->toArray());
        }
        else{
            return AdminInfo::create($request->toArray());
        }
    }

    public function uploadImage($request){
        $path = 'images';
        $image = $request['logo'];  // your base64 encoded
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $data = base64_decode($image);
        $imageName = 'adminlogo_' .rand(1, 999999) . '.' . $extension;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (file_put_contents(public_path() . '/' .  $path . '/' . $imageName, $data)) {
            return asset('/') .  $path  . '/' . $imageName;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminInfo  $adminInfo
     * @return \Illuminate\Http\Response
     */
    public function show(AdminInfo $adminInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminInfo  $adminInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminInfo $adminInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminInfo  $adminInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminInfo $adminInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminInfo  $adminInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminInfo $adminInfo)
    {
        //
    }
}

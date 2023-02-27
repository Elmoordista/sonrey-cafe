<?php

namespace App\Http\Controllers;
use Validator;

use App\Models\Admin;
use App\Models\Fcmtoken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     use ValidatesRequests;

     public function login(Request $request)
     {
        if (Auth::guard('admin')->attempt(['email' => $request['email'], 'password' =>  $request['password']])) {
            $user = Auth::guard('admin')->user();
            $fcm_token = Fcmtoken::where('fcm_token', $request->fcm_token)->first();

            if (!$fcm_token) {
                Fcmtoken::create([
                    'fcm_token' => $request->fcm_token,
                    'client_id' => $user->id
                ]);
            }
            $user['token'] =  $user->createToken('MyApp',['admin'])->accessToken->token;
            return  ['message' => 'success',  $user];
        } else {
            return ['message' => 'no account'];
        }
     }

    public function index()
    {
        return Admin::orderBy('role','ASC')->get();
    }
    public function getAdmin()
    {
        return Auth::user();
    }
    public function checkuser()
    {
        return Auth::guard('admin')->user();
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
           if(isset($request->id)){
                $request->validate([
                    'email' => 'email|required|unique:admins,email,'.$request->id,
                ]);
               if($request['password']){
                   $request['password'] = bcrypt($request['password']);
               }
               else{
                unset($request['password']);
               }
 
               unset($request['confirmpassword']);
               Admin::where('id', $request->id)->update($request->toArray());
           }
           else{
                $request->validate([
                    'email' => 'email|required|unique:admins,email,',
                    'password' => 'required',
                    'confirmpassword' => 'required',
                ]);

               $request['password'] = bcrypt($request['password']);
               return Admin::create($request->toArray());
           }
           

        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 500);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$role)
    {
      try {
        $account = Admin::where('role',$role)->get();
        if(count($account) == 1){
            return response()->json(['message' => $account], 422);
        }
        else{
            Admin::where('id',$id)->delete();
            return response()->json(['message' => 'delete success'],200);
        }
        
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

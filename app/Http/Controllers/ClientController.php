<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\Client;
use App\Models\Fcmtoken;
use App\Models\PasswordReset as ResetPassword;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        
        try {
            $login = $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $fcm_token = FcmToken::where('fcm_token', $request->fcm_token)->first();

            $user = Client::where('email', $request->email)
            // ->where('is_validated',1)
            ->first();


            if (!$fcm_token) {
                Fcmtoken::create([
                    'fcm_token' => $request->fcm_token,
                    'client_id' => $user->id
                ]);
            }

            if (!Auth::attempt($login) || !$user) {
                return response(['message' => 'login Credentials are incorrect'], 500);
            }

            $token = $user->createToken('authToken')->accessToken;


            return response(['access_token' => $token]);
            return response(['access_token' => $token, 'client_id' => $user->id]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            return Client::where('role',null)->get();
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
    }

    public function info()
    {
        try {
            return Auth::user();
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

            if(isset($request->id)){

                $request->validate([
                    'email' => 'email|required|unique:clients,email,'.$request->id,
                    'birth_date' => 'required',
                    'number' => 'required|numeric|digits:11',
                    'name' => 'required',
                    'username' => 'required|unique:clients,username,'.$request->id,
                ]);
                

                if($request['password']){
                    $request['password'] = bcrypt($request['password']);
                }
                else{
                    unset($request['password']);
                }
     
                Client::where('id', $request->id)->update($request->toArray());
            }
            else{

                $request->validate([
                    'email' => 'email|required|unique:clients,email,',
                    'birth_date' => 'required',
                    'number' => 'required|numeric|digits:11',
                    'name' => 'required',
                    'username' => 'required|unique:clients,username,',
                    'password' => 'required',
                    'confirmpassword' => 'required',
                ]);

                $request['password'] = bcrypt($request['password']);
                return Client::create($request->toArray());
            }

        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 500);
        // }
    }

    public function logout()
    {
        Auth::logout();
    }

    public function register(Request $request)
    {
        try {
            if (preg_match('#^data:image.*?base64,#', $request->image)) {
                 $request['image'] = $this->uploadImage($request);
            }

            if(isset($request->id)){
                if($request['password']){
                    $request['password'] = bcrypt($request['password']);
                }
                Client::where('id', $request->id)->update($request->toArray());
            }
            else{
                $request['password'] = bcrypt($request['password']);
                return Client::create($request->toArray());
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function uploadImage($request){
        $path = 'images';
        $image = $request['image'];  // your base64 encoded
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $data = base64_decode($image);
        $imageName = $request->birth_date. '_' .rand(1, 999999) . '.' . $extension;
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
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Client::where('id',$id)->first();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            return Client::where('id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function porgot_pass(Request $request)
    {

        $this->validate($request, [
            'email' => 'required | email'
        ]);

        $isCoachNotExist = Client::where('email', $request->email)->doesntExist();

        if ($isCoachNotExist) {
            return response()->json(['message' => 'Email not exists'], 500);
        }

        $token = $this->generateCode();

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token
        ]);

        
        Mail::to($request->email)->send(new PasswordReset($token, $request->email));

        return response()->json(['message' => 'Password Reset Code Succesufly Sent!']);
    }

    public static function generateCode(){

        $rand = Str::random(8);
        if(ResetPassword::where('token',$rand)->exists()){
            self::generateCode();
        }
        else{
            return $rand;
        }
    }
    
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'new_password' => 'required',
            'code'        => 'required'
        ]);

        $IfExist = DB::table('password_resets')
            ->where('token', $request->code)
            ->first();

        if ($IfExist) {
            Client::where('email', $IfExist->email)->update([
                'password' => bcrypt($request->new_password)
            ]);
            return response()->json(['message' => 'Password Succesfuly Reset']);
        }

        return response()->json(['message' => 'Error', 500]);
    }

}

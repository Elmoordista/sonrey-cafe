<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Exception;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    
    public function index()
    {
        try {
                return Announcement::all();
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
    }
    public function destroy($id)
    {
        try {
            return Announcement::where('id',$id)->delete();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        // try {

            $request->validate([
                'image' => 'required',
                'link' => 'required',
                'tittle' => 'required',
            ]);

            if (preg_match('#^data:image.*?base64,#', $request->image)) {
                 $request['image'] = $this->uploadImage($request);
            }

            if(isset($request->id)){
                return Announcement::where('id', $request->id)->update($request->toArray());
            }
            else{
                $request['password'] = bcrypt($request['password']);
                return Announcement::create($request->toArray());
            }

        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 500);
        // }
    }
    
    public function uploadImage($request){
        $path = 'images';
        $image = $request['image'];  // your base64 encoded
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $data = base64_decode($image);
        $imageName = $request->birth_date. 'announce_' .rand(1, 999999) . '.' . $extension;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (file_put_contents(public_path() . '/' .  $path . '/' . $imageName, $data)) {
            return asset('/') .  $path  . '/' . $imageName;
        }
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

// All good
class ImageController extends Controller
{
    public function upload(Request $request){
        $validator = Validator::make($request->all(), [
            "images" => 'required'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errore()->first());
        }
        $image_url = [];
        $images = $request->file('images');
        if($request->hasFile('images')){
            foreach($images as $image){
                $new_name = time()."_".Str::random(10).".".$image->getClientOriginalExtension();
                $image->move('Images', $new_name);
                $image_url[] = env('APP_URL')."/images/".$new_name;
            }
        }else{
            return "None photo is uploaded!";
        }
        return $image_url;
    }
    public function delete(Request $request){
        $image_path = public_path('/images/'.$request->name);
        if(!$request){
            return ResponseController::error('There is no such image under this name', 404);
        }
        File::delete($image_path);
        return ResponseController::success();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public static function data($data = [ ], $code = 200){
        return response($data, $code);
    }
    public static function error($data = null, $code = 422){
        return response([
            'message' => $data
        ], $code);
    }
    public static function success($message = "Successful", $code = 400){
        return response([
            'message' => $message
        ]);
    }
}





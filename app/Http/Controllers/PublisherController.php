<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// All good
class PublisherController extends Controller
{
    public function create(Request $request){
        $name = $request->name;
        try {
            $this->authorize('create', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed!');
        }
        $validator = Validator::make($request->all(), [
            "name" => 'required|unique:publishers,name',
            "image" => 'required|url',
            "logo_img" => 'required|url',
            'description' => 'nullable',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $publisher = Publisher::where('name', $name)->first();
        if($publisher){
            return ResponseController::error('This Publisher is already exists');
        }
        Publisher::create([
            'name' =>$name,
            'image' => $request->image,
            'logo_img' => $request->logo_img,
            'description' => $request->description
        ]);
        return ResponseController::success();
    }
    public function show(Request $request){
        $get = Publisher::paginate(5);
        if(count($get) == 0){
            return ResponseController::data('There is no publisher', 404);
        }
        return ResponseController::data($get);
    }
    public function delete(Publisher $publisher){
        if(!isset($publisher)){
            return  ResponseController::error('Not found', 404);
        }
        $publisher->products->delete();
        $publisher->delete();
        return ResponseController::success('Publisher and related product have been successfully deleted');
    }
    public function update(Request $request){
        $validation  = Validator::make($request->all(), [
            "name" => 'required|exists:publishers,name',
            "image" => 'required|url',
            "logo_img" => 'required|url',
            "description" => 'nullable|string'
        ]);
        if($validation->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $update = Publisher::where('id', $request->id)->first();
        if(!$update){
            return ResponseController::error('Not found', 404);
        }
        $update->update([
            'name'=>$request->name
        ]);
        return ResponseController::success();
    }
    public function history(Request $request){
        $show = Publisher::onlyTrashed()->get(['id', 'name', 'image']);
        if(count($show) == 0){
            return ResponseController::data('No value found');
        }
        return ResponseController::data($show);
    }
    public function restore(Request $request){
        $publisher = Publisher::withTrashed()
        ->find($request->publisher_id);
        $product = Product::withTrashed()
        ->where('publisher_id', $publisher->id)
        ->first();
        // return $product;
        if($publisher->trashed()){
            $publisher->restore();
            $product->restore();
            return ResponseController::success();
        }else{
            return ResponseController::error('No value deleted by software');
        }


    }
}

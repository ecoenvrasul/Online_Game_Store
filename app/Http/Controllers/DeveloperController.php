<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Product;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// All good
class DeveloperController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create a Developer!', 422);
        }
        $validator = Validator::make($request->all(), [
            "name" => 'required|string',
            "image" => 'required|url',
            "logotip" => 'required|url'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $developer = Developer::where('name', $request->name)->first();
        if($developer){
            return ResponseController::error('This developer is already exists!');
        }
        Developer::create([
            'name' => $request->name,
            "image" => $request->image,
            "logotip" => $request->logotip
        ]);
        return ResponseController::success();
    }
    public function update(Request $request ){
        try {
            $this->authorize('update', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update any Developer', 422);
        }
        $validator = Validator::make($request->all(),[
            'name' =>'required|unique:developers,name',
            'image' =>'required|url',
            'logotip' =>'required|url',
        ]);
        if ($validator->fails()) {
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $update = Developer::find($request->id);
        if(!$update){
            return ResponseController::error('Not found', 404);
        }
        $update->update($request->all());
        return ResponseController::success();
    }
    public function show(Request $request){
        $developers = Developer::paginate(20);
        if(count($developers) == 0){
            return ResponseController::error('No developers yet', 404);
        }
        return ResponseController::data($developers);
    }
    public function delete(Request $request){
        try {
            $this->authorize('delete', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You do not have the right to delete any Developer');
        }
        $developer = Developer::where('id', $request->developer_id)
        ->first();
        if(!$developer){
            return ResponseController::error('No developer found to delete', 404);
        }
        $developer->delete();
        $product_id = Product::where('developer_id', $developer->id)
        ->delete();
        return ResponseController::success('Developer and its products are deleted');
    }
    public function history(Request $request){
        try {
            $this->authorize('view', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to see any Developer here!', 422);
        }
        $developer = Developer::onlyTrashed()
        ->get();
        if(!$developer){
            return ResponseController::error('There is no deleted Developer', 404);
        }
        return $developer;
    }
    public function restore(Request $request){
        try {
            $this->authorize('restore', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to restore any Developer!', 422);
        }
        $developer = Developer::onlyTrashed()
        ->find($request->id);
        $product = Product::withTrashed()
        ->where('developer_id', $developer->id)
        ->first();
        if(!$developer){
            return ResponseController::error('There is no deleted Developer', 404);
        }elseif($developer->trashed()){
            $developer->restore();
            $product->restore();
            return ResponseController::success('Developer and its products are successfully restored');
        }
    }
}

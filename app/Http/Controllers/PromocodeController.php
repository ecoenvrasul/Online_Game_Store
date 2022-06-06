<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

// All good
class PromocodeController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Promocode::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create a Promocode');
        }
        $validator = Validator::make($request->all(), [
            "promocode" => 'required|unique:promocodes',
            "discount" => 'nullable|numeric',
            "user_id" => 'required|exists:users,id',
            "count" => 'nullable|numeric'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $user = $request->user();
        $count = $request->count ?? 0;
        Promocode::create([
            "promocode" => $request->promocode,
            "discount" => $request->discount,
            "user_id" => $user->id,
            "count" => $count
        ]);
        return "done";
    }
    public function showAllPromocodes(){
        try {
            $this->authorize('create', Promocode::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create a Promocode');
        }
        $promocodes = Promocode::orderBy('id', 'Desc')
        ->paginate(20);
        return $promocodes;
        if(empty($promocodes)){
            return ResponseController::error('There is no promocode!', 404);
        }
        $collection = [
            "last_page" => $promocodes->lastPage(),
            "promocodes" => [],
        ];
        foreach($promocodes as $promocode){
            $collection["promocodes"][] = [
                "promocode_id" => $promocode->id,
                "promocode" => $promocode->promocode,
                "discount" => $promocode->discount,
                "count" => $promocode->count,
            ];
        }
        return ResponseController::data($collection);
    }
    public function delete(Request $request){
        try{
            $this->authorize('delete',Promocode::class);
        }catch(\Throwable $th){
            return ResponseController::error('You are not allowed to delete a Promocode');
        }
        $promocode = Promocode::find($request->promocode_id);
        if(!$promocode){
            return ResponseController::error('Promocode not found', 404);
        }
        $promocode->delete();
        return ResponseController::success('Promocode succesfuly deleted');
    }
}

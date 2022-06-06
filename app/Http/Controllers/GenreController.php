<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// All good
class GenreController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create a Genre', 422);
        }
        $validator = Validator::make($request->all(), [
           "name" => 'required|string'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $genre = Genre::where('name', $request->name)->first();
        if($genre){
            return ResponseController::error('This genre is exists');
        }
        Genre::create([
            "name" => $request->name
        ]);
        return ResponseController::success();
    }
    public function update(Request $request){
        try {
            $this->authorize('update', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update any Genre', 422);
        }
        $validator = Validator::make($request->all(),[
            "name" => 'required|string'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $index = Genre::find($request->id);
        if(!$index){
            return ResponseController::error('No genre found');
        }
        $validator = Validator::make($request->all(),[
            'name' =>'required|unique:genres,name'
        ]);
        if ($validator->fails()) {
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $index->update([
            "name" => $request->name
        ]);
        return ResponseController::success();
    }
    public function show(Request $request){
        $genres = Genre::paginate(10);
        $collection = [
            "last_page" => $genres->lastPage(),
            "genres" => [],
        ];
        foreach($genres as $genre){
            $collection['ganres'][] =[
                "genre_id" =>$genre->id,
                "genre_title" => $genre->name
            ];
        }
        if(count($genres) == 0){
            return ResponseController::error('No genres');
        }
        return ResponseController::data($genres);
    }
    public function singleGenre(Genre $genre){
        return $genre;

    }
    public function delete(Request $request){
        try {
            $this->authorize('delete', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to delete any Genre here!', 422);
        }
        $index = Genre::find($request->id);
        if(!$index){
            return ResponseController::error('Not found', 404);
        }
        $index->delete();
        $product_id = Product::where('genre', $request->id)->get(['id']);
        Product::destroy($product_id);
        return ResponseController::success();
    }
    public function history(){
        try {
            $this->authorize('view', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to see the deleted Genre history!');
        }
        $history = Genre::onlyTrashed()->get();
        return $history;
    }
    public function restore(Request $request){
        try {
            $this->authorize('restore', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to restore sny Genre here!');
        }
        $restore = Genre::withTrashed()->find($request->id);
        if($restore->trashed()){
             $restore->restore();
             return ResponseController::success();
        }else{
            return ResponseController::error('Genre not found', 404);
        }
    }
}



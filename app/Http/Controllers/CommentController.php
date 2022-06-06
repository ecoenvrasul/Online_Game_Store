<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

// All good
class CommentController extends Controller
{
    public function create(Request $request){
        $product_id = $request->product_id ?? 0;
        $news_id = $request->news_id ?? 0;
        $validator = Validator::make($request->all(), [
            "title" => 'required|',
            "user_id" => 'required|exists:users,id',
            "product_id" => 'nullable|exists:products,id',
            "news_id" => 'nullable|exists:news,id'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $find = Comment::where('title', $request->title)
        ->get();
        $user = $request->user();
            Comment::create([
                'title' => $request->title,
                'user_id' => $user->id,
                'product_id' => $request->product_id ?? 0,
                'news_id' => $request->news_id ?? 0
            ]);
        return ResponseController::success();
    }
    public function showProductComments(Request $request){
        $comments = Comment::whereNotNull('product_id')
        ->where('status', 'unchecked')
        ->paginate(20);
        if(empty($comments)){
            return ResponseController::error('There is no comment on any Product');
        }
        return ResponseController::data($comments);
    }
    public function productComments(Product $product){
        if(count($product->comments) == 0){
            return ResponseController::error('No comment has been written on this product', 404);
        }
        $comments = $product->comments;
        $collection = [];
        foreach($comments as $comment){
            $collection[] = [
                "comment_id" => $comment->id,
                "product_id" => $comment->product_id,
                "user_id" => $comment->user_id,
                "status" => $comment->status
             ];
        }
        return $collection;
    }
    public function newsComments(News $news){
        $collection = [];
        $comments = $news->comments;
        return ResponseController::data($comments);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            "title" => 'required|string',
            "product_id" => 'nullable',
            "news_id" => 'nullable'
         ]);
         if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
         }
        $index = Comment::find($request->id);
        if(!$index){
            return ResponseController::error('No comment found', 404);
        }
        $index->update([
            'title' => $request->title
        ]);
        return ResponseController::success();
    }
    public function addPoint(Request $request){
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $user_comment = Comment::where('user_id', $user_id)->first();
        if($user_comment->status == "unchecked"){
            $point = $user->point;
            $user_comment->update([
                'status' => 'checked'
            ]);
            $user->update([
                'point' => $point+10
            ]);
            return ResponseController::success();
        }else{
            return ResponseController::error('The user has already checked!');
        }
    }
    public function delete(Request $request){
        $delete = Comment::find($request->id);
        if(!$delete){
            return ResponseController::error('No comment found to destroy', 404);
        }
        $delete->delete();
        return ResponseController::success();
    }
    public function history(Request $request){
        $comments = Comment::onlyTrashed()->get();
        return ResponseController::data($comments);
    }
    public function restore(Request $request){
        $comment = Comment::withTrashed()->find($request->comment_id);
        if($comment->trashed()){
            $comment->restore();
            return ResponseController::success();
        }else{
            return ResponseController::error('Commenet is not found', 404);
        }
    }
}




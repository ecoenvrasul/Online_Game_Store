<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Image;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// All good
class NewsController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', News::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create any News here!');
        }
        $validator = Validator::make($request->all(),[
            "image" => 'required|url',
            "title" => 'required|string',
            "text" => 'nullable',
            "body" => 'required'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $news = News::where('title', $request->title)->first();
        if($news){
            return ResponseController::error('This title is already exists') ;
        }
        $news = News::create([
            'image' => $request->image,
            'title' => $request->title,
            'text' => $request->text,
            'body' => $request->body,
        ]);
        return ResponseController::success();
    }
    public function show(Request $request){
        $news = News::orderBy('id', 'Desc')
        ->paginate(10);
        $collection = [
            "last_page" =>$news->lastPage(),
            "news" => [],
        ];
        foreach($news as $item){
            $collection['news'][] = [
                "news_id" => $item->id,
                "image" => $item->image,
                "title" => $item->title,
                "text" => $item->text
            ];
        }
        return ResponseController::data($collection);
    }
    public function singleNews(News $news){
        $news =[];
        $comments = $news->comments()->count();
        $news = [
            'news_title'=>$news->title,
            'news_image'=>$news->image,
            'news_body'=>$news->body,
            'created_at' =>$news->created_at,
            'comments'=>$comments
        ];
        return ResponseController::data($new);
    }
    public function comments(News $news){
        $comments = $news->comments;
        if(empty($comments)){
            return ResponseController::error('This news does not have nat comment yet', 404);
        }
        return ResponseController::data($comments);

    }
    public function update(Request $request){
        try {
            $this->authorize('update', News::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update any News here!');
        }
        $validator = Validator::make($request->all(),[
            "image" => 'required|url',
            "title" => 'required|string',
            "text" => 'nullable',
            "body" => 'required'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $update = News::find($request->id);
        if(!$update){
            return ResponseController::error('No news found!', 404);
        }
        $update->update([
            'title' => $request->title
        ]);
        return ResponseController::success();
    }
    public function delete(Request $request){
        // try {
        //     $this->authorize('delete', News::class);
        // } catch (\Throwable $th) {
        //     return ResponseController::error('You cannot delete any News here');
        // }
        $news = News::find($request->id);
        if(!$news){
            return ResponseController::error('No value!');
        }
        $news->delete();
        $news->comments()->delete();
        return ResponseController::success();
    }
    public function history(Request $request){
        try {
            $this->authorize('view', News::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are now allowed to see the history of News!');
        }
        $history = News::onlyTrashed()->get();
        if(count($history) == 0){
            return ResponseController::error('No file deleted');
        }
        return ResponseController::data($history);
    }
    public function restore(Request $request){
        try {
            $this->authorize('restore', News::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to restore any News here!');
        }
        $news = News::withTrashed()
        ->where('id', $request->news_id)
        ->restore();
        $comment = Comment::withTrashed()
        ->where('news_id', $request->news_id)
        ->restore();
        return ResponseController::success();
    }
}

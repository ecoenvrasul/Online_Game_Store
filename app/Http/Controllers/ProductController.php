<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\GenreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

// All of them are functioning as it is expected

class ProductController extends Controller
{
    // all good
    public function create(Request $request){
            try {
                $this->authorize('create', Product::class);
            } catch (\Throwable $th) {
                return ResponseController::error('You are not allowed to create a Product!');
            }
            $validator = Validator::make($request->all(), [
                'title' =>'required|unique:products,title|max:50',
                'title_img' => 'required|url',
                'rating' =>'required|numeric',
                'price' =>'required|numeric',
                'discount' =>'nullable|numeric',
                'current_price' => 'required|numeric',
                'about' =>'nullable|string',
                'minimal_system' =>'nullable|',
                'recommend_system' =>'nullable|',
                'warn' =>'required|boolean',
                'warn_text' =>'nullable|',
                'screenshots' =>'required|',
                'trailers' =>'required|',
                'language' =>'required|string|max:255',
                'active_location' =>'nullable|string|max:255',
                'genre' =>'required|exists:genres,id',
                'developer_id' =>'required|exists:developers,id',
                'publisher_id' =>'required|exists:publishers,id',
                'platform' =>'required|string',
                'producted' =>'required|'
            ]);
            if ($validator->fails()) {
                return ResponseController::error($validator->errors()->first(), 422);
            }
        $product = Product::where('title', $request->title)->first();
        //me
        $genre_ids = $request->genre;

        if($product){
            return ResponseController::error('This product is already Exists');
        }
        $product = Product::create([
            "title" => $request->title,
            "title_img" => $request->title_img,
            "rating" => $request->rating,
            "price" => $request->price,
            "discount" => $request->discount,
            "current_price" => $request->price - ($request->price*$request->discount)/100,
            "about" => $request->about,
            "minimal_system" => $request->minimal_system,
            "recommend_system" =>$request->recommend_system,
            "warn" =>$request->warn,
            "warn_text" => $request->warn_text,
            "screenshots" => $request->screenshots,
            "trailers" => $request->trailers,
            "language" => $request->language,
            "active_location" => $request->active_location,
            "genre" => $request->genre,
            "developer_id" => $request->developer_id,
            "publisher_id" => $request->publisher_id,
            "platform" => $request->platform,
            "producted" => $request->producted
        ]);
        foreach($genre_ids as $genre){
            GenreProduct::create([
                'genre_id' => $genre,
                'product_id' => $product->id
            ]);
        }
        return ResponseController::success();
    }
    public function viewAll(Product $product){
        $products = Product::paginate(20);
        if(count($products) == 0){
            return ResponseController::error('No product yet here!', 404);
        }
        $temp = [];
        $final = [];
        foreach($products as $product){
            $genre = $product->genre;
            $comments = $product->comments()->count();
            $temp  = [
                'product_id' => $product->id,
                'product_name' => $product->title,
                'developer_id' => $product->developer_id,
                'publisher_id' => $product->publisher_id,
                'comments' => $product->comments
            ];
            foreach($product->genre as $single){
                $genre = Genre::where('id', $single)->first(['id', 'name']);
                if($genre){
                    $temp['genre'][] = $genre;
                }
            }
            $final[] = $temp;
        }
        return ResponseController::data($final);
    }
    public function show(Request $request){
        $developer_id = $request->developer_id;
        $publisher_id = $request->publisher_id;
        $genre_id = $request->genre_id;

        $show = Product::when($developer_id, function($query) use($developer_id){
            return $query->where('developer_id', $developer_id);
        })->when($publisher_id, function($query) use($publisher_id){
            return $query->where('publisher_id', $publisher_id);
        })->when($genre_id, function($query) use($genre_id){
            return $query->where('genre_id', $genre_id);
        })->paginate(10);

        if(count($show) == 0){
            return ResponseController::error('There is no Product');
        }
        return ResponseController::data($show);
    }
    public function singleProduct(Product $product){
        return $product;
    }
    public function comments(Product $product){
        return $product->comments;
    }
    public function genre(Genre $genre){
        $genre_id = $genre->id;
        $product_ids = GenreProduct::where('genre_id', $genre_id)->get(['product_id']);
        // return $product_ids;
        $products = [];
        if(empty($product_ids)){
            return ResponseController::error('No Product there');
        }
        $list = [];
        foreach($product_ids as $item){
            $product = Product::where('id', $item['product_id'])->first();
            $comments = $product->comments()->count();
            $list = [
                "product_id" => $product->id,
                "product_title" => $product->title,
                "first_price" => $product->price,
                "developer_id" => $product->developer_id,
                'publisher_id' => $product->publisher_id,
                "comments" => $product->comments,
            ];
            $products[] = $list;
        }
        return ResponseController::data($products);
    }
    public function publisher(Publisher $publisher){
         $publisher_id = $publisher->id;
         $products = Product::where('publisher_id', $publisher_id)->get(['id']);
        if(!$products){
            return ResponseController::error('There is no product belongs to this publisher yet!');
        }
        $final = [];
        foreach($products as $item){
            $product = Product::where('id', $item['id'])->first();
            $comments = $product->comments()->count();
            $final[] = [
                'product_id' => $product->id,
                'product_name'=>$product->title,
                'product_price' => $product->price,
                'developer_id' => $product->developer_id,
                'genres' => $product->genre,
                'comments' => $product->comments
            ];
        }
         return $final;
    }
    public function developer(Developer $developer){
        $developer_id = $developer->id;
        $products = Product::where('developer_id', $developer_id)->get();
        // return $products;
        $allProducts = [];
        foreach($products as $product){
            $comments = $product->comments()->count();
            $allProducts[] = [
                'developer_name' =>$developer->name,
                'product_id' => $product->id,
                'product_name' => $product->title,
                'product_price' => $product->price,
                'publisher_id' => $product->publisher_id,
                'genre_id' => $product->genre,
                'comment' => $product->comments
            ];
        }
        return $allProducts;

    }
    public function update(Request $request){
        try {
            $this->authorize('create', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed!');
        }
        $update = Product::find($request->id);
        if(!$update){
            return ResponseController::error('No value found to update!', 404);
        }
        $update->update([
            'title' => $request->title
        ]);
        return ResponseController::success();
    }
    public function delete(Product $product){
        try {
            $this->authorize('create', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed!');
        }
        $product->delete();
        Comment::where('product_id', $product->id)
        ->delete();
        return ResponseController::success();
    }
    public function history(Request $request){
        $history = Product::onlyTrashed()->get(['id', 'title']);
        if(count($history) == 0){
            return ResponseController::error('No value is deleted!');
        }
        return ResponseController::data($history);
    }
    public function restore(Request $request){
        $product = Product::withTrashed()
        ->find($request->id);
        $comment = Comment::withTrashed()
        ->where('product_id', $request->id)
        ->first();
        if($product->trashed()){
            $product->restore();
            $comment->restore();
            return ResponseController::success();
        }else{
            return ResponseController::error('No deleted product found');
        }
    }
    public function newProduct(){
        $products = Product::latest()
        ->take(5)
        ->get([
            'id', 'title', 'title_img', 'price', 'discount', 'current_price'
        ]);
        return ResponseController::data($products);
    }
    public function orderByDiscount(){
        $products = Product::orderBy('discount', 'Desc')
        ->take(10)
        ->get([
            'id', 'title', 'discount', 'title_img', 'price', 'discount', 'current_price'
        ]);
        return ResponseController::data($products);
    }
    public function orderByRating(){
        $products = Product::orderBy('rating', 'Desc')
        ->take(10)
        ->get([
            'id', 'title', 'rating', 'title_img', 'price', 'discount', 'current_price'
        ]);
        return ResponseController::data($products);
    }
}

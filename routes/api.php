<?php

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\PromocodeController;
use App\Http\Controllers\PublisherController;


Route::post('/create', [AuthController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/employee', [AuthController::class, 'LoginEmployee']);



Route::middleware('auth:sanctum')->group(function(){
    Route::post('/getme', [AuthController::class, 'getme']);
    Route::post('/logout/user', [AuthController::class, 'logout']);
    Route::post('/employee/create', [AuthController::class, 'createEmployee']);
    Route::delete('/employee/delete', [AuthController::class, 'deleteEmployee']);
    Route::put('/employee/update', [AuthController::class, 'updateEmployee']);
    Route::get('/show/user', [AuthController::class, 'showUser']);
    Route::get('/show/ordered/users', [AuthController::class, 'orderByPoint']);
    Route::get('/show/employee', [AuthController::class, 'showEmployee']);

    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/product/all', [ProductController::class, 'viewAll']);
    Route::get('/product/show ', [ProductController::class, 'show']);
    Route::get('/product/{product} ', [ProductController::class, 'singleProduct']);
    Route::get('/product/comments/{product} ', [ProductController::class, 'comments']);
    Route::get('product/genre/{genre}', [ProductController::class, 'genre']);
    Route::get('product/publisher/{publisher}', [ProductController::class, 'publisher']);
    Route::get('product/developer/{developer}', [ProductController::class, 'developer']);
    Route::put('/product/update', [ProductController::class, 'update']);
    Route::delete('/product/delete/{product}', [ProductController::class, 'delete']);
    Route::get('/product/deleted/history', [ProductController::class, 'history']);
    Route::get('/product/restore/id', [ProductController::class, 'restore']);
    Route::get('/product/new/added', [ProductController::class, 'newProduct']);
    Route::get('/product/order/discount', [ProductController::class, 'orderByDiscount']);
    Route::get('/product/order/rating', [ProductController::class, 'orderByRating']);

    Route::post('/developer/create', [DeveloperController::class, 'create']);
    Route::get('/developer/show', [DeveloperController::class, 'show']);
    Route::put('/developer/update', [DeveloperController::class, 'update']);
    Route::delete('/developer/delete', [DeveloperController::class, 'delete']);
    Route::get('/developer/soft_deleted', [DeveloperController::class, 'history']);
    Route::get('/developer/restore', [DeveloperController::class, 'restore']);

    Route::post('/genre/create', [GenreController::class, 'create']);
    Route::get('/genre/show', [GenreController::class, 'show']);
    Route::get('/genre/{genre}', [GenreController::class, 'singleGenre']);
    Route::put('/genre/update', [GenreController::class, 'update']);
    Route::delete('/genre/delete', [GenreController::class, 'delete']);
    Route::get('/genre/database/history', [GenreController::class, 'history']);
    Route::get('/genre/database/restore', [GenreController::class, 'restore']);

    Route::post('/publisher/create', [PublisherController::class, 'create']);
    Route::get('/publisher/show', [PublisherController::class, 'show']);
    Route::put('/publisher/update', [PublisherController::class, 'update']);
    Route::delete('/publisher/delete/{publisher}', [PublisherController::class, 'delete']);
    Route::get('/publisher/soft-deleted', [PublisherController::class, 'history']);
    Route::get('/publisher/restore', [PublisherController::class, 'restore']);
    Route::get('/publisher/all/one', [PublisherController::class, 'all']);

    Route::post('/news/create', [NewsController::class, 'create']);
    Route::get('/news/show', [NewsController::class, 'show']);
    Route::get('/news/{news}', [NewsController::class, 'singleNews']);
    Route::get('/news/comments/{news}', [NewsController::class, 'comments']);
    Route::put('/news/update', [NewsController::class, 'update']);
    Route::delete('/news/delete', [NewsController::class, 'delete']);
    Route::get('/news/has/deleted', [NewsController::class, 'history']);
    Route::get('/news/soft/restore', [NewsController::class, 'restore']);

    Route::post('/comment/create', [CommentController::class, 'create']);
    Route::get('/comment/all/comments', [CommentController::class, 'showProductComments']);
    Route::get('/comment/product/{product}', [CommentController::class, 'productComments']);
    Route::get('/comment/news/{news}', [CommentController::class, 'newsComments']);
    Route::put('/comment/update', [CommentController::class, 'update']);
    Route::put('/comment/point', [CommentController::class, 'addPoint']);
    Route::delete('/comment/delete', [CommentController::class, 'delete']);
    Route::get('/comment/history', [CommentController::class, 'history']);
    Route::get('/comment/restore', [CommentController::class, 'restore']);

    Route::post('/image/upload', [ImageController::class, 'upload']);
    Route::delete('/image/delete', [ImageController::class, 'delete']);

    Route::post('/order/create', [OrderController::class, 'create']);

    Route::get('/basket/show', [BasketController::class, 'show']);
    Route::get('/basket/{basket}/orders', [BasketController::class, 'basketOrders']);
    Route::get('/basket/{basket}', [BasketController::class, 'countProducts']);
    Route::delete('/basket/{basket}', [BasketController::class, 'deleteBasket']);
    Route::delete('/basket/delete/{basket}', [BasketController::class, 'deleteSingleOrder']);
    Route::get('/basket/price/{basket}', [BasketController::class, 'price']);
    Route::get('/basket/user/{basket}', [BasketController::class, 'basketUser']);
    Route::get('/get/basket/{user}', [BasketController::class, 'userBasket']);
    Route::get('/basket/payment/promocode', [BasketController::class, 'payment']);

    Route::post('/favourite/create', [FavouriteController::class, 'create']);
    Route::delete('/favourite/delete', [FavouriteController::class, 'delete']);
    Route::get('/favourite/show/{user}', [FavouriteController::class, 'showUserProduct']);

    Route::post('/promocode/create', [PromocodeController::class, 'create']);
    Route::get('/promocode/show/all', [PromocodeController::class, 'showAllPromocodes']);
    Route::delete('/promocode/delete', [PromocodeController::class, 'delete']);
});

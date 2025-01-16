<?php

use App\Http\Controllers\Ads\AdsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Image\ImageController;
use App\Http\Controllers\Review\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->prefix('v1/ads')->group(function () {
    Route::get('/', [AdsController::class, 'showAllAd']);
    Route::get('/{id}', [AdsController::class, 'showSpecificAd']);
    Route::post('/new', [AdsController::class, 'createAds']);
    Route::put('/{id}', [AdsController::class, 'updateAd']);
    Route::delete('/{id}', [AdsController::class, 'deleteAd']);
});

Route::middleware(['auth:sanctum'])->prefix('v1/category')->group(function () {
    Route::get('/', [CategoryController::class, 'showAllCategory']);
    Route::post('/new', [CategoryController::class, 'createCategory']);
    Route::put('/{id}', [CategoryController::class, 'updateCategory']);
});

Route::middleware(['auth:sanctum'])->prefix('v1/image')->group(function () {
    Route::post('/', [ImageController::class, 'createImage']);
    Route::delete('/{id}', [ImageController::class, 'deleteImage']);
});

Route::middleware(['auth:sanctum'])->prefix('v1/review')->group(function () {
    Route::get('/{id}', [ReviewController::class, 'getAdReview']);
    Route::post('/{id}', [ReviewController::class, 'createReview']);
    Route::put('/{id}', [ReviewController::class, 'updateReview']);
});

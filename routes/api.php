<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BrandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);

Route::get('/comments/{id}', [CommentController::class, 'findComments']);
Route::post('/comments/{id}', [CommentController::class, 'postComment']);



/**
 * BRANDS ROUTES
 */

Route::get('/brands', [BrandController::class, 'index']);       // GET all brands
Route::post('/brands', [BrandController::class, 'store']);      // POST create brand
Route::get('/brands/{id}', [BrandController::class, 'show']);   // GET a specific brand
Route::put('/brands/{id}', [BrandController::class, 'update']); // PUT update brand
Route::delete('/brands/{id}', [BrandController::class, 'destroy']); // DELETE brand

<?php

use Illuminate\Http\Request;

use App\Http\Controllers\ConcessionaireController;
use App\Http\Controllers\CharactersController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\cicarController;
use Illuminate\Support\Facades\Route;

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
Route::post('/cars/new', [CarController::class, 'store']);
Route::put('/cars/update/{id}', [CarController::class, 'update']);
Route::get('/concessionaire/{id}/comments', [CarController::class, 'findConcessionaireComments']);


/**
 ** COMMNENTS ROUTES
 */
Route::get('/comments/{id}', [CommentController::class, 'findComments']);
Route::post('/comments/new', [CommentController::class, 'store']);


/**
 ** CONCESSIONAIRES ROUTES
 */
 Route::get('/concessionaires', [ConcessionaireController::class, 'index']);
 Route::get('/concessionaire/{id}', [ConcessionaireController::class, 'find']);

/**
 ** BRANDS ROUTES
 */
Route::get('/brands', [BrandController::class, 'index']);       // GET all brands
Route::post('/brands', [BrandController::class, 'store']);      // POST create brand
Route::get('/brands/{id}', [BrandController::class, 'show']);   // GET a specific brand
Route::put('/brands/{id}', [BrandController::class, 'update']); // PUT update brand
Route::delete('/brands/{id}', [BrandController::class, 'destroy']); // DELETE brand


/**
 ** ROUTES TO EXTERNAL API ROUTES
 */

Route::get('/characters/getall', [CharactersController::class, 'getAllCharacters']);
Route::get('/characters/filtered', [CharactersController::class, 'getFilteredCharacters']);



Route::get('/cicar', [CicarController::class, 'obtenerListaDeZonas']);

<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::resource('/v1/burgers', v1\BurgerController::class, [
    'except' => ['create', 'edit']
]);

Route::resource('/v1/ingredients', v1\IngredientController::class, [
    'except' => ['create', 'edit']
]);

Route::resource('/v1/groups', v1\GroupController::class, [
    'except' => ['create', 'edit']
]);
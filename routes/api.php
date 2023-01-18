<?php

use App\Http\Api\Controllers\ChatController;
use App\Http\Api\Controllers\MessageController;
use Illuminate\Http\Request;
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

Route::group([
    'prefix' => 'chat'
], function (){
    Route::post('/{chat}/message', [MessageController::class, 'add']);
    Route::delete('/{chat}/message', [MessageController::class, 'delete']);
    Route::get('/{chat}/message/list', [ChatController::class, 'getMessagesList']);
    Route::get('/list', [ChatController::class, 'getList']);
});


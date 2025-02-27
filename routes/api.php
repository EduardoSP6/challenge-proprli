<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
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

Route::get('status', function () {
    return [
        'DATE' => date("y-m-d H:i:s"),
        'APP_NAME' => config('app.name'),
        'APP_ENV' => config('app.env'),
        'APP_URL' => config('app.url'),
    ];
});

Route::apiResource('tasks', TaskController::class)->only(['index', 'store']);
Route::apiResource('tasks.comments', CommentController::class)->only(['store']);

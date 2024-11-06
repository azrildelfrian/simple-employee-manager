<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiEmployeeController;

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

Route::get('employees', [ApiEmployeeController::class, 'index']);
Route::post('employees', [ApiEmployeeController::class, 'store']);
Route::get('employees/{id}', [ApiEmployeeController::class, 'show']);
Route::put('employees/{id}', [ApiEmployeeController::class, 'update']);
Route::delete('employees/{id}', [ApiEmployeeController::class, 'destroy']);
// Route::delete('employees/batch', [ApiEmployeeController::class, 'batchDelete']);
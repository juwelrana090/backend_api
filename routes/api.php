<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ReportController;

Route::group(['middleware' => ['api']], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::group( ['prefix' => 'report'],  function () 
    {
        Route::get('/', [ReportController::class, 'index']);  
        Route::get('/search', [ReportController::class, 'search']);
        Route::get('/date-range', [ReportController::class, 'date_range']);
        Route::post('/create', [ReportController::class, 'create']); 
        Route::get('/{id}', [ReportController::class, 'detail']);
        Route::put('/update', [ReportController::class, 'update']);
        Route::delete('delete/{id}', [ReportController::class, 'delete']); 
    });
});

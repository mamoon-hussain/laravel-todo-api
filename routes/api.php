<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;

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




//Route::group(['prefix' => 'api'], function () {
//    Route::group(['prefix' => 'task'], function () {
//        Route::get('index', 'TaskController@index');
//    });
//});


Route::group(['prefix' => 'task'], function () {
    Route::get('index', [TaskController::class, 'index']);
    Route::get('details', [TaskController::class, 'details']);
    Route::post('create', [TaskController::class, 'create']);
    Route::put('update', [TaskController::class, 'update']);
    Route::delete('delete', [TaskController::class, 'delete']);
});




use Illuminate\Support\Facades\Hash;
Route::get('test',function(){
    $number=12345678;
    $number1=12345678;
    $n1=Hash::make ($number1);
    $n2=Hash::make ($number);
    // return "$n1 <br> $number <br> $n2 <br> $number1";
    if(Hash::check($number1,$n1))
    {
    return Hash::check($number1,$n1);
    }
    else{
        return " ya hamdi";
    }
});


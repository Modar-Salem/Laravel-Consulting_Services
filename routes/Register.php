<?php

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
route::post('SignUp' , [\App\Http\Controllers\User::class, 'SignUp']) ;

route::post('LogIn' , [\App\Http\Controllers\User::class, 'LogIn']) ;

route::get('LogOut' , [\App\Http\Controllers\User::class , 'LogOut'])->middleware('auth:sanctum');


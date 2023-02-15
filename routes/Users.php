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

    route::Get('MyInfo' , [\App\Http\Controllers\User::class, 'index']) ;

    route::post('Search' , [\App\Http\Controllers\User::class , 'Search']) ;

    route::post('InsertImage' , [\App\Http\Controllers\User::class , 'insert_image']) ;

    route::post('CategoryExperts',[\App\Http\Controllers\User::class , 'expert_browse']);

    route::get('GetUser/{id}' , [ \App\Http\Controllers\User::class, 'GetUser'] ) ;



    route::post('favorite' , [\App\Http\Controllers\Favorite::class , 'Favorite']);

    route::get('MyFavorite' , [\App\Http\Controllers\Favorite::class , 'Get_The_Favorite']);

    route::post('rates/{id}' , [\App\Http\Controllers\Rate::class , 'rate']);

    route::get('myRate/{id}' , [\App\Http\Controllers\Rate::class , 'Get_Rate']);

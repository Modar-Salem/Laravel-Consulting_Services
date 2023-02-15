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

Route::middleware('auth:sanctum')->group(function(){

    route::get('sent_counseling' , [\App\Http\Controllers\ConsultingController::class , 'sent_counseling']) ;

    route::get('Booked_appointments' , [\App\Http\Controllers\ConsultingController::class,  'Booked_appointments']) ;

    route::post('Appointment_Booking' , [\App\Http\Controllers\ConsultingController::class , 'Appointment_Booking']) ;

    route::post('response' , [\App\Http\Controllers\ConsultingController::class , 'Response']) ;
});


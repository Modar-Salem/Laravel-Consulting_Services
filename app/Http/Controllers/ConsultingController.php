<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConsultingController extends Controller
{



    public function sent_counseling()
    {
        try
        {
            $user_id= Auth::id() ;

            $sent_counseling = \App\Models\User::find($user_id)->Sender_Consulting() ;

            return response()->json([
                'Status' => true ,
                'Sent_Counseling' => $sent_counseling->get()
            ]) ;
        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }
    }




    public function Booked_appointments(Request $request)
    {
        try {

            $expert_id = null;
            if (Auth::user()->role == 'expert')
                $expert_id = auth::id();

            else
                return response()->json([
                    'Status' => false,
                    'Message' => 'For expert only'
                ]);

            $Booked_appointments = \App\Models\User::find($expert_id)->Incoming_Consulting()
                                            ->where('response', null) ;
            return response()->json([
                'Status' => true,
                'Booked_appointments' => $Booked_appointments->get()
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }

    }




    public function Appointment_Booking(Request $request)
    {
        try {
            $user_id = Auth::id();
            $validate = Validator::make($request->all(),
                [
                    'expert_id' => 'Required ',

//                    'day' => 'Required  ',

                    'time'  => 'required' ,

                    'counseling' => 'Required | string'
                ]);

            if ($validate->fails()) {
                return response()->json([
                    'Status' => false,
                    'Message' => $validate->errors()
                ]);
            }

            $user = \App\Models\User::find($user_id);
            $expert = \App\Models\User::find($request['expert_id'])->expert_information;

            if ($user->balance < $expert->fee) {
                return response()->json([
                    'Status' => false,
                    'Message' => 'You do not have enough balance'
                ]);
            }

            $user->balance = $user->balance - $expert->fee;
            $expert->balance = $expert->balance + $expert->fee;

            $Appointment_Booking = Consulting::create([
                'user_id' => $user_id,
                'expert_id' => $request['expert_id'],
                'day' =>Carbon::now()->format('Y-m-d'),
                'time' => $request['time'] ,
                'counseling' => $request['counseling']
            ]);

            return response()->json([
                'Status' => true,
                'Message' => 'Successful'
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ], 401);
        }
    }


    public function Response(Request $request)
    {
        try
        {
            $expert_id = Auth::id() ;
            $validate = Validator::make($request->all() , [
                    'user_id' => 'Required' ,
                    'response' => 'string'
            ]) ;
            if($validate->fails()){
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]);
            }

            $counseling = Consulting::where('user_id' , $request['user_id'])->where('expert_id' , $expert_id) ;
            $counseling->update(['response' => $request['response']]) ;

            return response()->json([
                'Status' => true ,
                'counseling' => $counseling->get()
            ]) ;
        } catch (\Exception $exception) {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ], 401);
        }
    }


}

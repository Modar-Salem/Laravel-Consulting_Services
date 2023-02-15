<?php

namespace App\Http\Controllers;

use App\Models\Rate as ModelsRate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Rate extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }


    public function rate(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all() ,
                [
                    'rate' => 'Required | min :1 | max :5 |integer ',
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }

            $user_id = Auth::id() ;
            \App\Models\Rate::updateOrCreate([
                'user_id'=> $user_id
            ],
                [
                    'rate' => $request['rate'] ,
                    'expert_id' => $request['expert_id']
                ]) ;

            return response()->json([
                'status' => true ,
                'Message' => 'Rated Successfully'
            ]) ;


        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ], 401) ;
        }


    }


    /**
     * give a rate to the product
     *
     * @param int $expert_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Get_Rate(int $expert_id){
        try
        {
            $count = Rate::where('expert_id' , '=', $expert_id ) -> count() ;
            $sum = Rate::where('expert_id' , '=', $expert_id )-> sum('rate') ;

            return response()->json([
                'Status' => true ,
                'Rate' => $sum/$count
            ]) ;


        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ], 401) ;

        }
    }


}

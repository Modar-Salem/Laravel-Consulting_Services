<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use App\Models\expert_information;
use App\Models\Favorite as ModelsFavorite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Favorite extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Add the specified resource to favorite.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Favorite(int $expert_id)
    {
        try
        {
            $user_id = Auth::id() ;

            $favorite = \App\Models\Favorite::where('user_id' , $user_id)->where('expert_id'  ,  $expert_id);


            if ($favorite->first())
            {
                $favorite->delete() ;
                return response()->json([
                    'Status'=>true ,
                    'Message' => 'Removed Successfully'
                ]) ;

            }
            else
            {
                $expert = \App\Models\User::find($expert_id) ;

                if($expert) {
                    $user_id = Auth::id();
                    \App\Models\Favorite::create([
                        'expert_id' => $expert_id,
                        'user_id' => $user_id
                    ]);

                    return response()->json([
                        'Status' => true,
                        'Message' => 'Added Successfully'
                    ]);
                }
                else
                {
                    return response()->json([
                        'Status' => false ,
                        'Message' => 'Expert Not Found'
                    ]) ;
                }
            }
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status'=>false ,
                'Message'=>$exception->getMessage()
            ]) ;
        }
    }


    /**
     * Add the specified resource to favorite.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Get_The_Favorite()
    {
        try
        {
            $user_id = Auth::id() ;
            $favorite = \App\Models\Favorite::where('user_id' , $user_id) ;

            return response()->json([
                'Status' => true ,
                'user_favorite' => $favorite->get()
            ]) ;

        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status'=>false ,
                'Message'=>$exception->getMessage()
            ]) ;
        }

    }
}

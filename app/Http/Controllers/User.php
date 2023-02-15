<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\expert_information;
use App\Models\Rate;
use App\Models\Work_times;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        try
        {
            $id = Auth::id();
            $user = \App\Models\User::find($id);

            if ($user)
            {
                if($user->role == 'expert')
                {
                 $expert = $user->expert_information()->get() ;

                return response()->json([
                    "Status" => true,
                    "User" =>  $user,
                    "Expert" => $expert
                ]);
                }
                else
                {
                    return response()->json([
                        "Status" => true,
                        "User" => $user ,
                    ]);
                }

            }

            else
                return response()->json([
                    "Status" => false,
                    "Message" => "User Not found"
                ]);
        }catch (\Exception $exception)
        {
            return response()->json([
                'Status'=>false ,
                'Message'=>$exception->getMessage()
            ]) ;
        }
    }

    /**
     * Get Any User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetUser($user_id)
    {
        $user = \App\Models\User::find($user_id);
        if($user)
        {
            if($user['role']=='expert')
                return response()->json([
                    'Status' => true ,
                    'User'=> $user ,
                    'Expert' => $user->expert_information()->get()
                    ,'Days' => $user->work_times()->get()
                ]) ;
            else
                return response()->json([
                    'Status' => true ,
                    'User' => $user
                ]) ;
        }else
        {
            return response()->json([
                'Status' => true ,
                'Message' => 'User Not Found'
            ]);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SignUp(Request $request)
    {
        $expert_information = null ;
        //validate from Request-Error
        try
        {

            $validate = Validator::make($request->all() , [
                'name' => 'required | string | min:5 | max :34',

                'email' => 'required | email',

                'password'  => 'required | string | min : 8 | max:34 ' ,

                'role' => 'required' ,

                'gender' => 'required'
            ]);

            if ($validate->fails())
                return response()->json([
                    'Status' => false ,
                    'Validation Error' => $validate->errors()
                ],401) ;
            if ($request['role'] == 'expert')
            {
                $validate = Validator::make($request->all() , [

                    'experience' => 'required |string | min: 5 | max :300',

                    'consulting_type' => 'required' ,

                    'fee' => 'required |integer' ,


                    'phone' => 'required | string | numeric ',

                    'address' => 'string' ,

                ]) ;
                if ($validate->fails())
                    return response()->json([
                        'Status' => false ,
                        'Validation Error' => $validate->errors()
                    ],401) ;

            }

        } catch (\Exception $exception)
        {
                return response()->json([
                    'Status' => false,
                    'Message' => $exception->getMessage()
                ]);
        }


        //create user AND create token
        try
        {
            $User = \App\Models\User::create([
                'name' => $request['name'],

                'email' => $request['email'],

                'password' => \Illuminate\Support\Facades\Hash::make($request['password']),

                'gender' => $request['gender'] ,

                'role' => $request['role'] ,

            ]) ;

            if($request['role']=='expert')
            {

                try
                {
                    $expert_information = expert_information::create([
                        'expert_id' => $User['id'],

                        'experience' => $request['experience'],

                        'consulting_type' => $request['consulting_type'],

                        'phone' => $request['phone'] ,

                        'address' => $request['address'] ,

                        'fee' => $request['fee'],

                    ]);


                    $day = Work_times::create([
                        'expert_id' => $User['id'] ,

                        'sunday' => $request['sunday'] [0] ,
                        'begin_time1' => $request['sunday'] [1] ,
                        'end_time1' => $request['sunday'] [2] ,


                        'monday' => $request['monday'] [0] ,
                        'begin_time2' => $request['monday'] [1] ,
                        'end_time2' => $request['monday'] [2] ,



                        'tuesday' => $request['tuesday'] [0] ,
                        'begin_time3' => $request['tuesday'] [1] ,
                        'end_time3' => $request['tuesday'] [2] ,


                        'wednesday' => $request['wednesday'] [0] ,
                        'begin_time4' => $request['wednesday'] [1] ,
                        'end_time4' => $request['wednesday'] [2] ,



                        'thursday' => $request['thursday'] [0] ,
                        'begin_time5' => $request['thursday'] [1] ,
                        'end_time5' => $request['thursday'] [2] ,


                        'friday' => $request['friday'] [0] ,
                        'begin_time6' => $request['friday'] [1] ,
                        'end_time6' => $request['friday'] [2] ,

                        'saturday' => $request['saturday'] [0] ,
                        'begin_time7' => $request['saturday'] [1] ,
                        'end_time7' => $request['saturday'] [2] ,

                    ]) ;

                }catch (\Exception $exception)
                {


                    return response()->json([
                        'Status'=> false ,
                        'Error in create the expert_information' => $exception->getMessage()
                    ]);
                }
            }
            //create Token
            try
            {
                $token = $User->createToken('API TOKEN')->plainTextToken ;
            }
            catch (\Throwable $Th)
            {
                return response() -> json([
                    'Status' => false  ,
                    'Error in Create the Token' => $Th->getMessage() ,
                ] ,  500) ;
            }


        }
        catch (\Throwable $Th)
        {
            return response() -> json([
                'Status' => false  ,
                'Error in Create the User' => $Th->getMessage() ,
            ] ,  500) ;
        }

        if($request['role']=='expert')
        //Success
            return response() -> json([
                'Status' => true ,
                'User' => $User ,
                'Expert' =>$expert_information ,
                'Token' => $token
            ], 201) ;

        else
            //Success
            return response() -> json([
                'Status' => true ,
                'User' => $User ,
                'Token' => $token
            ], 201) ;

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Search(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all() ,
                [
                    'consultant_name' => 'Required | string | max :34'
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]);
            }

            $expert_information = array() ;
            $Result = \App\Models\User::where('name', 'like', '%' . $request['consultant_name'] . '%')->where('role' , 'expert') ;
            foreach ($Result->get() as $res)
            {
                $expert_information[] = expert_information::where('expert_id' , $res['id'])->get() ;
            }

            if ($Result->first()) {
                return response()->json([
                    'Status' => true,
                    'Result' => $res = \App\Models\User::where('name', 'like', '%' . $request['consultant_name'] . '%')->where('role' , 'expert')->get() ,
                    'Expert Information' =>$expert_information
                ]);
            }
            else
            {
                return response()->json([
                    'Status' => false,
                    'Message' =>'Not Found'
                ]);
            }




        }
        catch (\Exception $exception){

            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ], 401) ;
        }

    }

    public function insert_image(Request $request)
    {
        $user_id = Auth::id() ;

        $validate = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png,gif | required']);

        if ($validate->fails()) {
            return response()->json([
                'Status' => false,
                'Message' => $validate->errors()
            ]);
        }
        if ($request->hasFile('image')) {
            try
            {
                $path = Null;
                $image = $request->file('image');

                //Get FileName with extension
                $filenameWithExt = $image->getClientOriginalName();

                //Get FileName without Extension
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                //Get Extension
                $Extension = $image->getClientOriginalExtension();

                //New_File_Name
                $NewfileName = $filename . '_' . time() . '_.' . $Extension;

                //Upload Image
                $path = $image->storeAs('images', $NewfileName);


                //create Object in Database
                $user= \App\Models\User::find($user_id)
                    ->update(
                        ['image' => URL::asset('storage/' . $path)]
                    );
                if($user)
                    return response()->json([
                       'Status' => true ,
                       'Message' => 'Image are inserted Successfully' ,
                    ]) ;

            } catch(\Exception $exception)
            {

                    return response()->json([
                        'Status' => false,
                        'Message' => $exception->getMessage()
                    ], 401);
            }
        }

    }


    public function charge(Request $request)
    {
        $user = \App\Models\User::find($request['user_id']) ;
        $user->balance = $user->balance + $request['cash'] ;
        $user->update() ;
        return response()->json([
            'Status' => true ,
            'cash' => $request['cash'] ,
            'User' => $user
        ]) ;
    }

    /**
     * LogIn .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function LogIn(Request $request) {

        try
        {
                $validate = Validator::make($request->all() , [
                    'email' => 'required | email',

                    'password'  => 'required | string | min : 8 | max:34 ' ,
                ])  ;

                if ($validate->fails())
                    return response()->json([
                        'Status' => false ,
                        'Validation Error' => $validate->errors()
                    ],401) ;


                if (!Auth::attempt($request->only('email' , 'password' )))
                    return response()->json([
                        'Status' => false ,
                        'Message' => 'Invalid Data'
                    ]);

                else
                {

                    $User = \App\Models\User::where('email' , $request['email'])->first() ;
                    $token = $User->createToken('API TOKEN')->plainTextToken ;

                    return response() ->json([
                        'Status'=> true ,
                        'User' => $User,
                        'Token' => $token ,
                    ], 201) ;

                }

        }
        catch (\Throwable $Th)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $Th->getMessage()
            ],500) ;
        }
    }

    /**
     * Logout .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function LogOut()
    {
        try
        {

            Auth::user()->tokens->each(function ($token){
                $token->delete() ;
            }) ;
            return response()->json([
                'Status' => true ,
                'Message' => 'LogOut Successfully'
            ]) ;

        }
        catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
        }

    }


    public function expert_browse(Request $request)
    {

        $user = array() ;
        $experts = \App\Models\expert_information::where('consulting_type'  , $request['consulting_type']) ;

        foreach ($experts->get() as $expert)
        {
            $user[] = \App\Models\User::where('id' , $expert['expert_id'])->get() ;
        }

        if($experts->first())
            return response()->json([
                'Status'=> 'true' ,
                'Experts' => $user,
                'Expert_Information' =>  \App\Models\expert_information::where('consulting_type'  , $request['consulting_type'])->get()
            ]);

        else
            return response()->json([
                'Status'=>false ,
                'Message' => 'Not Found'
            ]) ;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function rate(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all() ,
                [
                    'rate' => 'Required | min :1 | max :5 |integer ',
                    'expert_id' => 'required'
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }

            $user_id = Auth::id() ;

            Rate::updateOrCreate([
                'user_id'=> $user_id
            ],
                [
                    'rate' => $request['rate'] ,
                    'expert_id' => $request['product_id']
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

    public function get_rate ($expert_id)
    {
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

    public function Add_To_Favorite($expert_id)
    {
        try
        {
            $user_id = Auth::id() ;

            $favorite = \App\Models\Favorite::where('user_id' , $user_id)->where('expert_id' , '=' ,  $expert_id);


            if ($favorite->first())
            {
                $favorite->delete() ;
                return response()->json([
                    'Status'=>true ,
                    'Message' => 'product un favorite'
                ]) ;

            }
            else
            {
                $product = \App\Models\Product::find($product_id) ;

                if($product) {
                    $user_id = Auth::id();
                    \App\Models\Favorite::create([
                        'product_id' => $product_id,
                        'user_id' => $user_id
                    ]);

                    return response()->json([
                        'Status' => true,
                        'Message' => 'product favorite'
                    ]);
                }
                else
                {
                    return response()->json([
                        'Status' => false ,
                        'Message' => 'Product Not Found'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}

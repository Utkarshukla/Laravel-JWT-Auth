<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function register(Request $request){
        $val = Validator::make($request->all(),[
            'name'=>'required|string|min:2|max:100',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:6|confirmed'
        ]);
        if($val->fails()){
            return response()->json($val->errors());
        }else{
            $user= User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            return response()->json([
                'success'=>true,
                'msg'=>'successfully Registered on Database',
                'user'=>$user,
            ]);
        }
    }

    public function login(Request $request){
        $val = Validator::make($request->all(),[
            'email'=>'required|string|email|max:100',
            'password'=>'required|string|min:6'
        ]);
        if($val->fails()){
            return response()->json($val->errors());
        }
        if(!$token = auth()->attempt($val->validated())){
            return response()->json([
                'success'=>false,
                'msg'=>'Username and password not found',    
            ]);   
        }
       return $this->respondWithToken($token);

    }
    protected function respondWithToken($token){
        return response()->json([
            'success'=>true,
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>JWTAuth::factory()->getTTL()*60,

        ]);
    }
    public function logout(){
        try {
            auth()->logout();
            return response()->json([
                'success'=>true,
                'msg'=>'User loggedout'
    
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success'=>false,
                'msg'=>$th->getMessage()
    
            ]);
        }
        
        
    }
    public function profile(){
        try {
            return response()->json([
                'success'=>true,
                'data'=>auth()->user()
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success'=>false,
                'msg'=>$th->getMessage()
            ]);
        }
    }
    public function updateProfile(Request $request){
        if (auth()->user()) {
            $val = Validator::make($request->all(),[
                'id'=>'required',
                'name'=>'required|string|min:2|max:100',
                'email'=>'required|string|email',
            ]);
            if($val->fails()){
                return response()->json($val->errors());
            }
            $user =User::find($request->id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->save();
            return response()->json([
                'success'=>true,
                'msg'=>'Updated Successfully',
                'data'=>$user
            ]);
        } else {
            return response()->json([
                'success'=>false,
                'msg'=>'User is not Authenticated.'
            ]);
        }
        
    }

    public function sendVerifyMail($email){
        if(auth()->user()){
            $user = User::where('email',$email)->get();
            if (count($user)>0) {
                
                $random =Str::random(40);
                $domain=URL::to('/');
                $url = $domain.'/verify-mail/'.$random;

                $data['url']=$url;
                $data['email'] = $email;
                $data['title'] ='Email verification';
                $data['body'] ='Please click here to verify Your Email.';
                Mail::send('verifyMail',['data'=>$data],function($message) use ($data){
                    $message ->to($data['email'])->subject($data['title']);
                });
                $user = User::find($user[0]['id']);
                $user->remember_token = $random;
                $user->save();
                return response()->json([
                    'success'=>true,
                    'msg'=> 'Mail Sent Successfully',
                ]);

            } else {
                return response()->json([
                    'success'=>false,
                    'msg'=> 'User in not found!',
                ]);
            }
            
        } else{
            return response()->json([
                'success'=>false,
                'msg'=> 'User in not Authenticated',
            ]);
        }
    }
    public function verificationMail($token){
        $user =User::where('remember_token',$token)->get();
        if (count($user)>0) {
            $datetime =Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($user[0]['id']);
            $user->remember_token='';
            $user->email_verified_at = $datetime;
            $user->save();
            return "<h1 style='color:green'>Email verified Successfully</h1>";
        } else {
            return view('404');
        }
        
    }
}

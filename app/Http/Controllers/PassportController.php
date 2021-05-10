<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;

class PassportController extends Controller
{
    public function register(StorePassportRequest $request){
        $this->validate($request,[
            'name'=> "required|min:3",
            'email'=> "required|email|unique:users",
            'password'=>"required|min:6",

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('Successful')->accessToken;
        return response()->json(['token' => $token], 200);
    }

    public function login(StorePassportRequest $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(auth()->attempt($credentials)){
            $token = auth()->user()->createToken('Sucessful')->accessToken;
            return response()->json(['token'=> $token],200);
        }else{
            return response()->json(['error'=> 'UnAuthorised'],401);
        }
    }

    public function details(){
        return response()->json(['user'=> auth()->user()],200);
    }

}


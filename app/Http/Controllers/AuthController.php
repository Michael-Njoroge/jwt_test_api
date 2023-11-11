<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['login','register']]);
    }
 
    /**
     * Register User
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        if($validator->fails()){
            return response() -> json($validator->errors());
        }

        $user = User::create([
            'name' => $request -> name,
            'email' => $request->email,
            'password' => Hash::make($request->password)       
        ]);
        

         $token = auth()->attempt($request->only('email', 'password'));

         $cookie = Cookie::make('access_token', $token, 60);

         return response() -> json([
            'status' => 'success',
            'access_token' => $token,
            'expires_in' => Auth::factory() -> getTTl() * 60,
            'user' => $user
         ])->withCookie($cookie);
    }


    /**
     * Login User
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed'
        ]);

        if($validator->fails()){
            return response() -> json($validator->errors());
        }

        $token = auth() -> attempt($request -> only('email','password'));

        if(!$token){
            return response()->json([
                'status' => 'false',
                'Errors' => 'Unauthorized'
            ],401);
         }

         $cookie = Cookie::make('access_token', $token, 60);

         return response() -> json([
            'status' => 'success',
            'access_token' => $token,
            'expires_in' => Auth::factory() -> getTTl() * 60,
            'user' => auth() -> user()
         ]) -> withCookie($cookie);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function me()
    {
       return response() -> json(auth() -> user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    protected function respondWithtoken($token){
        return response() -> json([
            'user' => auth()->user(),
            'access_token' => $token
        ]);
    }
}

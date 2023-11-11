<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
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
        

         auth()->attempt($request->only('email', 'password'));

         return response() -> json([
            'message' => 'User registered successfully',
            'user' => $user
         ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
}

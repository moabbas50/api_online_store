<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $token = $user->createToken("myToken")->plainTextToken;
        $response = [
            'message' => "welcome ",
            'user' => $user,
            'token' => $token
        ];
        return response($response, 200);
    }


    public function loginUser(Request $request)
    {
        $data = $request->validate([

            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', '=', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            $response = [
                'message' => "incorect email or password"
            ];
        } else {
            $token = $user->createToken("myToken")->plainTextToken;
            $response = [
                'message' => "welcome ",
                'user' => $user,
                'token' => $token
            ];
        };
        return response($response, 200);
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////
    // public function registerAdmin(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|unique:users,email',
    //         'password' => 'required|confirmed'
    //     ]);
    //     $user = Admins::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => bcrypt($data['password']),
    //     ]);
    //     $token = $user->createToken("myToken")->plainTextToken;
    //     $response = [
    //         'message' => "welcome ",
    //         'user' => $user,
    //         'token' => $token
    //     ];
    //     return response($response, 200);
    // }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function loginAdmin(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $admin = Admins::where('email', '=', $data['email'])->first();
        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            $response = [
                'message' => "incorect email or password"
            ];
        } else {
            $token = $admin->createToken("myToken")->plainTextToken;
            $response = [
                'message' => "welcome ",
                'admin' => $admin,
                'token' => $token
            ];
        };
        return response($response, 200);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        $response = [
            'message' => "logout successfully"
        ];
        return response($response, 200);
    }
}

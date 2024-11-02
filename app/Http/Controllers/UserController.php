<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(User $User)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $userr = auth()->user();
        $user = User::where('email', '=', $userr->email)->first();
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'address' => 'required|string',
            'phone_number' => 'required|string|regex:/^(\+?\d{1,4}[-.\s]?)?(\(?\d{1,4}\)?[-.\s]?)?[\d-.\s]{7,}$/'
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->save();
        $response = [
            'message' => 'your information updated',
            'status' => '201',
            'data' => $user
        ];
        return response($response, 200);
    }
    public function rest_password(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email',
            'oldpassword' => 'required',
            'newpassword' => 'required|confirmed'
        ]);
        $admin = User::where('email', $request->email && 'password', $request->oldpassword)->first();
        if (!$admin || !Hash::check($request->oldpassword, $admin->password)) {
            $response = [
                'message' => "incorect email or oldpassword "
            ];
        } else {
            $admin->password = bcrypt($request->newpassword);
            $admin->update();
            $response = [
                'message' => "reset password successfully ",
            ];
        };
        return response($response, 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $User)
    {
        //
    }
}

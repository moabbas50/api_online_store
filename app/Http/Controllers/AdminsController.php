<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admins::all();
        if ($admins->isEmpty()) {
            $response = [
                'message' => "NO Recoreds Found",
                'status' => "200"
            ];
        } else {
            $response = [
                'message' => "Get all admins Successfully",
                'status' => "200",
                'data' => $admins
            ];
        };
        return response($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);
        $admin = Admins::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $response = [
            'message' => "new admin created",
            'user' => $admin,
        ];
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Admins $admins)
    {
        //
    }

    /* 
 Rest Password
*/
    public function rest_password(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email',
            'oldpassword' => 'required',
            'newpassword' => 'required|confirmed'
        ]);
        $admin = Admins::where('email', $request->email && 'password', $request->oldpassword)->first();
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admins $admins)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admins $admins)
    {
        //
    }
}

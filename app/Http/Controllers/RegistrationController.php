<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:15',
            'lastname' => 'required|max:15',
            'username' => 'required|unique:users,username|min:5|max:15',
            'password' => 'required|min:5'
        ]);

        $user = User::where('username',$request->username)->first();
        if ($user) {
            return redirect()->back();
        }

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('login')->with('success-registration', 'Your account is created! Please login');
    }
}

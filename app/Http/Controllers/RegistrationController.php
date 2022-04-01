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
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username',$request->username)->first();
        if ($user) {
            session()->flash('message', 'Username already exist');
            return redirect()->back();
        }

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $user->save();

        session()->flash('message', 'Your account is created');

        return redirect()->route('login');
    }
}

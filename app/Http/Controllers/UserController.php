<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Show Register/Create form
    public function create(){
        
        return view('users.register');
    }
    //Create new user
    public function store(Request $request){
        $formFiels = $request->validate([
            'name' => ['required','min:3'],
            'email' => ['required','email', Rule::unique('users','email')],
            'password' => 'required|confirmed|min:6'
        ]);
        
        // Hash password
        $formFiels['password'] = bcrypt($formFiels['password']);
        
        //Create user
        $user = User::create($formFiels);

        //Login
        auth()->login($user);

        return redirect('/')->with('message','user created succesfully!!');
    }

    //Logout user
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message','You have been logout');
    }

    //Show Login form
    public function login(){
        return view('users.login');
    }

    //Authenticate user
    public function authenticate(Request $request){
        $formFiels = $request->validate([
            'email' => ['required','email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFiels)){
            $request->session()->regenerate();

            return redirect('/')->with('message','You are now logged in!');
        }
        return back()->withErrors(['email'=>'Invalid credentials'])->onlyInput('email');
    }

}

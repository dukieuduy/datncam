<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
// use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(){
        return view('client/pages.login');
    }
    public function postLogin(Request $request){
        // dd($request->all());
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect()->route('home');
        }else{
            return redirect()->back()->with('error','sai thông tin');
        }
    }
    public function register(){
        return view('client/pages.register');
    }
    public function postRegister(Request $request){
        // dd($request->all());

        // dd(Hash::make($request->password));
        $request->merge(['password'=>Hash::make($request->password)]);
        try {
            User::create($request->all());
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->route('login');

    }
    public function logout(){
        Auth::logout();
        return redirect()->back();
    }
}


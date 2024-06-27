<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    //
    public function show(Request $request){
        return view("Auth.login");
    }

    public function authenticate(Request $request): RedirectResponse{
        $credentials = $request->validate([
            "email" =>['required','email'],
            "password" =>['required']
        ]);
        if(Auth::guard("web")->attempt($credentials)){
            $request->session()->regenerate();
            switch(Auth::user()->role){
                case "super":
                    return redirect()->route('dashboard')->withSuccess("You have successfully  logged in");
                    break;
                case "magazinier":
                    return redirect()->route("dashboard-manager")->withSucces("You have successfully  logged in");
                    break;       
            }
        }
        return back()->withErrors([
            'email'=>"l'email incorrect ou inexistante"
        ])->onlyInput('email');
    

    }

    public function register(Request $request):RedirectResponse{
        $request->validate([
            "name" =>"string|required",
            "email"=>"email | required |unique:users,email",
            "password"=>"required|min:8|confirmed",
            "password_confirmation"=>"required",
            "role"=>"required| string",
            "region"=>"required| string"
        ]);
        $hashPassword = Hash::make($request->password);
        User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$hashPassword,
            "role"=>$request->role,
            "region"=>$request->region
        ]);
        return redirect()->route('addUser')->withSuccess("utilisateur insere avec succes");
    }
    public function logout(Request $request):RedirectResponse{
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminPageController extends Controller
{
    //
    public function show(Request $request){
        return view('dashboard');
    }
    public function showUsers(Request $request){
        $users = User::where("role","!=","super")->get();
        return view("manageUsers",["users"=>$users]);
    }
    public function addUserForm(Request $request){
        return view("addUser");
    }
    public function editUser(Request $request, $id){
        $user = User::findOrFail($id);
        return view("editUser",["user"=>$user]);
    }   
    public function updateUser(Request $request,$id){
        $request->validate([
            "name"=>"string|required",
            "role"=>"string| required",
            "region"=>"string | required"
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->role = $request->role;
        $user->region = $request->region;
        $user->save();
        return redirect()->route('manageUsers')->withSuccess("utilisateur modifier avec success");
    }
    public function deleteUser(Request $request, $id){
        $user=  User::findOrFail($id);
        $user->delete();
        return  response()->json(["message"=>"element supprime avec success"]);
    }
}

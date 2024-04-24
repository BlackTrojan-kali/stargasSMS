<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controllers;
use App\Http\Middleware\IsSuper;

Route::group(["middleware"=>"auth"],function(){
      Route::middleware(IsSuper::class)->group(function(){
            Route::get("/",[Controllers\SuperAdminPageController::class,"show"])->name('dashboard');
            Route::get("/manageUsers",[Controllers\SuperAdminPageController::class,"showUsers"])->name("manageUsers");
            Route::get("/addUser",[Controllers\SuperAdminPageController::class,"addUserForm"])->name("addUser");
            Route::post("/addUser",[Controllers\LoginController::class,"register"])->name("register");
            Route::get("/editUser/{id}",[Controllers\SuperAdminPageController::class,"editUser"])->name("editUser");
            Route::post("/updateUser/{id}",[Controllers\SuperAdminPageController::class,"updateUser"])->name("updateUser");
      });  
      Route::post('/logout',[Controllers\LoginController::class,"logout"])->name("logout");
});
Route::get('/login',[Controllers\LoginController::class, 'show'])->name('login');
Route::post('/login',[Controllers\LoginController::class,"authenticate"])->name("authenticate");
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controllers;
use App\Http\Controllers\Controller;
use App\Http\Middleware\isManager;
use App\Http\Middleware\IsSuper;

Route::group(["middleware"=>"auth"],function(){
      Route::middleware(IsSuper::class)->group(function(){
            Route::get("/",[Controllers\SuperAdminPageController::class,"show"])->name('dashboard');
            Route::get("/manageUsers",[Controllers\SuperAdminPageController::class,"showUsers"])->name("manageUsers");
            Route::get("/addUser",[Controllers\SuperAdminPageController::class,"addUserForm"])->name("addUser");
            Route::post("/addUser",[Controllers\LoginController::class,"register"])->name("register");
            Route::get("/editUser/{id}",[Controllers\SuperAdminPageController::class,"editUser"])->name("editUser");
            Route::post("/updateUser/{id}",[Controllers\SuperAdminPageController::class,"updateUser"])->name("updateUser");
            Route::delete("/deleteUser/{id}",[Controllers\SuperAdminPageController::class,"deleteUser"])->name("deleteUser");
            Route::get("/chooseArticleType",[Controllers\ArticleController::class,"choose"])->name("chooseArticleType");
            Route::get("/manageArtilces",[Controllers\ArticleController::class,"show"])->name("manageArticles");
            Route::get("/addArticle",[Controllers\ArticleController::class,"insert"])->name("addArticle");
            Route::get("/addAccessory",[Controllers\ArticleController::class,"insertAcc"])->name("addAccessory");
            Route::post("/insertArticle",[Controllers\ArticleController::class,"store"])->name("insertArticle");
            Route::delete("/deleteArticle/{id}",[Controllers\ArticleController::class,"delete"])->name("deleteArticle");
      });  
      Route::middleware(isManager::class)->group(function () {
          Route::get("/manager/dashboard",[Controllers\MagazinierController::class,"show"])->name("dashboard-manager");
          Route::post("manager/moveActioins/save/{action}/{state}",[Controllers\MagazinierController::class,"saveBottleMove"])->name("saveBottleMove");
          Route::post("manager/moveActioins/save/{action}",[Controllers\MagazinierController::class,"saveAccessoryMoves"])->name("saveAccessoryMove");
          //historique des mouvements
          Route::get("/manager/history",[Controllers\MagazinierController::class,"showHistory"])->name("manager-history");
      });
      Route::post('/logout',[Controllers\LoginController::class,"logout"])->name("logout");
});
Route::get('/login',[Controllers\LoginController::class, 'show'])->name('login');
Route::post('/login',[Controllers\LoginController::class,"authenticate"])->name("authenticate");
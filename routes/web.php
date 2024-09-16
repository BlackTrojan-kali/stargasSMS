<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controllers;
use App\Http\Controllers\Controller;
use App\Http\Middleware\isManager;
use App\Http\Middleware\isProducer;
use App\Http\Middleware\IsSuper;

Route::group(["middleware"=>"auth"],function(){
      
      Route::post("/moveVrac",[Controllers\ProducerController::class,"depotage"])->name("Depotage");
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
            //citernes
            Route::get("/addCiterns",[Controllers\CiternController::class,"showFormAddCitern"])->name("addCiterns");
            Route::post("/validateCiterns",[Controllers\CiternController::class,"validateFormAddCitern"])->name("validateCiterns");
            Route::delete("/deleteCitern/{id}",[Controllers\CiternController::class,"delete"])->name("deleteCiterne");
         
            
      });  
      Route::middleware(isManager::class)->group(function () {
          Route::get("/manager/dashboard",[Controllers\MagazinierController::class,"show"])->name("dashboard-manager");
          Route::post("manager/moveActioins/save/{action}/{state}",[Controllers\MagazinierController::class,"saveBottleMove"])->name("saveBottleMove");
          Route::post("manager/moveActioins/save/{action}",[Controllers\MagazinierController::class,"saveAccessoryMoves"])->name("saveAccessoryMove");
          //historique des mouvements
          Route::get("/manager/history",[Controllers\MagazinierController::class,"showHistory"])->name("manager-history");
          Route::post("/manager/filteredHistory",[Controllers\MagazinierController::class,"showfilteredHistory"])->name("manager-filtered-history");
         Route::delete("/manager/DeleteMove/{id}",[Controllers\MagazinierController::class,"deleteMove"])->name("deleteMove");
          //RELEVES
            Route::post("/manager/gplMove",[Controllers\CiternController::class,"moveGpl"])->name("MoveGpl");
            Route::get("/manager/releves",[Controllers\CiternController::class,"showReleve"])->name("showReleve");
               //etats
            Route::get("/producer/moveEntryMan/{state}/{type}/{weight}",[Controllers\ProducerController::class,"showEntry"])->name("moveEntryMan");


      });
      Route::middleware(isProducer::class)->group(function(){
            Route::get("/producer/dashboard",[Controllers\ProducerController::class,"show"])->name("dashboard-producer");
            Route::post("/producer/gplMove",[Controllers\CiternController::class,"moveGpl"])->name("MoveGplPro");
            Route::get("/producer/releves",[Controllers\CiternController::class,"showReleve"])->name("showRelevePro");
            Route::post("producer/moveActioins/save/{action}/{state}",[Controllers\ProducerController::class,"saveBottleMove"])->name("saveBottleMovePro");
            Route::get("/producer/citernes",[Controllers\ProducerController::class,"showCiterne"])->name("showCiterne");
            Route::get("/producer/makeRel/{id}",[Controllers\ProducerController::class,"makeRel"])->name("makeRel"); 
            Route::post("/producer/postRel/{id}",[Controllers\ProducerController::class,"postRel"])->name("postRel");
            Route::post("/producer/makeMove",[Controllers\ProducerController::class,"produceGas"])->name("produceGas");
            Route::post("/producer/makeTransmission",[Controllers\ProducerController::class,"transmitGas"])->name("transmitGas");
            Route::get("/producer/moveEntryPro/{state}/{type}/{weight}",[Controllers\ProducerController::class,"showEntry"])->name("moveEntryPro");
           });
      Route::post('/logout',[Controllers\LoginController::class,"logout"])->name("logout");
});
Route::get('/login',[Controllers\LoginController::class, 'show'])->name('login');
Route::post('/login',[Controllers\LoginController::class,"authenticate"])->name("authenticate");
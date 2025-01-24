<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controllers;
use App\Http\Controllers\Controller;
use App\Http\Middleware\isCommercial;
use App\Http\Middleware\isController;
use App\Http\Middleware\IsDirector;
use App\Http\Middleware\isManager;
use App\Http\Middleware\isProducer;
use App\Http\Middleware\IsSuper;

Route::group(["middleware" => "auth"], function () {

      Route::post("/moveVrac", [Controllers\ProducerController::class, "depotage"])->name("Depotage");
      Route::middleware(IsSuper::class)->group(function () {
            Route::get("/", [Controllers\SuperAdminPageController::class, "show"])->name('dashboard');
            Route::get("/manageUsers", [Controllers\SuperAdminPageController::class, "showUsers"])->name("manageUsers");
            Route::get("/addUser", [Controllers\SuperAdminPageController::class, "addUserForm"])->name("addUser");
            Route::post("/addUser", [Controllers\LoginController::class, "register"])->name("register");
            Route::get("/editUser/{id}", [Controllers\SuperAdminPageController::class, "editUser"])->name("editUser");
            Route::post("/updateUser/{id}", [Controllers\SuperAdminPageController::class, "updateUser"])->name("updateUser");
            Route::delete("/deleteUser/{id}", [Controllers\SuperAdminPageController::class, "deleteUser"])->name("deleteUser");
            Route::get("/chooseArticleType", [Controllers\ArticleController::class, "choose"])->name("chooseArticleType");
            Route::get("/manageArtilces", [Controllers\ArticleController::class, "show"])->name("manageArticles");
            Route::get("/addArticle", [Controllers\ArticleController::class, "insert"])->name("addArticle");
            Route::get("/addAccessory", [Controllers\ArticleController::class, "insertAcc"])->name("addAccessory");
            Route::post("/insertArticle", [Controllers\ArticleController::class, "store"])->name("insertArticle");
            Route::delete("/deleteArticle/{id}", [Controllers\ArticleController::class, "delete"])->name("deleteArticle");
            //citernes
            Route::get("/addCiterns", [Controllers\CiternController::class, "showFormAddCitern"])->name("addCiterns");
            Route::post("/validateCiterns", [Controllers\CiternController::class, "validateFormAddCitern"])->name("validateCiterns");
            Route::delete("/deleteCitern/{id}", [Controllers\CiternController::class, "delete"])->name("deleteCiterne");
      });
      Route::middleware(isManager::class)->group(function () {
            Route::get("/manager/dashboard", [Controllers\MagazinierController::class, "show"])->name("dashboard-manager");
            Route::post("manager/moveActioins/save/{action}/{state}", [Controllers\MagazinierController::class, "saveBottleMove"])->name("saveBottleMove");
            Route::post("manager/moveActioins/save/{action}", [Controllers\MagazinierController::class, "saveAccessoryMoves"])->name("saveAccessoryMove");
            //historique des mouvements
            Route::delete("/manager/DeleteRec/{id}", [Controllers\MagazinierController::class, "deleteReception"])->name("deleteRec");
            Route::get("/manager/history", [Controllers\MagazinierController::class, "showHistory"])->name("manager-histories");
            Route::post("/manager/filteredHistory", [Controllers\MagazinierController::class, "showfilteredHistory"])->name("manager-filtered-history");
            Route::delete("/manager/DeleteMove/{id}", [Controllers\MagazinierController::class, "deleteMove"])->name("deleteMove");
            //RELEVES
            Route::post("/manager/gplMove", [Controllers\CiternController::class, "moveGpl"])->name("MoveGpl");
            Route::get("/manager/receives", [Controllers\CiternController::class, "showReleve"])->name("showReleve");
            //etats
            Route::get("/manager/moveEntryMan/{state}/{type}/{weight}", [Controllers\ProducerController::class, "showEntry"])->name("moveEntryMan");
            //etats globaux
            Route::get("/manager/moveGlobalMan/{type}/{weight}", [Controllers\ArticleController::class, "MoveGlobal"])->name("moveGlobalMan");
            Route::get("/manager/citerne/modif/{id}", [Controllers\ArticleController::class, "ModifyTheo"])->name("modif");
            Route::post("/manager/citerne/postmodif/{id}", [Controllers\ArticleController::class, "postModif"])->name("postModif");
            //citernes etat
            Route::get("/manager/citernes", [Controllers\ProducerController::class, "showCiterne"])->name("showCiterneMan");
            Route::get("/manager/citernes/historique", [Controllers\CiternController::class, "show"])->name("historique-rel");
            //bordereau de route
            Route::post("/manager/broute-post/", [Controllers\MagazinierController::class, "generateBroutePDF"])->name("gen-broute");
            Route::get("/manager/broute-list", [Controllers\MagazinierController::class, "show_broute_list"])->name("broutes-list-man");
            Route::get("/manager/broute-list-gen/{id}", [Controllers\MagazinierController::class, "BroutePDF"])->name("gen-broute-2");
            Route::delete("/manager/broute-list-del/{id}", [Controllers\MagazinierController::class, "deleteBroute"])->name("del-broute");
            //modification de mouvement
            Route::get("/manager/modifmove/{id}", [Controllers\MagazinierController::class, "modifyMove"])->name("modify-move");
            Route::post("/manager/modifmove/post/{id}", [Controllers\MagazinierController::class, "updateMove"])->name("update-move-man");
      });
      Route::middleware(isProducer::class)->group(function () {
            Route::get("/producer/dashboard", [Controllers\ProducerController::class, "show"])->name("dashboard-producer");
            Route::post("/producer/gplMove", [Controllers\CiternController::class, "moveGpl"])->name("MoveGplPro");
            Route::get("/producer/releves", [Controllers\CiternController::class, "showReleve"])->name("showRelevePro");
            Route::post("producer/moveActioins/save/{action}/{state}", [Controllers\ProducerController::class, "saveBottleMove"])->name("saveBottleMovePro");
            Route::get("/producer/citernes", [Controllers\ProducerController::class, "showCiterne"])->name("showCiterne");

            Route::delete("/producer/DeleteRec/{id}", [Controllers\ProducerController::class, "deleteReception"])->name("deleteRecPro");
            Route::post("/producer/makeMove", [Controllers\ProducerController::class, "produceGas"])->name("produceGas");
            Route::delete("/producer/DeleteMove/{id}", [Controllers\ProducerController::class, "deleteMove"])->name("deleteMovePro");
            Route::post("/producer/makeTransmission", [Controllers\ProducerController::class, "transmitGas"])->name("transmitGas");
            Route::get("/producer/moveEntryPro/{state}/{type}/{weight}", [Controllers\ProducerController::class, "showEntry"])->name("moveEntryPro");

            Route::get("/producer/moveGlobalPro/{type}/{weight}", [Controllers\ArticleController::class, "MoveGlobal"])->name("moveGlobalPro");
            //historique production
            Route::get("/producer/produceHistory/", [Controllers\ProducerController::class, "showProdHist"])->name("showProdHist");
            Route::post("/producer/genProdHist", [Controllers\ProducerController::class, "ProdHistPDF"])->name("genProdHist");
            //modification de mouvement
            Route::get("/producer/modifmove/{id}", [Controllers\ProducerController::class, "modifyMove"])->name("modify-move-pro");
            Route::post("/producer/modifmove/post/{id}", [Controllers\ProducerController::class, "updateMove"])->name("update-move-pro");
      });
      Route::middleware(isCommercial::class)->controller(Controllers\CommercialController::class)->group(
            function () {
                  Route::get('/dashboardCom', "index")->name("dashboardCom");
                  Route::post("commercial/moveActioins/save/{action}/{state}", "saveBottleMove")->name("saveBottleMoveCom");
                  Route::post("commercial/moveActioins/save/{action}", "saveAccessoryMoves")->name("saveAccessoryMoveCom");
                  Route::post("/commercial/sales/{type}", "makeSales")->name("makeSales");
                  Route::get("/commercial/history", [Controllers\MagazinierController::class, "showHistory"])->name("commercial-history");
                  Route::get("/commercial/history", "showHistory")->name("commercial-history");
                  Route::get("/commercial/sales-history", "SalesHistory")->name("sales-history");
                  Route::post("/commercial/versements", "makeVersement")->name("makeVersement");
                  Route::get("/commercial/ventes/{type}", "ventes")->name("showVentes");
                  Route::delete("/commercial/delventes/{id}", "deleteSales")->name("delVentes");
                  Route::delete("/commercial/DeleteMove/{id}", [Controllers\MagazinierController::class, "deleteMove"])->name("deleteMoveCom");
                  Route::get("/commercial/printInvoice/{id}", "print_invoice")->name("printInvoice");
                  Route::get("/commercial/moveGlobalPro/{type}/{weight}", [Controllers\ArticleController::class, "MoveGlobal"])->name("moveGlobalCom");
                  Route::post("/commercial/createAccessoriePDf/{type}", "makeAcSales")->name("makeAcSales");
                  Route::delete("/commercial/deleteVersemment/{id}", "deleteVersement")->name("deleteVersement");
                  //modifier les ventes
                  Route::get("/commercial/modify/{id}", "modifySales")->name("modifySale");
                  Route::post("/commercial/modify/post/{id}", "updateSales")->name("updateSale");
                  //modifier les versements
                  Route::get("/commercial/modifyversement/{id}", "modifyVersement")->name("modifyVersement");
                  Route::post("/commercial/modifyversement/post/{id}", "updateVersement")->name("updateVersement");
            }
      );
      Route::middleware(isCommercial::class)->group(function () {
            Route::get("/commercial/moveEntryCom/{state}/{type}/{weight}", [Controllers\ProducerController::class, "showEntry"])->name("moveEntryCom");
      });
      //routes du controller
      Route::middleware(isController::class)->group(function () {

            Route::get("/controller", [Controllers\BossController::class, "index"])->name("bossDashboard");
            Route::post("/controller/recieves-pdf", [Controllers\BossController::class, "generate_receive_pdf"])->name("receives_boss_pdf");
            Route::post("/controller/sales-state-pdf", [Controllers\BossController::class, "generate_sale_state"])->name("boss_sale_state_pdf");
            Route::post("/controller/versement/pdf", [Controllers\BossController::class, "generate_versements_state"])->name("boss_versementPdf");
            Route::post("/pdfController", [Controllers\BossController::class, "generatePDF"])->name("pdfController");
            Route::post("/producer/genProdHistCon", [Controllers\ProducerController::class, "ProdHistPDF"])->name("genProdHistCon");
            Route::get("/controller/citernes", [Controllers\BossController::class, "showCiterne"])->name("showCiterneCon");
            Route::get("/controller/receives", [Controllers\CiternController::class, "showReleve"])->name("showReleveCon");
            Route::get("/commercial/ventesCon/{type}", [Controllers\CommercialController::class, "ventes"])->name("showVentesCon");
            Route::get("/controller/citernes/historique", [Controllers\CiternController::class, "show"])->name("historique-rel-con");
            Route::get("/controller/produceHistory/", [Controllers\ProducerController::class, "showProdHist"])->name("showConHist");
            Route::get("/controller/broute-list", [Controllers\BossController::class, "show_broute_list"])->name("broutes-list-con");
            Route::get("/controller/broute-list-gen/{id}", [Controllers\BossController::class, "BroutePDF"])->name("gen-broute-3");
            Route::get("/Controller/printInvoice/{id}", [Controllers\CommercialController::class, "print_invoice"])->name("printInvoiceController");
            //Excels files
            Route::post("/constroller/excels", [Controllers\ExcelController::class, "export"])->name("export-excelts");
            Route::post("/controller/recieves-excel", [Controllers\ExcelController::class, "exportReceives"])->name("receives_boss_excel");
      });
      //DIRECTOR INTERFACES
      Route::middleware(IsDirector::class)->group(
            function () {
                  Route::get("/director/dashboard", [Controllers\DirectorController::class, "index"])->name("director-dashboard");
                  Route::get("/director/Versement", [Controllers\DirectorController::class, "getCAGlobal"])->name("globalCA");
                  Route::get("/director/Consigne", [Controllers\DirectorController::class, "getCAGlobalConsigne"])->name("globalCA-consigne");
                  Route::get("/director/Versements/{region}", [Controllers\DirectorController::class, "getCAGlobalRegion"])->name("globalCARegion");
                  Route::get("/director/Consigne/{region}", [Controllers\DirectorController::class, "getCAGlobalRegionConsigne"])->name("globalCARegion-consigne");

                  //ventes consolide
                  Route::get("/director/CA", [Controllers\DirectorController::class, "globalSales"])->name("globalSalesCA");
                  Route::get("/director/CA-consigne", [Controllers\DirectorController::class, "globalConsigne"])->name("globalConsignesCA");
                  Route::get("/director/CA/{region}", [Controllers\DirectorController::class, "getRegionSales"])->name("globalSalesCA-region");
                  Route::get("/director/CA-consigne/{region}", [Controllers\DirectorController::class, "getRegionConsignes"])->name("globalConsignesCA-region");
                  //entree consolide
                  Route::get("/director/Global/entries", [Controllers\DirectorController::class, "globalFullBottles"])->name("globalFullBottles");
                  Route::get("/director/Global/entries-vides", [Controllers\DirectorController::class, "globalEmptyBottles"])->name("globalEmptyBottles");
                  Route::get("/director/{region}/entries", [Controllers\DirectorController::class, "RegionFullBottles"])->name("RegionFullBottles");
                  Route::get("/director/{region}/entries-vides", [Controllers\DirectorController::class, "RegionEmptyBottles"])->name("RegionEmptyBottles");
            }
      );
      Route::post("/commercial/versement/pdf", [Controllers\CommercialController::class, "generate_versements_state"])->name("versementPdf");
      Route::get("/producer/makeRel/{id}", [Controllers\ProducerController::class, "makeRel"])->name("makeRel");
      Route::post("/producer/postRel/{id}", [Controllers\ProducerController::class, "postRel"])->name("postRel");
      Route::post('/logout', [Controllers\LoginController::class, "logout"])->name("logout");
      Route::post("/pdf", [Controllers\ArticleController::class, "generatePDF"])->name("pdf");
      Route::post("/sales-state-pdf", [Controllers\CommercialController::class, "generate_sale_state"])->name("sale_state_pdf");
      Route::post("/recieves-pdf", [Controllers\CiternController::class, "generate_receive_pdf"])->name("receives_pdf");
      Route::post("/releves-pdf", [Controllers\CiternController::class, "generate_rel_pdf"])->name("releves_pdf");

      //manage roles and regions
      Route::get("/roles", [Controllers\RoleController::class, "index"])->name("roles");
      Route::get("/create-role", [Controllers\RoleController::class, "create"])->name("create-role");
      Route::get("/regions", [Controllers\RegionController::class, "index"])->name("regions");
      Route::get("/create-region", [Controllers\RegionController::class, "create"])->name("create-region");
      Route::post("/store-role", [Controllers\RoleController::class, "store"])->name("store-role");
      Route::post("/store-region", [Controllers\RegionController::class, "store"])->name("store-region");
      Route::delete("/role-delete/{id}", [Controllers\RoleController::class, "delete"]);
      Route::delete("/region-delete/{id}", [Controllers\RegionController::class, "delete"]);
});
Route::get('/login', [Controllers\LoginController::class, 'show'])->name('login');
Route::post('/login', [Controllers\LoginController::class, "authenticate"])->name("authenticate");

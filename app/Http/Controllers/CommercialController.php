<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Article;
use App\Models\Citerne;
use App\Models\Movement;
use App\Models\Stock;
use App\Models\Vente;
use App\Models\Versement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CommercialController extends Controller
{
    //
    public function index()
    {

        $stocks = Stock::where("region", "=", Auth::user()->region)->where("category", "commercial")->with("article")->get();
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        return view("commercial.dashboard", ["stocks" => $stocks, "accessories" => $accessories]);
    } //
    public function ventes(Request $request, $type)
    {

        $stocks = Stock::where("region", "=", Auth::user()->region)->where("category", "commercial")->with("article")->get();
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        $mobile = Citerne::where("type", "mobile")->get();
        $mobile = Citerne::where("type", "mobile")->get();
        $fixe  = Citerne::where("type", "fixe")->get();

        if ($type == "versements") {
            $versements1 = Versement::where("bank", env("COMPANIE_BANK_1"))->where("region", "=", Auth::user()->region)->get();
            $versements2 = Versement::where("bank", env("COMPANIE_BANK_2"))->where("region", "=", Auth::user()->region)->get();
            $versements3 = Versement::where("bank", "CAISSE")->where("region", "=", Auth::user()->region)->get();
            if (Auth::user()->role == 'controller') {
                return view("controller.historique-versements", ["ventes" => $versements1, "ventes2" => $versements2, "ventes3" => $versements3, "type" => $type, "mobile" => $mobile, "fixe" => $fixe, "stocks" => $stocks]);
            }
            return view("commercial.historique-versements", ["stocks" => $stocks, "accessories" => $accessories, "ventes" => $versements1, "ventes3" => $versements3,  "ventes2" => $versements2, "type" => $type]);
        } else {
            $ventes = Vente::where("type", $type)->where("region", Auth::user()->region)->get();
            if (Auth::user()->role == 'controller') {
                return view("controller.historique-ventes", ["ventes" => $ventes, "type" => $type, "mobile" => $mobile, "fixe" => $fixe, "stocks" => $stocks]);
            } else {
                return view("commercial.historique-ventes", ["stocks" => $stocks, "accessories" => $accessories, "ventes" => $ventes, "type" => $type]);
            }
        }
    }
    public function SalesHistory()
    {

        $stocks = Stock::where("region", "=", Auth::user()->region)->where("category", "commercial")->with("article")->get();
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        $sales = Vente::where("region", "=", Auth::user()->region)->where("service", Auth::user()->role)->with("article")->orderBy("created_at", "DESC")->get();
        return view("commercial.SalesHistory", ["sales" => $sales, "accessories" => $accessories, "stocks" => $stocks]);
    }
    public function saveBottleMove(Request $request, $action, $state)
    {
        $request->validate([
            "origin" => "string | required",
            "weight" => "string |required",
            "label" => "string |required",
            "qty" => "numeric | required ",
            "bord" => "string | required "
        ]);
        $state = intval($state);
        $weight = floatval($request->weight);
        $region = Auth::user()->region;
        $service = Auth::user()->role;
        $article = Article::where("type", "=", "bouteille-gaz")->where("weight", "=", $weight)->where("state", "=", $state)->first();
        if ($article) {
            $stock = Stock::where("article_id", "=", $article->id)->where("category", "=", Auth::user()->role)->where("region", "=", $region)->first();


            if ($stock) {
                if ($action == "entry") {
                    $stock->qty = $stock->qty + $request->qty;
                    $stockQty =  $stock->qty;
                    $stock->save();
                    //notify boss
                    $actions = new Action();
                    $actions->description = Auth::user()->name . "[entry] - [{{$request->qty}}] - [{{$article->type}}]- [{{$article->weighy}} KG]";
                    $actions->id_user = Auth::user()->id;
                } else {
                    $stock->qty = $stock->qty - $request->qty;
                    $stockQty =  $stock->qty;
                    if ($stock->qty <= 0) {
                        $stock->qty = 0;
                        $stockQty =  $stock->qty;
                    }
                    $stock->save();

                    //notify boss
                    $actions = new Action();
                    $actions->description = Auth::user()->name . "[outcome] - [{{$request->qty}}] - [{{$article->type}}]- [{{$article->weighy}} KG]";
                    $actions->id_user = Auth::user()->id;
                }
                $move = new Movement();
                $move->article_id = $article->id;
                $move->qty = $request->qty;
                $move->bordereau = $request->bord;
                $move->stock_id = $stock->id;
                $move->origin = $request->origin;
                $move->stock = $stockQty;
                $move->service = $service;
                $move->label = $request->label;
                if ($action == "entry") {
                    $move->entree = 1;
                    $move->sortie = 0;
                } else {

                    $move->entree = 0;
                    $move->sortie = 1;
                }
                $move->save();


                return response()->json(['success' => 'mouvement enregistre avec succes']);
            } else {
                return response()->json(["error" => "stock inexistant"]);
            }
        } else {
            return response()->json(["error" => "stock inexistant"]);
        }
    }


    //SAVE ACCESSORIES
    public function saveAccessoryMoves(Request $request, $action)
    {
        $request->validate([
            "title" => "string |required",
            "qty" => "numeric | required|max:2000",
            "label" => "string | max:250 |required",
            "bord" => "string | required"
        ]);
        $article = Article::where("title", "=", $request->title)->where("type", "=", "accessoire")->first();
        if ($article) {

            $stock = Stock::where("article_id", "=", $article->id)->where("category", "=", Auth::user()->role)->where("region", "=", Auth::user()->region)->first();

            if ($stock) {
                if ($action == "entry") {
                    $stock->qty = $stock->qty + $request->qty;

                    $stockQty = $stock->qty;
                    $stock->save();
                } else {
                    $stock->qty = $stock->qty - $request->qty;

                    $stockQty = $stock->qty;
                    if ($stock->qty <= 0) {
                        $stock->qty = 0;

                        $stockQty = $stock->qty;
                    }
                    $stock->save();
                }


                $move = new Movement();
                $move->article_id = $article->id;
                $move->qty = $request->qty;
                $move->stock = $stockQty;
                $move->stock_id = $stock->id;
                $move->origin = "null";
                $move->bordereau = $request->bord;
                $move->service = Auth::user()->role;
                $move->label = $request->label;
                if ($action == "entry") {
                    $move->entree = 1;
                    $move->sortie = 0;
                } else {

                    $move->entree = 0;
                    $move->sortie = 1;
                }
                $move->save();


                return response()->json(["success" => "mouvement enregistre avec succes"]);
            }
        } else {
            return response()->json(["error" => "stock inexistant"]);
        }
    }

    public function showHistory(Request $request)
    {
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        $allMoves = Movement::join("stocks", "stock_id")->where("stocks.region", Auth::user()->region)->with("fromStock", "fromArticle")->where("entree", 1)->where("service", Auth::user()->role)->orderBy("created_at", "DESC")->get();
        $allMovesOut = Movement::with("fromStock", "fromArticle")->where("sortie", 1)->where("service", Auth::user()->role)->orderBy("created_at", "DESC")->get();
        return view("manager.history", ["accessories" => $accessories, "allMoves" => $allMoves, "allMovesOut" => $allMovesOut]);
    }

    public function makeSales(Request $request, $type)
    {
        $request->validate([
            "costumer" => "string | required",
            "address" => "string | required",
            "numero" => "numeric |required",
            "prix_6" => "numeric | required",
            "qty_6" => "numeric | required",
            "prix_12" => "numeric | required",
            "qty_12" => "numeric | required",
            "prix_50" => "numeric | required",
            "qty_50" => "numeric | required",
            "currency" => "string | required",

        ]);
        $article = Stock::join("articles", "stocks.article_id", "articles.id")->where("articles.type", "bouteille-gaz")->where("articles.state", 1)->where("articles.weight", 12.5)->where("stocks.region", Auth::user()->region)->where("stocks.category", "magasin")->select("stocks.*")->first();
        $article2 =  Stock::join("articles", "stocks.article_id", "articles.id")->where("articles.type", "bouteille-gaz")->where("articles.state", 1)->where("articles.weight", 6)->where("stocks.region", Auth::user()->region)->where("stocks.category", "magasin")->select("stocks.*", "articles.weight")->first();

        $article3 =  Stock::join("articles", "stocks.article_id", "articles.id")->where("articles.type", "bouteille-gaz")->where("articles.state", 1)->where("articles.weight", 50)->where("stocks.region", Auth::user()->region)->where("stocks.category", "magasin")->select("stocks.*")->first();



        $vente = new Vente();

        $vente->customer = $request->costumer;
        $vente->prix_6 = $request->prix_6;
        $vente->number = $request->numero;
        $vente->qty_6 = $request->qty_6;
        $vente->prix_12 = $request->prix_12;
        $vente->qty_12 = $request->qty_12;
        $vente->prix_50 = $request->prix_50;
        $vente->qty_50 = $request->qty_50;
        $vente->prix_total = (($vente->prix_6 * $vente->qty_6) + ($vente->prix_12 * $vente->qty_12) + ($vente->prix_50 * $vente->qty_50));
        $vente->address = $request->address;
        $vente->type = $type;
        $vente->region = Auth::user()->region;
        $vente->service = Auth::user()->role;
        $vente->prix_unitaire = 0;
        $vente->currency = $request->currency;
        $vente->save();
        $pdf = Pdf::loadView("commercial.invoice", ["vente" => $vente, "article" => $article, "type" => $type]);
        return $pdf->download($vente->customer . $vente->created_at . ".pdf");
    }
    public function makeAcSales(Request $request, $type)
    {
        $request->validate([
            "costumer" => "string | required",
            "address" => "string | required",
            "numero" => "numeric |required",
            "prix" => "numeric | required",
            "qty" => "numeric | required",
            "accessoire" => "string | required",
            "currency" => "string | required",
        ]);
        $article = Stock::join("articles", "stocks.article_id", "articles.id")->where("articles.type", "accessoire")->where("stocks.region", Auth::user()->region)->where("stocks.category", "magasin")->select("stocks.*")->first();


        $vente = new Vente();

        $vente->customer = $request->costumer;
        $vente->prix_6 = $request->prix;
        $vente->number = $request->numero;
        $vente->qty_6 = $request->qty;
        $vente->prix_12 = 0;
        $vente->qty_12 = 0;
        $vente->prix_50 = 0;
        $vente->qty_50 = 0;
        $vente->address = 0;
        $vente->type = $type;
        $vente->region = Auth::user()->region;
        $vente->service = Auth::user()->role;
        $vente->prix_unitaire = 0;
        $vente->currency = $request->currency;
        $vente->save();
        $pdf = Pdf::loadview("commercial.invoice2", ["vente" => $vente, "article" => $article, "type" => $type]);
        return  $pdf->download($vente->customer . $vente->created_at . ".pdf");
    }
    public function print_invoice($id)
    {
        $vente = Vente::findOrFail($id);
        $pdf = Pdf::loadView("commercial.invoice", ["vente" => $vente, "type" => $vente->type]);
        return $pdf->download($vente->customer . $vente->created_at . ".pdf");
    }
    //delete sales
    public function deleteSales($id)
    {
        $sale = Vente::findOrFail($id);

        $sale->delete();
        return response()->json(["message" => "element supprime avec success"]);
    }
    public function makeVersement(Request $request)
    {
        $request->validate([
            "montant_gpl" => "numeric | required",
            "montant_consigne" => "numeric | required",
            "commentaire" => "string | nullable",
            "bordereau" => "string | required",
            "bank" => "string | required",
        ]);
        $versement = new Versement();
        $versement->montant_gpl = $request->montant_gpl;
        $versement->montant_consigne = $request->montant_consigne;
        $versement->commentaire = $request->commentaire;
        $versement->bordereau = $request->bordereau;
        $versement->region = Auth::user()->region;
        $versement->service = Auth::user()->role;
        $versement->bank = $request->bank;
        $versement->save();
        return response()->json(["success" => "versement enregistre avec succes"]);
    }
    //SALES STATE GENERATE PDF

    public function generate_sale_state(Request $request)
    {
        $request->validate(
            [
                "depart" => "date | required",
                "fin" => "date | required",
                "name" => "string | nullable",
                "sale" => "string |required",
            ]
        );
        $fromDate = $request->depart;
        $toDate = $request->fin;
        if ($request->name) {

            $sales = Vente::whereBetween("created_at", [$request->depart, $request->fin])->where("type", $request->sale)->where("region", Auth::user()->region)->where("customer", $request->name)->get();
        } else {
            $sales = Vente::whereBetween("created_at", [$request->depart, $request->fin])->where("type", $request->sale)->where("region", Auth::user()->region)->get();
        }
        $pdf = Pdf::loadview("salesPdf", ["fromDate" => $fromDate, "toDate" => $toDate, "sales" => $sales, "type" => $request->sale]);
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(510, 800, "[{PAGE_NUM} sur {PAGE_COUNT}]", null, 15, array(0, 0, 0));
        return $pdf->download(Auth::user()->role . "" . Auth::user()->region . $fromDate . $toDate . ".pdf");
    }

    public function generate_versements_state(Request $request)
    {
        $request->validate(
            [
                "depart" => "date | required",
                "fin" => "date | required",
                "bank" => "string | required ",
            ]
        );
        $fromDate = $request->depart;
        $toDate = $request->fin;
        if ($request->bank == "all") {
            $afb = Versement::where("bank", env("COMPANIE_BANK_1"))->where("region", Auth::user()->region)->where("service", Auth::user()->role)->whereBetween("created_at", [$fromDate, $toDate])->get();
            $cca = Versement::where("bank", env("COMPANIE_BANK_2"))->where("region", Auth::user()->region)->where("service", Auth::user()->role)->whereBetween("created_at", [$fromDate, $toDate])->get();
            $caisse = Versement::where("bank", "CAISSE")->where("region", Auth::user()->region)->where("service", Auth::user()->role)->whereBetween("created_at", [$fromDate, $toDate])->get();
            $pdf = Pdf::loadview("versementPdfAll", ["fromDate" => $fromDate, "toDate" => $toDate, "afb" => $afb, "cca" => $cca, "bank" => $request->bank])->setPaper("A4", 'landscape');

            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();

            $canvas = $dom_pdf->get_canvas();
            $canvas->page_text(720, 550, "[{PAGE_NUM} sur {PAGE_COUNT}]", null, 15, array(0, 0, 0));
            return $pdf->download(Auth::user()->role . "versementsglobal" . Auth::user()->region . $fromDate . $toDate . $request->bank . ".pdf");
        } else {
            $deposit = Versement::where("bank", $request->bank)->where("region", Auth::user()->region)->where("service", Auth::user()->role)->whereBetween("created_at", [$fromDate, $toDate])->get();
            $pdf = Pdf::loadview("versementPdf", ["fromDate" => $fromDate, "toDate" => $toDate, "deposit" => $deposit, "bank" => $request->bank]);

            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();

            $canvas = $dom_pdf->get_canvas();
            $canvas->page_text(510, 800, "[{PAGE_NUM} sur {PAGE_COUNT}]", null, 15, array(0, 0, 0));
            return $pdf->download(Auth::user()->role . "versements" . Auth::user()->region . $fromDate . $toDate . $request->bank . ".pdf");
        }
    }
    public function deleteVersement($id)
    {
        $versement = Versement::findOrFail($id);
        $versement->delete();
        return response()->json(["message" => "versement supprime avec success"]);
    }
    public function modifySales($idSale)
    {

        $stocks = Stock::where("region", "=", Auth::user()->region)->where("category", "commercial")->with("article")->get();
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        $sale = Vente::findOrFail($idSale);
        return view("commercial.ModifSale", ["stocks" => $stocks, "accessories" => $accessories, "sale" => $sale]);
    }
    public function updateSales(Request $request, $idSale)
    {
        $request->validate([
            "costumer" => "string | required",
            "address" => "string | required",
            "numero" => "numeric |required",
            "prix_6" => "numeric | required",
            "qty_6" => "numeric | required",
            "prix_12" => "numeric | required",
            "qty_12" => "numeric | required",
            "prix_50" => "numeric | required",
            "qty_50" => "numeric | required",
            "currency" => "string | required",

        ]);

        $vente = Vente::findOrFail($idSale);

        $vente->customer = $request->costumer;
        $vente->prix_6 = $request->prix_6;
        $vente->number = $request->numero;
        $vente->qty_6 = $request->qty_6;
        $vente->prix_12 = $request->prix_12;
        $vente->qty_12 = $request->qty_12;
        $vente->prix_50 = $request->prix_50;
        $vente->qty_50 = $request->qty_50;
        $vente->address = $request->address;
        $vente->currency = $request->currency;
        $vente->save();
        return back()->withSuccess("element modifie avec succes");
    }
    public function modifyVersement($idVers)
    {

        $stocks = Stock::where("region", "=", Auth::user()->region)->where("category", "commercial")->with("article")->get();
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        $versement = Versement::findOrFail($idVers);
        return view("commercial.ModifVersement", ["stocks" => $stocks, "accessories" => $accessories, "versement" => $versement]);
    }
    public function updateVersement(Request $request, $idVers)
    {
        $request->validate([
            "montant_gpl" => "numeric | required",
            "montant_consigne" => "numeric | required",
            "commentaire" => "string | nullable",
            "bordereau" => "string | required",
            "bank" => "string | required",
        ]);
        $versement = Versement::findOrFail($idVers);
        $versement->montant_gpl = $request->montant_gpl;
        $versement->montant_consigne = $request->montant_consigne;
        $versement->commentaire = $request->commentaire;
        $versement->bordereau = $request->bordereau;
        $versement->bank = $request->bank;
        $versement->save();
        return back()->withSuccess("element modifie avec succes");
    }
}

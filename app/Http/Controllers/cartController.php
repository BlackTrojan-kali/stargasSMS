<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Client;
use App\Models\Clientprice;
use App\Models\Invoices;
use App\Models\Invoicetrace;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class cartController extends Controller
{
    //
    public function add($idProd)
    {
        $article = Article::findOrFail($idProd);
        Cart::add($idProd, $article->title, 1, $article->price, $article->weight);
        return response()->json(["message" => "added successfully"]);
    }
    public function cartlist()
    {
        $stocks = Stock::where("region", "=", Auth::user()->region)->where("category", "commercial")->with("article")->get();
        $accessories = Article::where("type", "=", "accessoire")->get("title");
        $clients = Client::all();
        return view("commercial.cartlist", ["accessories" => $accessories, "stocks" => $stocks, "clients" => $clients]);
    }
    public function updateCart($rowID, Request $request)
    {
        $request->validate(["qty" => "numeric | required", "rowId" => "string |required"]);
        Cart::update($rowID, ["qty" => $request->qty]);
        return back()->withSuccess("updated successfully");
    }
    public function deleteItem($rowID)
    {
        Cart::remove($rowID);
        return back()->withSuccess("item deleted successfully");
    }
    public function validate(Request $request)
    {
        $request->validate([
            "client" => "required",
            "currency" => "string |required",
            "amount" => "numeric |required",
            "type" => "string |  required",
        ]);
        $client = Client::findOrFail($request->client);
        $articles = Clientprice::where("id_client", $request->client)->get();
        if (count(Cart::content()) <= 0) {
            return back()->withErrors(["message" => "votre panier est vide"]);
        }
        foreach ($articles as $article) {
            foreach (Cart::content() as $content) {

                if ($content->id == $article->id_article) {
                    Cart::update($content->rowId, ["price" => $article->unite_price]);
                }
            }
        }
        $invoice = new Invoices();
        $invoice->commercial = Auth::user()->name;
        $invoice->id_client = $client->id;
        $invoice->currency = $request->currency;
        $invoice->region = Auth::user()->region;
        $invoice->recieved = $request->amount;
        $invoice->articles = Cart::content();
        $invoice->total_price = floatval(str_replace(",", "", Cart::priceTotal()));
        $invoice->type = $request->type;
        $pdf = Pdf::loadView("commercial.invoice3", ["invoice" => $invoice, "articles" => $articles, "client" => $client]);

        $invoice->save();
        foreach (Cart::content() as $content) {
            $trace = new Invoicetrace();
            $trace->id_invoice = $invoice->id;
            $trace->id_article = $content->id;
            $trace->qty = $content->qty;
            $trace->unit_price = $content->price;
            $trace->region = $invoice->region;
            $trace->save();
        }
        Cart::destroy();
        return $pdf->download($client->nom . $client->prenom . $invoice->created_at . ".pdf");
    }

    public function printInvoice($id)
    {

        $invoice = Invoices::findOrFail($id);
        $client = Client::findOrFail($invoice->id_client);
        $articles = Clientprice::where("id_client", $client->id)->get();
        $pdf = Pdf::loadView("commercial.invoice3", ["invoice" => $invoice, "articles" => $articles, "client" => $client]);
        return $pdf->download($client->nom . $client->prenom . $invoice->created_at . ".pdf");
    }
}
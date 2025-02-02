<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Clientcat;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class ClientController extends Controller
{
    //
    public function showCats()
    {
        $clientcats = Clientcat::all();
        return view("super.list_client_cats", ["clientcats" => $clientcats]);
    }
    public function createCat()
    {
        return view("super.create_client_cat");
    }
    public function storeCat(Request $request)
    {
        $request->validate([
            "name" => "string | max:20 |min:4",
            "redux" => "numeric | nullable",
        ]);
        $clientcat = new Clientcat();
        $clientcat->name = $request->name;
        $clientcat->reduction = $request->redux;
        $clientcat->save();
        return back()->withSuccess("categorie client creee avec succes");
    }
    public function modifCat($id)
    {
        $clientcat = Clientcat::findOrFail($id);
        return view("super.modify_client_cat", ["clientCat" => $clientcat]);
    }
    public function updateCat(Request $request, $id)
    {
        $request->validate([
            "name" => "string | max:20 | min:4",
            "redux" => "numeric | nullable",
        ]);
        $clientcat = Clientcat::findOrFail($id);
        $clientcat->name = $request->name;
        $clientcat->reduction = $request->redux;
        $clientcat->save();
        return back()->withSuccess("categorie client modifiee avec succes");
    }
    public function deleteCat($id)
    {
        $clientcat = Clientcat::find($id);
        $clientcat->delete();
        return response()->json(["message" => "categorie client supprimee avec succes"]);
    }
    //gestion clients
    public function showClients()
    {
        $clients = Client::all();
        return view("super.list-clients", ["clients" => $clients]);
    }
    public function createClient()
    {
        $categories = Clientcat::all();
        return view("super.create_client", ["categories" => $categories]);
    }
    public function storeClient(Request $request)
    {
        $request->validate([
            "name" => "string | min:3 | required",
            "address" => "string | min:3 | required",
            "fname" => "string | min:3 | required",
            "email" => "email | required",
            "phone" => "numeric | required",
            "category" => "required",
            "redux" => " numeric |nullable",
        ]);
        $client = new Client();
        $client->nom = $request->name;
        $client->prenom = $request->fname;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->numero = $request->phone;
        $client->reduction = $request->redux;
        $client->id_clientcat = $request->category;
        $client->save();
        return back()->withSuccess("client created successfully");
    }
    public function modifClient($id)
    {
        $client = Client::where("id", $id)->with("Clientcat")->first();
        $categories = Clientcat::all();
        return view("super.modif_client", ["client" => $client, "categories" => $categories]);
    }
    public function updateClient(Request $request, $id)
    {
        $request->validate([
            "name" => "string | min:3 | required",
            "address" => "string | min:3 | required",
            "fname" => "string | min:3 | required",
            "email" => "email | required",
            "phone" => "numeric | required",
            "category" => "required",
            "redux" => "numeric |nullable",
        ]);
        $client = Client::where("id", $id)->first();
        $client->nom = $request->name;
        $client->prenom = $request->fname;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->numero = $request->phone;
        $client->reduction = $request->redux;
        $client->id_clientcat = $request->category;
        $client->save();
        return back()->withSuccess("client updated successfully");
    }
    public function deleteClient($id){
        $client = Client::find($id);
        $client->delete();
        return response()->json(["message"=>"Client deleted successfully"]);
    }
}
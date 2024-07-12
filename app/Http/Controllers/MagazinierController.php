<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Movement;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagazinierController extends Controller
{
    //
    public function show(Request $request){
        if(Auth::user()->role == "magazinier"){
            $categorie = "magasin";
        }else{
            $categorie = Auth::user()->role;
        }
        $stocks = Stock::where("region","=",Auth::user()->region)->where("category","=",$categorie)->with("article")->get();
        return view('manager.dashboard',["stocks"=>$stocks]);
    }
    public function showmove(Request $request){
        return  view("manager.moveActions");
    }
    public function showTypeChoice(Request $request, $action){
        if( $action == "entry"){
        return view("manager.MovetypeAdd",["action"=>$action]);
    }else{
        return view("manager.MovetypeAdd",["action"=>$action]);
    }
}
public function registerAction(Request $request, $action, $type){
    if($type == "bouteille-gaz"){
        
        return view("manager.registerBottleGaz",["action"=>$action]);
        
    }elseif($type == "accessiore"){
        $articles = Article::where("type","=","accessoire");
        return view("manager.registerAccessory",["action"=>$action]);
    }
}
public function saveBottleMove(Request $request, $action){
    $request->validate([
        "origin"=>"string | required",
        "weight"=>"string |required",
        "qty"=>"numeric | required | min:0 |max:1000",
        "state"=>"string | required"
    ]);
    $state = intval($request->state);
    $weight = floatval($request->weight);
    $region = Auth::user()->region;
    $article = Article::where("type","=","bouteille-gaz")->where("weight","=",$weight)->where("state","=",$state)->first();
   if($article){
    $stock = Stock::where("article_id","=",$article->id)->where("category","=","magazin")->where("region","=",$region)->first();

   
 if($stock){
    if($action == "entry"){
      $stock->qty = $stock->qty +$request->qty;
        $stock->save();
    }else{
        $stock->qty = $stock->qty - $request->qty;
        if($stock->qty <= 0){
            $stock->qty = 0;
        }
        $stock->save();
        
    }
        $move = new Movement();
        $move->article_id = $article->id;
        $move->qty = $request->qty;
        $move->stock_id = $stock->id;
        $move->label = $request->origin;
        if($action =="entry"){
            $move->entree = 1;
            $move->sortie = 0;
        }else{

            $move->entree = 0;
            $move->sortie = 1;
        }
        $move->save();

    
        return back()->withSuccess("mouvement enregistre avec succes");
    
        }else{
        return back()->withErrors(["message"=>"stock inexistant"]);
    }    
}else{
    return back()->withErrors(["message"=>"stock inexistant"]);
}
}
}

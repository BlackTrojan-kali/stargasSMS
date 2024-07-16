<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Movement;
use App\Models\Stock;
use Carbon\Carbon;
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
        $accessories = Article::where("type","=","accessoire")->get("title");
        return view('manager.dashboard',["stocks"=>$stocks,"accessories"=>$accessories]);
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
public function showHistory(Request $request){
    $accessories = Article::where("type","=","accessoire")->get("title");
    $allMoves = Movement::with("fromStock","fromArticle")->where("entree",1)->orderBy("created_at","DESC")->get();
    $allMovesOut = Movement::with("fromStock","fromArticle")->where("sortie",1)->orderBy("created_at","DESC")->get();
    return view("manager.history",["accessories"=>$accessories,"allMoves"=>$allMoves,"allMovesOut"=>$allMovesOut]);
}
public function showfilteredHistory(Request $request){
    $request->validate([
        "fromdate"=>"date|required",
        "todate"=>"date|required",
        "type"=>"string|required"
    ]);
    $fromdate = /*Carbon::createFromFormat("Y-m-d",*/$request->fromdate;//)->toString();
    $todate = /*Carbon::createFromFormat("Y-m-d",$request->*/$request->todate;//)->toString();
    $accessories = Article::where("type","=","accessoire")->get("title");
    if($request->type == "boutielles-pleines" ){
        $type = "bouteille-gaz";
        $state= 1;
    }elseif( $request->type == "bouteilles-vides"){
        $type = "bouteille-gaz";
        $state = 0;
    }else{
        $type = "accessoire";
        $state = 0;
    }
    $allMoves = Movement::join("articles","movements.article_id","=","articles.id")->whereBetween("movements.created_at",[$fromdate,$todate])->where("movements.entree",1)->where("articles.type",$type)->where("articles.state",$state)->select("movements.*")->get();
   
    $allMovesOut = Movement::join("articles","movements.article_id","=","articles.id")->whereBetween("movements.created_at",[$fromdate,$todate])->where("articles.type",$type)->where("movements.entree",0)->where("articles.state",$state)->select("movements.*")->get();
   
    return view("manager.history",["accessories"=>$accessories,"allMoves"=>$allMoves,"allMovesOut"=>$allMovesOut]);
}
public function saveBottleMove(Request $request, $action, $state){
    $request->validate([
        "origin"=>"string | required",
        "weight"=>"string |required",
        "label"=>"string | max:250 |required",
        "qty"=>"numeric | required | min:0 |max:1000",
    ]);
    $state = intval($state);
    $weight = floatval($request->weight);
    $region = Auth::user()->region;
    $article = Article::where("type","=","bouteille-gaz")->where("weight","=",$weight)->where("state","=",$state)->first();
   if($article){
    $stock = Stock::where("article_id","=",$article->id)->where("category","=",Auth::user()->role)->where("region","=",$region)->first();

   
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
        $move->origin = $request->origin;
        $move->label = $request->label;
        if($action =="entry"){
            $move->entree = 1;
            $move->sortie = 0;
        }else{

            $move->entree = 0;
            $move->sortie = 1;
        }
        $move->save();

    
        return response()->json(['success' => 'mouvement enregistre avec succes']);
    
        }else{
        return response()->json(["error"=>"stock inexistant"]);
    }    
}else{
    return response()->json(["error"=>"stock inexistant"]);
}
}
//SAVE ACCESSORIES
public function saveAccessoryMoves(Request $request, $action){
    $request->validate([
        "title"=>"string |required",
        "qty"=>"numeric | required",
        "label"=>"string | max:250 |required"
    ]);
    $article = Article::where("title","=",$request->title)->where("type","=","accessoire")->first();
    if($article){

    $stock = Stock::where("article_id","=",$article->id)->where("category","=",Auth::user()->role)->where("region","=",Auth::user()->region)->first();
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
        $move->origin = "null";
        $move->label = $request->label;
        if($action =="entry"){
            $move->entree = 1;
            $move->sortie = 0;
        }else{

            $move->entree = 0;
            $move->sortie = 1;
        }
        $move->save();

    
        return response()->json(["success"=>"mouvement enregistre avec succes"]);
    }
}  
else{
    return response()->json(["error"=>"stock inexistant"]);
}
}
}

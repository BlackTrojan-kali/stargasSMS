<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Article;
use App\Models\Citerne;
use App\Models\Stock;
use App\Models\Movement;
use App\Models\Vracstock;
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
        $vracstocks = Citerne::where("type","mobile")->get();
        return view('manager.dashboard',["stocks"=>$stocks,"accessories"=>$accessories,"vrac"=>$vracstocks]);
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
    $allMoves = Movement::with("fromStock","fromArticle")->where("entree",1)->where("service",Auth::user()->role)->orderBy("created_at","DESC")->get();
    $allMovesOut = Movement::with("fromStock","fromArticle")->where("sortie",1)->where("service",Auth::user()->role)->orderBy("created_at","DESC")->get();
    $vracstocks = Citerne::where("type","mobile")->get();
    return view("manager.history",["accessories"=>$accessories,"allMoves"=>$allMoves,"allMovesOut"=>$allMovesOut,"vrac"=>$vracstocks]);
}
public function showfilteredHistory(Request $request){
    $request->validate([
        "fromdate"=>"date|required",
        "todate"=>"date|required",
        "type"=>"string|required"
    ]);
    $fromdate = /*Carbon::createFromFormat("Y-m-d",*/$request->fromdate;//)->toString();
    $todate = /*Carbon::createFromFormat("Y-m-d",$request->*/$request->todate;//)->toString();
    $accessories = Article::where("type","=","accessoire")->where("service",Auth::user()->role)->get("title");
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
    $allMoves = Movement::join("articles","movements.article_id","=","articles.id")->whereBetween("movements.created_at",[$fromdate,$todate])->where("movements.entree",1)->where("service",Auth::user()->role)->where("articles.type",$type)->where("articles.state",$state)->select("movements.*")->get();
   
    $allMovesOut = Movement::join("articles","movements.article_id","=","articles.id")->whereBetween("movements.created_at",[$fromdate,$todate])->where("articles.type",$type)->where("movements.entree",0)->where("articles.state",$state)->where("service",Auth::user()->role)->select("movements.*")->get();
    $vracstocks = Citerne::where("type","mobile")->get();
    return view("manager.history",["accessories"=>$accessories,"vrac"=>$vracstocks,"allMoves"=>$allMoves,"allMovesOut"=>$allMovesOut]);
}
public function saveBottleMove(Request $request, $action, $state){
    $request->validate([
        "origin"=>"string | required",
        "weight"=>"string |required",
        "label"=>"string | max:250 |required",
        "qty"=>"numeric | required | min:0 |max:1000",
        "bord"=>"string | required "
    ]);
    $state = intval($state);
    $weight = floatval($request->weight);
    $region = Auth::user()->region;
    $service = Auth::user()->role;
    $article = Article::where("type","=","bouteille-gaz")->where("weight","=",$weight)->where("state","=",$state)->first();
   if($article){
    $stock = Stock::where("article_id","=",$article->id)->where("category","=",Auth::user()->role)->where("region","=",$region)->first();
    
   
 if($stock){
    if($action == "entry"){
      $stock->qty = $stock->qty +$request->qty;
      $stockQty =  $stock->qty;
        $stock->save();
        //notify boss
        $actions = new Action();
        $actions->description = Auth::user()->name."[entry] - [{{$request->qty}}] - [{{$article->type}}]- [{{$article->weighy}} KG]";
        $actions->id_user = Auth::user()->id;
    }else{
        $stock->qty = $stock->qty - $request->qty;
        $stockQty =  $stock->qty;
        if($stock->qty <= 0){
            $stock->qty = 0;
            $stockQty =  $stock->qty;
        }
        $stock->save();

          //notify boss
          $actions = new Action();
          $actions->description = Auth::user()->name."[outcome] - [{{$request->qty}}] - [{{$article->type}}]- [{{$article->weighy}} KG]";
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
//delete move

public function deleteMove($id,Request $request){
    $move= Movement::findOrFail($id);
    $stock = Stock::findOrFail($move->stock_id);
    if($move->entree == 1){
        $stock->qty -= $move->qty;
        $stock->save();
    }else if($move->sortie == 1){
        $stock->qty += $stock->qty;
        $stock->save();
    }
    $move->delete();
    return response()->json(["message"=>"movement supprime avec success"]);
}

//SAVE ACCESSORIES
public function saveAccessoryMoves(Request $request, $action){
    $request->validate([
        "title"=>"string |required",
        "qty"=>"numeric | required",
        "label"=>"string | max:250 |required",
        "bord"=>"string | required"
    ]);
    $article = Article::where("title","=",$request->title)->where("type","=","accessoire")->first();
    if($article){

    $stock = Stock::where("article_id","=",$article->id)->where("category","=",Auth::user()->role)->where("region","=",Auth::user()->region)->first();
  
    if($stock){ 
    if($action == "entry"){
        $stock->qty = $stock->qty +$request->qty;
        
        $stockQty = $stock->qty;     
          $stock->save();
          //notify boss
          $actions = new Action();
          $actions->description = Auth::user()->name."[entry] - [{{$request->qty}}] - [{{$article->type}}]- [{{$article->weighy}} KG]";
          $actions->id_user = Auth::user()->id;
          $actions->save();
      }else{
          $stock->qty = $stock->qty - $request->qty;
          
        $stockQty = $stock->qty;     
          if($stock->qty <= 0){
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Article;
use App\Models\Citerne;
use App\Models\Movement;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;

class ProducerController extends Controller
{
    //
    public function show(){
        $stocks = Stock::where("category","production")->get();
        $vracstocks = Citerne::where("type","mobile")->get();
        return view("producer.dashboard",["vrac"=>$vracstocks,"stocks"=>$stocks]);
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
}
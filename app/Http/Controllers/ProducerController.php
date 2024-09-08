<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Article;
use App\Models\Citerne;
use App\Models\Movement;
use App\Models\Producermove;
use App\Models\Relhistorie;
use App\Models\Stock;
use App\Models\Transmit;
use App\Models\Vracmovement;
use App\Models\Vracstock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;

class ProducerController extends Controller
{
    //
    public function show(){
        $stocks = Stock::where("category","production")->get();
        $allvrackstocks = Citerne::all();
        $vracstocks = Citerne::where("type","mobile")->get();
        $fixe  = Citerne::where("type","fixe")->get();
        return view("producer.dashboard",["vrac"=>$vracstocks,"stocks"=>$stocks,"fixe"=>$fixe,"all"=>$allvrackstocks]);
    }
    
    //Etats des mouvements 
    public function showEntry(Request $request,$state,$type,$weight){
        $stocks = Stock::where("category","production")->get();
        $allvrackstocks = Citerne::all();
        $vracstocks = Citerne::where("type","mobile")->get();
        $fixe  = Citerne::where("type","fixe")->get();
        if($weight>0){
            $moves = Movement::join("articles","movements.article_id","articles.id")->where("movements.entree",$type)->where("articles.state",$state)->where("articles.weight",$weight)->select("movements.*")->with("fromArticle")->get();
    
            $moves2 = Movement::join("articles","movements.article_id","articles.id")->where("movements.entree",$type)->where("articles.state",0)->where("articles.weight",$weight)->select("movements.*")->with("fromArticle")->get();
         
            
        }else{
            $moves2= Movement::join("articles","movements.article_id","articles.id")->where("movements.entree",$type)->where("articles.state",0)->where("articles.weight",$weight)->select("movements.*")->with("fromArticle")->get();
            $moves = Movement::join("articles","movements.article_id","articles.id")->where("movements.entree",$type)->where("articles.type","accessoire")->select("movements.*")->get();
        }

        return view("producer.moveEntry",["vrac"=>$vracstocks,"stocks"=>$stocks,"fixe"=>$fixe,"all"=>$allvrackstocks,"moves"=>$moves,"moves2"=>$moves2]);
            
    }

    public function showCiterne(){
        
        $stocks = Stock::where("category","production")->get();
        $vracstocks = Citerne::where("type","mobile")->get();
        $fixe  = Citerne::where("type","fixe")->with("Stock")->get();

        $allvrackstocks = Citerne::all();
        return view("producer.citernes",["vrac"=>$vracstocks,"stocks"=>$stocks,"fixe"=>$fixe,"all"=>$allvrackstocks]);
    }
    public function makeRel($id){
        
        $stocks = Stock::where("category","production")->get();
        $vracstocks = Citerne::where("type","mobile")->get();
        $fixe  = Citerne::where("type","fixe")->with("Stock")->get();
        $fixer = Citerne::where("id",$id)->with("Stock")->first();
    
        $allvrackstocks = Citerne::all();
        return view("producer.citerneRel",["vrac"=>$vracstocks,"stocks"=>$stocks,"fixe"=>$fixe,"fixer"=>$fixer,"id"=>$id,"all"=>$allvrackstocks]);
    }
    public function postRel(Request $request, $id){
        $request->validate([
            "qty" =>"numeric|required"
        ]);
        $fixe = Citerne::where("id",$id)->with("Stock")->first();

        $move = new Relhistorie();
        $move->citerne = $fixe->name;
        $move->stock_theo = $fixe->stock->stock_theo;
        $move->stock_rel = $fixe->stock->stock_rel;
        $move->ecart = $fixe->stock->stock_rel - $fixe->stock->stock_theo;
        
        $move->save();
        $stock = Vracstock::where("id",$fixe->stock->id)->first();
        $stock->stock_rel = $request->qty;
    
        $fixe->save();
        $stock->save();
        return back()->withSuccess("success");
    }

    public function depotage(Request $request){
        $request->validate([
            "fixe"=>"string | required",
            "mobile"=>"string | required",
            "qty"=>"numeric |required",
            "matricule"=>"string |required"
        ]);
        $fixe= intval($request->fixe);
        $mobile = intval($request->mobile);
        $stock = Vracstock::where("citerne_id",$fixe)->first();
        $stock->stock_theo += $request->qty;
        $stock->stock_rel = $stock->stock_theo;
        $stock->save();
        $move = new Vracmovement();
        $move->citerne_id = $fixe;
        $move->vracstock_id = $stock->id;
        $move->qty = $request->qty;
        $move->matricule = $request->matricule;
        $move->save();
        return response()->json(["success"=>"mouvement enregistre avec success"]);         
        
    }
    
public function saveBottleMove(Request $request, $action, $state){
    $request->validate([
        "origin"=>"string | required",
        "weight"=>"string |required",
        "label"=>"string | max:250 |required",
        "qty"=>"numeric | required | min:0 |max:1000",
        "bord"=>"required | string"
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
        $move->bordereau = $request->bord;
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

public function produceGas(Request $request){
    $request->validate([
        "citerne"=>"string | required",
        "type" =>"string |required",
        "qty"=>"numeric | required",
        "bord"=> "string | required",
    ]);
    $type = floatval($request->type);
    $move = new Producermove();
    $citerne = Citerne::where("name",$request->citerne)->first();
    $move->id_citerne = $citerne->id;
    $move->type = $type;
    $move->qty = $request->qty;
    $move->bordereau = $request->bord;
    //$move->save();
    
    $article = Article::where("weight",$type)->where("state",0)->with("hasStock")->first();
    $article2 = Article::where("weight",$type)->where("state",1)->with("hasStock")->first();
    if($article){
    if($article->hasStock[1]->qty < $request->qty) {
        return response()->json(["error"=>"stock insufisant"]);
    }else{
        $article->hasStock[1]->qty -= $request->qty;
        $article->hasStock[1]->save();
        $article2->hasStock[1]->qty += $request->qty;
        $article2->hasStock[1]->save();
        $move->save();
        return response()->json(["success"=>"mouvement insere avec success"]);
    }
    }else{
        return response()->json(["error"=>"stock inexistant"]);
    }
    //return response()->json(["success"=>"mouvement enregistre avec succes"]);
    
}
public function transmitGas(Request $request){
    $request->validate([
        "state"=>"required| string",
        "type"=>"required| string",
        "qty"=>"required| numeric",
        "bord"=>"required | string"
    ]);
    $state = intval($request->state);
    $type= floatval($request->type);
    $move = new Transmit();
    $move->state = $state;
    $move->type = $type;
    $move->qty = $request->qty;
    $move->bordereau = $request->bord;
    
    $article = Article::where("state",$state)->where("weight",$type)->with("hasStock")->first();
    if($article){
        if($article->hasStock[1]->qty < $request->qty){
            return response()->json(["error"=>"stock insufisant"]);
        }else{
            $move->save();
            $article->hasStock[1]->qty -= $request->qty;
            $article->hasStock[1]->save();
            return response()->json(["success"=>"insersion reussie"]);
        }
    }else{
        return response()->json(["error"=>"stock inexistant"]);
    }
       
}
}

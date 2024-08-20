<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Citerne;
use App\Models\Receive;
use App\Models\Vracmovement;
use App\Models\Vracstock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CiternController extends Controller
{
    //
//RELEVES RELEVES RELEVES RELEVES RELEVES
public function showReleve(Request $request){
    $accessories = Article::where("type","=","accessoire")->get("title");
    $vracstocks = Citerne::where("type","mobile")->get();
    $receptions = Receive::with("citerne")->get();
    if(Auth::user()->role == "magasin"){
    return view("manager.reception",["accessories"=>$accessories,"vrac"=>$vracstocks,"receptions"=>$receptions]);
    }else{
        return view("producer.reception",["receptions"=>$receptions,"vrac"=>$vracstocks]);
    }
}
    public function showFormAddCitern(Request $request){
        return view("addCitern");
    }
    public function moveGpl(Request $request){
        $request->validate([
            "citerne"=>"string| required",
            "qty"=>"numeric| required",
            "matricule"=>"string | required",
            "livraison"=>"string | required"
        ]);
        $citerne = Citerne::where("name",$request->citerne)->first();
        $move =new Receive();
        $move->id_citerne = $citerne->id;
        $move->livraison =$request->livraison;
        $move->matricule = $request->matricule;
        $move->qty = $request->qty;
        $move->receiver = Auth::user()->role;
        $move->save();
         
        return response()->json(["success"=>"mouvement inserer avec success"]);
    }
    public function validateFormAddCitern(Request $request){
        $request->validate([
            "name"=>"string| required |unique:citernes,name",
            "capacity"=>"numeric| required ",
            "type"=>"string |required",
        ]);
        $citern = new Citerne();
        $citern->name = $request->name;
        $citern->capacity = $request->capacity;
        $citern->type = $request->type;
        $citern->save();
        $citern =  Citerne::where("name",$request->name)->first();
        if($citern->type == "fixe"){
                $vracstock = new Vracstock();
                $vracstock->citerne_id = $citern->id;
                $vracstock->stock_theo = 0;
                $vracstock->stock_rel = 0;
                $vracstock->save();
    }

       return back()->withSuccess("citern inseree avec success");
    }
    public function delete(Request $request,$id){
        $article = Citerne::findOrFail($id);
        $article->delete();
        return response()->json(["message"=>"element supprime avec succes"]);
    }
}

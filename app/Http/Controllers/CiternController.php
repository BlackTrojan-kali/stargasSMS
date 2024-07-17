<?php

namespace App\Http\Controllers;

use App\Models\Citerne;
use App\Models\Vracmovement;
use App\Models\Vracstock;
use Illuminate\Http\Request;

class CiternController extends Controller
{
    //

    public function showFormAddCitern(Request $request){
        return view("addCitern");
    }
    public function moveGpl(Request $request,$action){
        $request->validate([
            "citerne"=>"string| required",
            "qty"=>"numeric| required",
            "label"=>"string | max:300"
        ]);
        $citerne = Citerne::where("name",$request->citerne)->first();
        $vracstock = Vracstock::where("citerne_id",$citerne->id)->first();
        $move =new Vracmovement();
        $move->vracstock_id = $vracstock->id;
        $move->citerne_id = $citerne->id;
        $move->type_move = $action;
        if($action=="entry"){
            $vracstock->stock_theo += $request->qty;
        }else{
            if($vracstock->stock_theo <= 0 ){
                $vracstock->stock_theo = 0;
            }else{
                $vracstock->stock_theo -= $request->qty;
            }
        }
        $vracstock->save();
        $move->qty = $request->qty;
        
        $move->label = $request->label;
        $move->save();
         
        return response()->json(["success"=>"mouvement inserer avec success"]);
    }
    public function validateFormAddCitern(Request $request){
        $request->validate([
            "name"=>"string| required |unique:citernes,name",
            "type"=>"string |required",
        ]);
        $citern = new Citerne();
        $citern->name = $request->name;
        $citern->type = $request->type;
        $citern->save();
        $citern =  Citerne::where("name",$request->name)->first();
        $vracstock = new Vracstock();
        $vracstock->citerne_id = $citern->id;
        $vracstock->stock_theo = 0;
        $vracstock->stock_rel = 0;
        $vracstock->save();

       return back()->withSuccess("citern inseree avec success");
    }
    public function delete(Request $request,$id){
        $article = Citerne::findOrFail($id);
        $article->delete();
        return response()->json(["message"=>"element supprime avec succes"]);
    }
}

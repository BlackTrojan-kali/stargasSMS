<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Citerne;
use App\Models\Receive;
use App\Models\Stock;
use App\Models\Vente;
use App\Models\Versement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BossController extends Controller
{
    //
    public function index(){
        $stocks = Stock::with("article")->get();
        $mobile = Citerne::where("type","mobile")->get();
        return view("ControllerDashboard",["stocks"=>$stocks,"mobile"=>$mobile]);
    }
    
    public function generate_receive_pdf(Request $request){
        $request->validate([
            "depart"=>"date | required",
            "fin" =>"date | required",
            "citerne"=>"string | required",
            "region" =>"string | required",
            "service"=>"string | required"
        ]);
        $fromDate = $request->depart;
        $toDate = $request->fin;
        $idCitern = intval($request->citerne);
        $receive = Receive::where("id_citerne",$idCitern)->where("receiver",$request->service)->whereBetween("created_at",[$request->depart,$request->fin])->with("citerne")->get();
 
        $pdf = Pdf::loadview("RecievePdf",["receive"=>$receive,"fromDate"=>$fromDate,"toDate"=>$toDate]);
        return  $pdf->download("historique des receptions.pdf",["region"=>$request->region]);
    }
    
public function generate_sale_state(Request $request){
    $request->validate(
        [
            "depart"=>"date | required",
            "fin"=>"date | required",
            "name"=>"string | nullable",
            "sale"=>"string |required",
            "region"=>"string | required",
        ]
        );
        $fromDate = $request->depart;
        $toDate = $request->fin;
if($request->name){

    $sales = Vente::whereBetween("created_at",[$request->depart, $request->fin])->where("type",$request->sale)->where("region",$request->region)->where("customer",$request->customer)->get();
}else{
    $sales = Vente::whereBetween("created_at",[$request->depart, $request->fin])->where("type",$request->sale)->where("region",$request->region)->get();
}
$pdf = Pdf::loadview("salesPdf",["fromDate"=>$fromDate,"toDate"=>$toDate,"sales"=>$sales,"type"=>$request->sale]);    
return $pdf->download(Auth::user()->role."".$request->region.$fromDate.$toDate.".pdf");
}

public function generate_versements_state(Request $request){
    $request->validate(
        [
            "depart"=>"date | required",
            "fin"=>"date | required",
            "bank"=>"string | required ",
            "region"=>"string | required",
            "service"=>"string | required",
        ]
        );
        $fromDate = $request->depart;
        $toDate = $request->fin;
if($request->bank == "all"){
    $afb = Versement::where("bank","AFB")->where("region",$request->region)->where("service",$request->service)->whereBetween("created_at",[$fromDate,$toDate])->get();
    $cca = Versement::where("bank","CCA")->where("region",$request->region)->where("service",$request->service)->whereBetween("created_at",[$fromDate,$toDate])->get();
    $pdf = Pdf::loadview("versementPdfAll",["fromDate"=>$fromDate,"toDate"=>$toDate,"afb"=>$afb,"cca"=>$cca,"bank"=>$request->bank])->setPaper("A4",'landscape');    
    return $pdf->download($request->role."versementsglobal".$request->region.$fromDate.$toDate.$request->bank.".pdf");
}else{
    $deposit = Versement::where("bank",$request->bank)->where("region",$request->region)->where("service",$request->service)->whereBetween("created_at",[$fromDate,$toDate])->get();
    $pdf = Pdf::loadview("versementPdf",["fromDate"=>$fromDate,"toDate"=>$toDate,"deposit"=>$deposit,"bank"=>$request->bank]);    
    return $pdf->download(Auth::user()->role."versements".Auth::user()->region.$fromDate.$toDate.$request->bank.".pdf");
}
}
}

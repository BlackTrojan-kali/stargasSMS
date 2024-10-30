<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Versement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    //
    public function index()
    {
        $stocks = Stock::with("article")->get();
        $region = Region::all();
        return view("director.dashboard", ["stocks" => $stocks, "region" => $region]);
    }
    public function getCAGlobal()
    {
        $year = 2024;
        $region = Region::all();
        $versements = Versement::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois,region,bank,SUM(montant_gpl) as total_gpl')->whereYear("created_at", $year)->groupBy("annee", "mois", "bank", "region")->get();

        return view("director.globalCA", ["versements" => $versements, "region" => $region]);
    }
    public function getCAGlobalRegion($regionHere)
    {
        $year = 2024;
        $region = Region::all();
        $versements = Versement::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois,region,bank,SUM(montant_gpl) as total_gpl')->where("region", $regionHere)->whereYear("created_at", $year)->groupBy("annee", "mois", "region", "bank")->get();
    
        return view("director.CAPerRegion", ["versements" => $versements, "region" => $region, "here" => $regionHere]);
    }
}
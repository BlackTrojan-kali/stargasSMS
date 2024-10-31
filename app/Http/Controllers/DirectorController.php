<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Vente;
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
    //versemnents gpl
    public function getCAGlobal()
    {
        $year = 2024;
        $region = Region::all();
        $versements = Versement::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois,bank,SUM(montant_gpl) as total_gpl')->whereYear("created_at", $year)->groupBy("annee", "mois", "bank",)->get();
        $type = "GPL";
        return view("director.globalCA", ["versements" => $versements, "region" => $region, "type" => $type]);
    }
    //function consigne versement
    public function getCAGlobalConsigne()
    {
        $year = 2024;
        $region = Region::all();
        $versements = Versement::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois,bank,SUM(montant_consigne) as total_gpl')->whereYear("created_at", $year)->groupBy("annee", "mois", "bank",)->get();
        $type = "Consigne";
        return view("director.globalCA", ["versements" => $versements, "region" => $region, "type" => $type]);
    }
    public function getCAGlobalRegion($regionHere)
    {
        $year = 2024;
        $region = Region::all();
        $versements = Versement::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois,region,bank,SUM(montant_gpl) as total_gpl')->where("region", $regionHere)->whereYear("created_at", $year)->groupBy("annee", "mois", "region", "bank")->get();
        $type = "GPL";
        return view("director.CAPerRegion", ["versements" => $versements, "region" => $region, "here" => $regionHere, "type" => $type]);
    }

    public function getCAGlobalRegionConsigne($regionHere)
    {
        $year = 2024;
        $region = Region::all();
        $versements = Versement::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois,region,bank,SUM(montant_consigne) as total_gpl')->where("region", $regionHere)->whereYear("created_at", $year)->groupBy("annee", "mois", "region", "bank")->get();
        $type = "consigne";
        return view("director.CAPerRegion", ["versements" => $versements, "region" => $region, "here" => $regionHere, "type" => $type]);
    }

    //VENTES CONSOLIDEES
    public function globalSales()
    {
        $year = 2024;
        $region = Region::all();
        $ventes = Vente::selectRaw("YEAR(created_at) as annee, MONTH(created_at) as mois,SUM(prix_total) as total_gpl")->where("type", "vente")->groupBy("annee", "mois", "type")->get();
        dd($ventes);
    }
}
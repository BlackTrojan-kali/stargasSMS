<?php

namespace App\Http\Controllers;

use App\Exports\MovementExport;
use App\Exports\ReceptionExport;
use App\Http\Controllers\Controller;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    //
    public function export(Request $request)
    {
        return Excel::download(new MovementExport($request->depart, $request->fin, $request->type, $request->state, $request->service, Auth::user()->region), "movements.xlsx");
    }
    public function exportReceives(Request $request)
    {
        $request->validate([
            "depart" => "date | required",
            "fin" => "date | required",
            "citerne" => "string | required"
        ]);
        $idCitern = intval($request->citerne);

        return Excel::download(new ReceptionExport($request->depart, $request->fin, $idCitern, "citerne", Auth::user()->region, Auth::user()->role,), "receives" . $request->depart . ".xlsx");
    }
}

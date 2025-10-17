<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZonaController extends Controller
{
    //

    public function obtenerZonas(){
        $zona = new Zona();
        $valores = $Zona::all();
        $respuesta = [
            "succes" => true,
            "msg" => "Valores devueltos por el EndPoint",
            "data" => $valores,
            "error" => "",
            "total" => sizeof($valores)
        ];

        
    }

    public function obtenerZona(){
        $zona = new Zona();
        $valores = $Zona->where('id_zona',$idzona)->get();
        $respuesta = [
            "succes" => true,
            "msg" => "Valores devueltos por el EndPoint",
            "data" => $valores,
            "error" => "",
            "total" => sizeof($valores)
        ];

        return response()->json($respuesta,200);
    }
}

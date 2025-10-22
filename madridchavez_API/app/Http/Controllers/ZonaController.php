<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zona;

class ZonaController extends Controller
{
    //

    public function obtenerZonas(){

        $Zona = new Zona();

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        $valores = $Zona::all();

        //VERIFIFACION DE EXISTENCIA DE DATOS
        if(!empty($valores)){
            $satisfactorio = true;
            $estado = 200;
            $mensaje = "Valores encontrados";
            $errores = [
                "code" => 200,
                "msg" => ""
            ];
        }else{
            //DATOS NO ENCONTRADOS
            $satisfactorio = false;
            $estado = 404;
            $mensaje = "No se han encontrado valores";
            $errores = [
                "code" => 404,
                "msg" => "Datos no encontrados"
            ];
        }

        //VARIABLE DE SALIDA
        $valores = $Zona::all();
        $respuesta = [
            "succes" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "error" => $errores,
            "total" => sizeof($valores)
        ];

        //SE RETORNA EL MENSAJE AL USUARIO
        return response()->json($respuesta,200);
    }

    public function obtenerZona(int $idzona = 0){

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        if ($idzona > 0){
            $Zona = new Zona();
            $valores = $Zona->where('id_zona',$idzona)->get();

            //VERIFIFACION DE EXISTENCIA DE DATOS
        if(!empty($valores)){
            $satisfactorio = true;
            $estado = 200;
            $mensaje = "Valores encontrados";
            $errores = [
                "code" => 200,
                "msg" => ""
            ];
        }else{
            //DATOS NO ENCONTRADOS
            $satisfactorio = false;
            $estado = 404;
            $mensaje = "No se han encontrado valores";
            $errores = [
                "code" => 404,
                "msg" => "Datos no encontrados"
            ];
        }//FIN DEL IF(!EMPTY(VALORES)){

        }else{
            //NO SE HA ENVIADO UN VALOR PARA EL PARAMETRO $idzona
            $satisfactorio = false;
            $estado = 400;
            $mensaje = "No se ha enviado el parametro obligatorio";
            $errores = [
                "code" => 400,
                "msg" => "El identificador de la zona esta vacio"
            ]; 
        } //FIN DEL IF($IDZONA > 0){ 


        //SE CREA LA VARIABLE DE SALIDA
        $respuesta = [
            "succes" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "error" => $errores,
            "total" => sizeof($valores)
        ];
        
        return response()->json($respuesta,200);
    }
}
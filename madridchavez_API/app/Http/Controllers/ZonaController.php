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
    
    public function obtenerZonaPais(int $idpais = 0){

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        if ($idpais > 0){
            $Zona = new Zona();
            $valores = $Zona->where('id_pais',$idpais)->get();

            //VERIFIFACION DE EXISTENCIA DE DATOS
        if(!empty($valores)){
            //Si SE ENCONTRARON DATOS
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
            //NO SE HA ENVIADO UN VALOR PARA EL PARAMETRO $idpais
            $satisfactorio = false;
            $estado = 400;
            $mensaje = "No se ha enviado el parametro obligatorio";
            $errores = [
                "code" => 400,
                "msg" => "El identificador del país esta vacio"
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

    public function crearZona(Request $request){

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        $validacion = $request->validate([
            "idpais" => "required|integer|gt:0",
            "nombrezona"=>"required|max:50"
        ]);
        
        $Zona = new Zona();

        //SE ASIGNA A CADA ATRIBUTO DE LA TABLA LOS CAMPOS DEL FORMULARIO
        $Zona->id_pais = $request->idpais;
        //------(Campo de BD)-----------(Campo de form)
        $Zona->nombre_zona = $request->nombrezona;
        //------(Campos de BD)-----------(Campo de form)

        $insertado = $Zona->save(); //SE HACE INSERT A LA BASE DE DATOS

        if($insertado){
            
            $ultimoinsertado = $Zona->id_zona;
            $datosinsertados = $this->obtenerZona($ultimoinsertado);
        
            $satisfactorio = true;
            $estado = 200;
            $mensaje = "Se guardaron los datos correctamente";
            $errores = [
                "code" => 200,
                "msg" => ""
            ];
        
        }else{
            $satisfactorio = false;
            $estado = 500;
            $mensaje = "Hubo un problema al guardar los datos";
            $errores = [
                "code" => 500,
                "msg" => "No se pudo hacer insert a la tabla Zona"
            ]; 
        }

        $respuesta = [
            "succes" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $datosinsertados->original["data"][0],
            "error" => $errores,
            "total"=> $datosinsertados->original["total"]

            //"total" => sizeof($valores)
        ];

        //SE MUESTRA EL MENSAJE AL USUARIO
        return response()->json($respuesta,$estado);



    }
}
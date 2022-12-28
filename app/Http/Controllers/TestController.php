<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Return_;

class TestController extends Controller
{
    public $user;
    public $dni;
    private $atencion;
    private $establecimiento;
    use traitservicio;
    public function __construct(Request $request)
    {
       //$this->middleware('auth');
       
       if(!$request->session()->has('user') ) {
        Redirect::to('/errorlink',)->send();
        throw new Exception('error al autenticar');
       }else{
        $this->user=$request->session()->get('user');
        $this->atencion=$request->session()->get('num_atencion');
        $this->establecimiento=$request->session()->get('num_establecimiento');
        if(!$request->session()->has('dni')){
            Redirect::to('/errorlink',)->send();
            //return view('error',['message'=>'Inicia session otra vez a travez del link']);
            throw new Exception('error al autenticar');
        }else{
            $this->dni=$request->session()->get('dni');
        } 
       }
    }
    
    protected function enviar_resultado(Request $request){
        try{
            $request->validate([
                'pregunta'=>'required|numeric',
                'modulo'=>'required|numeric',
                'submodulo'=>'required|numeric',
                'tipo'=>'required',
                'respuesta'=>'required_if:tipo,==,1',
                'observacion'=>'required_if:tipo,==,2'
            ]);
            $result=$request->respuesta;
            $observacion=$request->observacion;
            if($request->tipo==2){
                $observacion=$request->observacion;
                $result='';
            }
            if($request->tipo==3){
                if($request->respuesta=='' && $request->observacion==''){
                    return response()->json(['message'=>'Los campos estan vacios'],405);
                }
                $observacion=$request->observacion;
                $result=$request->respuesta;
            }
            $array=[
                'op'=>'editar_pregunta',
                'usuariows'=>'app',
                'clavews'=>'fa0801',
                'atencion'=>"$this->atencion",
                'establecimiento'=>"$this->establecimiento",
                'pregunta'=>"$request->pregunta",
                'resultado'=>"$result",
                'observacion'=>"$observacion",
                'modulo'=>"$request->modulo",
                'submodulo'=>"$request->submodulo",    
            ];
            //dd($array);
            $response=$this->requestdata($array);
            return $response;
        }catch(Exception $e){
            return response()->json(['message'=>'Error al enviar respuesta'],405);
        }
    }


    protected function enviar_resuestas(Request $request){
        $request->validate([
            'preguntas'=>'required',
        ]);
        try{
            json_encode($request);
            $array=[];
            foreach($request->preguntas as $pre){
                if($pre['pregunta']=='' || $pre['respuesta']=='' || $pre['pregunta']==null || $pre['respuesta']==null){
                    return response()->json(['message'=>'Error al verificar respuestas'],405);
                }
                else{
                    $array[]=[
                        'atencion'=>$this->atencion,
                        'establecimiento'=>$this->establecimiento,
                        'pregunta'=>$pre['pregunta'],
                        'respuesta'=>$pre['respuesta'],
                    ];
                }
            }
           return response()->json(['message'=>'enviado','preguntas'=>$array]);
        }catch(Exception $e){
            response()->json(['message'=>'Error en el servidor'],405);
        }
      
    }


    
    public function test(Request $request,$id){
        
        try{
        $params=[
            'op'=>'listar_examenpsicologico',
            'usuariows'=>'app',
            'clavews'=>'fa0801',
            'atencion'=>$this->atencion,
            'establecimiento'=>$this->establecimiento, 
        ];
        $cuestionario=$this->requestdata($params);

        $cuestionario=$cuestionario['listar_examenpsicologico'];

       // return $cuestionario;
 
        $datos=[];
        foreach($cuestionario as $cuest){
            if($cuest['submodulo']==$id){
                $datos=[
                    'nombre'=>$cuest['denominacion'],
                    'preguntas'=>14,
                    'estado'=>$cuest['estado']=='PENDIENTE'?0:1,
                    'id'=>$cuest['submodulo'],
                    'tiempo'=>$cuest['tiempo'],
                    'modulo'=>$cuest['modulo'],
                    'tipo'=>1,
                    'atencion'=>$cuest['atencion'],
                    'establecimiento'=>$cuest['establecimiento'],
                ];   
            }
        }

        if($datos['atencion']!=$this->atencion || $datos['establecimiento']!=$this->establecimiento){
            throw new Exception('La atencion no coincide');
        }
        $descripcion='Resuelva el test correctamente';
        $preguntas=$this->obtener_preguntas($datos['modulo'],$id);
        if(count($preguntas)>0){
            $des=$preguntas[0]['descripcion'];
            if($des!=''){
                $descripcion=$des;
            }
        }
        $num_preguntas=count($preguntas);
        $num_respuestas=0;
        foreach($preguntas as $pre){
            if($pre['respuesta']!='' || $pre['observacion']!=''){
                $num_respuestas++;
            }
        }
        
       // return $preguntas;
        return view('test',['datous'=>$request->session()->all(),'preguntas'=>$preguntas,'num_preg'=>$num_preguntas,'num_res'=>$num_respuestas,'desc'=>$descripcion,'test'=>$datos,'user'=>$this->user,'dni'=>$this->dni]);
        }catch(Exception $e){
            throw new Exception('ERROR');
        }
    }


    public function obtener_preguntas($modulo,$submodulo){
        $params=[
            'op'=>'listar_examenpsicologicopreguntas',
            'usuariows'=>'app',
            'clavews'=>'fa0801',
            'atencion'=>$this->atencion,
            'establecimiento'=>$this->establecimiento, 
            'modulo'=>$modulo,
            'submodulo'=>$submodulo,
        ];
        $preguntas=$this->requestdata($params);
        $pregu=[];
        $prueba=$preguntas['listar_examenpsicologicopreguntas'][0];
        if($prueba['numpregunta']==null || $prueba['denominacion']==null ){
            return [];
        }
        $opciones=$this->obtener_opciones($modulo,$submodulo);
        $opciones=$opciones['listar_examenpsicologicoopciones'];
        $grup=0;
        $grupnom=false;
        $num=0;
        foreach($preguntas['listar_examenpsicologicopreguntas'] as $pre){
            $opcion =$this->buscar_opcione($pre['numpregunta'],$opciones);
           if($pre['grupo_id']!=$grup){
              $grupnom=true;
              $grup=$pre['grupo_id'];
              $num++;
           }else{
              $grupnom=false;
           }
            $pregu[]=[
                'id'=>$pre['numpregunta'],
                'pregunta'=>$pre['denominacion'],
                'numopcion'=>$pre['numopcion'],
                'respuesta'=>$pre['respuesta'],
                'descripcion'=>$pre['descripcion'],
                'tipo'=>$pre['tipo_respuesta'],
                'observacion'=>$pre['observacion'],
                'opciones'=>$opcion,
                'grupo_id'=>$pre['grupo_id'],
                'nombre_grupo'=>$grupnom?$pre['grupo_nombre']:'',
                'grupo_vacio'=>'Grupo '.$num,
            ];
        }
        return $pregu;//$pre['grupo_nombre']
    }
    public function buscar_opcione($preg,$opciones){
        $option=[];
        foreach($opciones as $op){
            if($op['numpregunta']==$preg){
                $option[]=[
                    'id'=>$op['idopcion'],
                    'denominacion'=>$op['denominacion'],
                    'valor'=>$op['valor'],
                ];
            }
        }
        return $option;
    }
    public function obtener_opciones($modulo,$submodulo){
        $params=[
            'op'=>'listar_examenpsicologicoopciones',
            'usuariows'=>'app',
            'clavews'=>'fa0801',
            'atencion'=>$this->atencion,
            'establecimiento'=>$this->establecimiento, 
            'modulo'=>$modulo,
            'submodulo'=>$submodulo,
        ];
       
        $opciones=$this->requestdata($params);

        return $opciones;
    }
}

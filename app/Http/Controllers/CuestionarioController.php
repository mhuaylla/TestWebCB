<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class CuestionarioController extends Controller
{
    use traitservicio;
    
    public function obtenerdatos(Request $request){
        //
        try{
            $request->validate([
                'dni'=>'required|numeric',
            ]);
            $params=[
                'dni'=>$request->dni,
                'op'=>'obtener_pacientedni',
                'usuariows'=>'app',
                'clavews'=>'fa0801',
            ];
            $ses=$this->sesssion($params,1,$request);
            if(isset($ses['message'])){
                return response()->json(['message'=>$ses['message']],405);
            }
            return $ses;
           
        }catch(Exception $e){
            return response()->json(['message'=>'Error al obtener datos'],405);
        }
        
    }
    public function sesssion($params,$tipo, Request $request){
        try{
            $response=$this->requestdata($params);
            $userdata=[];
            if(isset($response['obtener_pacientedni'])){
                $userdata=$response['obtener_pacientedni'][0];
            }
            if(isset($response['obtener_paciente_ae'])){
                $userdata=$response['obtener_paciente_ae'][0];
            }
            if(count($userdata)==0){
                array('message'=>'Error al encontrar datos del paciente');
            }
            if($userdata['nombre']==null || $userdata['aten_numero']==null || $userdata['aten_establecimiento']==null){
                return array('message'=>'No se encontraron datos del paciente');
            }
            $credentials=[
                "user"=>$userdata['nombre'],
                "dni"=>$userdata['documento_identidad'],
                'empresa'=>$userdata['empresa_razon_social'],
                'fecha'=>$userdata['fecha'],
                'atencion'=>$userdata['numatencion'],
                'plan'=>$userdata['plan_denominacion'],
                'num_atencion'=>$userdata['aten_numero'],
                'num_establecimiento'=>$userdata['aten_establecimiento'],
                'ocupacion'=>$userdata['ocupacion'],
                'edad'=>$userdata['edad'],
                'sexo'=>$userdata['sexo'],
                'tipo'=>$tipo,
            ];
            $request->session()->put($credentials);
            ///$request->session()->put(['dni'=>$request->dni]);
            return $request->session()->all();
        }catch(Exception $e){
            //return $e;
            return array('message'=>'Error en al obtener datos del paciente');
        }

    }

    public function logout(Request $request){
        //Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/iniciar/session/dni/credentials');
    }

    public function decrypt($strg_e){
          //algoritmo de desncriptado del token
          $strg_r = '';
          for ($i = 0; $i < strlen($strg_e); $i += 2) {
              $strg = substr($strg_e, $i, 2);

              $strg_d = substr($strg, 0, 1);
              $strg_b = substr($strg, 1, 1);

              $strg_c = ord($strg_d);
             // return $strg_b;
              $strg_a =(int)$strg_c-18-(int)$strg_b;

              $strg_a = chr($strg_a);

              $strg_r = $strg_r . $strg_a;
              // echo $i.'-';
          }

          return $strg_r;
    }

    public function datos_link($strg_e ,Request $request){
      
         $arr=explode('_',$strg_e);
         $ate='0';
         $esta='0';
         try{
            if(count($arr)>1){
                $ate=$this->decrypt($arr[0]);
                $esta=$this->decrypt($arr[1]);
            }
            $this->logout($request);
            // $dni=base64_decode($token);
            $params=[
                'atencion'=>"$ate",
                'establecimiento'=>"$esta",
                'op'=>'obtener_paciente_ae',
                'usuariows'=>'app',
                'clavews'=>'fa0801',
            ];
            $ses=$this->sesssion($params,2,$request);
        
            if(isset($ses['message'])){
                return view('error',['message'=>$ses['message']]);
                //throw new Exception('Error al obtener datos');
            }
            else{
                return redirect('/inicio');
            }
        
        }catch(Exception $e){
            return view('error',['message'=>'Error al obtener datos del paciente']);
        }
    }
}

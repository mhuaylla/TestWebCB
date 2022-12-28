<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait traitservicio 
{
    public function requestdata($params)
    {
        try{
        $response = Http::asForm()->post('http://190.117.23.142:9012/webApiSql/wa/atencion.php',$params);
        return $response;
        }catch(Exception $e){
            return array(['message'=>'Error al consultar la api']);
        }
        
    }
}

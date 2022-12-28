<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @extends('layouts')

    @section('cuerpo')
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
        </div>
    </div>
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="{{url('/inicio')}}" class="logo">
                            <img src="{!! asset('assets/images/logo.png')!!}" alt="">
                    
                        </a>
                        @if($user['tipo']==1)
                        <ul class="nav">
                            <li>
                                <a href="{{route('logout')}}">Terminar session</a>
                                
                            </li>
                        </ul>
                        <a class="menu-trigger">
                            <span>Menu</span>
                        </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <section id="section-1" style="height: 100% !important; min-height: 400px; background-color:#fff;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="more-info" style="box-shadow: 1px 1px 44px -10px rgb(0 0 0 / 47%);">
                        <div class="row">
                        <div class="col-lg-12">
                            <i class="fa fa-user"></i>
                            <h4 style="font-size: 20px; margin-top: 15px;">DATOS DEL PACIENTE</h4>
                        </div>
                        
                        <div class="col-lg-6 col-sm-6 col-6">
                            <h4><span>Apellidos y nombres:</span><br>{{$user['user']}}</h4>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-6">
                            
                            <h4><span>Fecha:</span><br>{{$user['fecha']}}</h4>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-6">
                            
                            <h4><span>N° atencion:</span><br>{{$user['atencion']}}</h4>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6">
                            <h4><span>Plan de atencion:</span><br>{{$user['plan']}}</h4>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6">
                            <h4><span>Empresa:</span><br>{{$user['empresa']}}</h4>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="visit-country">
      <div class="container">
        <div class="row">
          <div class="col-lg-5">
            <div class="section-heading">
              @if(count($cuestionario)>0)
              <h2>Lista de pruebas</h2>
              @else
              <h2>No tienes pruebas</h2>
              @endif
              <p></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="items">
              <div class="row">
                @foreach ($cuestionario as $cuest)
                <div class="col-lg-12">
                  <div class="item">
                    <div class="row">
                      <div class="col-lg-10 ">
                        <div class="right-content">
                          <h4>{{$cuest['nombre']}}</h4>
                            <div>
                             
                              <div class="main-button" > 
                                    
                                @if($cuest['avance']==100)
                                <a href="/test/{{$cuest['id']}}" style="background-color: rgb(98, 234, 98);border:none;">Realizado</a>
                                @else
                                <a href="/test/{{$cuest['id']}}">Realizar</a> 
                                @endif  
                              </div>
                            </div>
             
                          <ul class="info">
                            <li><i class="fa fa-question"></i>preguntas:{{$cuest['preguntas']}}</li>
                            @if($cuest['tiempo']>0)
                            <li><i class="fa fa-clock"></i>Tiempo:{{$cuest['tiempo']}} minutos</li>   
                            @else
                            <li ><i class="fa fa-clock"></i>Tiempo: Indefinido</li>        
                            <li style="padding-top: 10px">
                              <div role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="--value:{{$cuest['avance']}}"></div>
                            </li>
                            @endif
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    @stop
</body>
</html>
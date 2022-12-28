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
                        <a href="{{url('/')}}" class="logo">
                            <img src="assets/images/logo.png" alt="">
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>


    <section id="section-1">
        <div class="content-slider">
          <input type="radio" id="banner1" class="sec-1-input" name="banner" checked>
          <div class="slider">
            <div id="top-banner-1" class="banner">
              <div class="banner-inner-wrapper header-text">
                <div class="main-caption">
                  <h2>Cuestionario Médico</h2>

                      <div class="row">
             
                        <div class="col-lg-12">
                          <form id="formulariodni" method="POST" action="{{route('obtener')}}">
                            @csrf
                 
                            <div class="row">
            
                              <div class="col-lg-12">
                                  <fieldset>             
                                      <input 
                                      type="text" name="dni" class="dniinput" placeholder="DNI del paciente" autocomplete="of" required=""
                                      style="
                                      height:35px;
                                      background-color: #fff;
                                      border-radius: 23px;
                                      border: 1px solid #e0e0e0;
                                      padding: 0px 20px;
                                      cursor: pointer;
                                      margin-bottom: 30px;"
                                      >     
                                  </fieldset>
                              </div>

                              <div class="border-button">
                                <button type="submit"  class="enviar" id="btn_enviar" >
                                    <div id='loader'>Enviar </div>
                                </button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
              
                      
                    
                      
                </div>
          
              </div>
            </div>
          </div>
        </div>
      </section>



@stop
<!DOCTYPE html>

<html lang="es" xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php

        $entorno = App::environment();

        /*

            08dic2020

            Modifiqué el archivo Mix.php linea 46 para que pueda encontrar el manifiesto del cambio de versionado para app.js y app.css

            en /repuestos/vender/laravel/framework/src/illuminate/Foundation/Mix.php

        */

    @endphp

    <link rel="icon" href="{{asset('storage/imagenes/favicon1.png')}}" type="image/png"  />

    @php

        if($entorno=='local'){

            echo "<link href=\"".asset('css/app.css')."\" rel='stylesheet'>";

            echo "<link href=\"".asset('css/dev.css')."\" rel='stylesheet'>";

        }elseif($entorno=='production'){

            echo "<link href=\"".mix('app.css','dist')."\" rel='stylesheet'>";

            echo "<link href=\"".mix('dev.css','dist')."\" rel='stylesheet'>";

        }

    @endphp



    

    <title>@yield('titulo','Default') | {{config('app.name')}}</title>

    <section>

        @yield('javascript')

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script>
            $("a[href='#top']").click(function() {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
            });
            $(document).ready(function(){
                $('#listado-permisos-detalle').css('display','none');
        //Check to see if the window is top if not then display button
                $(window).scroll(function(){
                // Show button after 100px
                var showAfter = 100;
                if ($(this).scrollTop() > showAfter ) { 
                $('.back-to-top').fadeIn();
                } else { 
                $('.back-to-top').fadeOut();
                }
                });
                 //Click event to scroll to top
                $('.back-to-top').click(function(){
                $('html, body').animate({scrollTop : 0},800);
                return false;
                });
            });

            function dameofertas(){
                console.log('BUSCANDO ...');
                let url = '/ventas/dameofertas';

                $.ajax({
                    type:'get',
                    url: url,
                    success: function(ofertas){
                        $('#modal_body_ultimas_ofertas').empty();
                        var contador = 0;
                        ofertas.forEach(e => {
                            let precio_format = Number(e.precio_venta).toFixed(0);
                            precio_total = precio_format;
                            $('#modal_body_ultimas_ofertas').append(`
                            <div class="col-md-3">
                            
                            <img src="/storage/`+e.urlfoto+`" style="width: 200px; height: 200px;"/><br>
                            <span>`+e.descripcion+`</span><br>
                            <span class="font-weight-bold">Precio normal $ `+precio_total+` </span> <br>
                            <span  class="font-weight-bold">Precio Oferta $ `+e.precio_actualizado+`</span><br>
                            <span>`+e.marcarepuesto.marcarepuesto+`</span>
                            </div>
                            
                            `);
                            contador = contador + 1;
                            console.log(contador);
                        });
                        $("#ultimas-ofertas-modal").modal("show");
                    },
                    error: function(error){
                        console.log(error.responseText);
                    }
                })
            }
        </script>
    </section>

    <section>
        <style>
            .back-to-top {
                    position: fixed;
                    bottom: 30px;
                    right: 30px;
                    width: 64px;
                    height: 64px;
                    z-index: 9999;
                    cursor: pointer;
                    border: 1px solid black;
                    text-decoration: none;
                    transition: opacity 0.2s ease-out;
                    background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Go-top.svg/1024px-Go-top.svg.png');
                }
                .back-to-top:hover{
                    opacity: 0.7;
                }

                #listado-permisos-detalle{
                    border: 1px solid black;
                    height: auto;
                    width: 100%;
                    margin-top: 30px;
                }
        </style>
        @yield('style')

        

    </section>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <div id="app">

        {{-- <menucomponente> </menucomponente> --}}

        @include('fragm.cabecera_sistema')

        <section>

            @yield('contenido')

            @yield('contenido_titulo_pagina')

            @yield('mensajes')

            @yield('contenido_ingresa_datos')

            @yield('contenido_ver_datos')

        </section>

    </div>

    @include('fragm.pie')

    @php

        if($entorno=='local'){

            echo "<script src=\"".asset('js/app.js')."\" defer></script>";

        }elseif($entorno=='production'){

            echo "<script src=\"".mix('app.js','dist')."\" defer></script>";

        }



    @endphp



<!-- Ventana modal BUSCAR REPUESTO -->

<div role="dialog" tabindex="-1" class="modal fade" id="buscar-repuesto-modal">

    <div class="modal-dialog modal-xl modal-ventas" role="document" >

      <div class="modal-content">

 

        <div class="modal-header modal-header-80"> <!-- CABECERA -->

         <div class="row" style="width:100%">

             <div class="col-2" style="padding-left:2px">

                 <button class="btn btn-warning btn-sm" onclick="buscar()" style="height: 20px"><p id="txtBotonPanel">Ocultar Marcas y Modelos</p></button>

             </div>

             <div class="col-9"><p class="d-flex justify-content-center" id="buscar-repuesto-modal-titulo" style="color:white;font-weight:bold">BUSCAR REPUESTO</p></div>

             <div class="col-1" style="padding-right: 1px"><button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding-right: 1px"><span aria-hidden="true">×</span></button></div>

         </div>

         <div class="row" style="width:90%">

             <div class="col-2 alin-der" style="padding-right:2px;color:white">Buscar:</div>

             <div class="col-10" style="margin-bottom:5px;padding-left:2px">

                 <input type="search" class="form-control" style="height:25px;" name="txtDescripcion" id="buscar_por_descripcion" placeholder="¿Qué desea encontrar?" onkeyup="enter_press(event,'d')" onclick="clic_en(1)">

             </div>

         </div>

        </div> <!-- FIN CABECERA -->

 

        <div class="modal-body modal-body-alto" style="padding-top: 0px;"> <!-- CONTENIDO -->

         <div class="row">

         <!-- Columna 1 : elegir -->

                 <div class="col-sm-3" id="panel-buscar" style="background-color: #F2F5A9;">

                 <div class="row row-25 " id="zona_elegir">

                     <!--

                     <strong>BUSCAR POR:</strong><br><button class="btn btn-success btn-sm" onclick="filtros()" style="height: 20px;padding-top:0px"><p id="pFiltros">Mostrar Filtros</p></button><br>

                 -->

                     <div id="filtros" style="display:none"> <!-- FILTROS Inicia oculto -->

                         <div class="col-12 col-sm-12 col-md-12">

                         <input class="form-control-sm" type="checkbox" name="chkMedidas" id="chkMedidas"><small>Solo con Medidas</small>

                         </div>

                         <div class="col-12 col-sm-12 col-md-12">

                         <input class="form-control-sm" type="checkbox" name="chkStock" id="chkStock"><small>Solo con Stock</small>

                         </div>

                         <div class="col-12 col-sm-12 col-md-12" style="display:none">

                         <input class="form-control-sm" type="checkbox" name="chkStock" id="chkDescripcion"><small>No buscar en Descripción</small>

                         </div>

                     </div> <!-- fin div FILTROS -->

 

                 <!--

                     <div class="form-group col-12 col-sm-12 col-md-12" style="margin-bottom:5px;">

                         <input type="search" class="form-control" style="height:25px;" name="txtCodigoProveedor" id="buscar_por_codigo_proveedor" placeholder="Cod. Proveedor" onkeyup="enter_press(event,'p')" onclick="clic_en(2)">

                     </div>

                     <div class="form-group col-12 col-sm-12 col-md-12" style="margin-bottom:5px;">

                         <input type="search" class="form-control" style="height:25px;" name="txtOem" id="buscar_por_oem" placeholder="Cod. OEM" onkeyup="enter_press(event,'o')" onclick="clic_en(3)">

                     </div>

                     <div class="form-group col-12 col-sm-12 col-md-12" style="margin-bottom:5px;">

                         <input type="search" class="form-control" style="height:25px;" name="txtCodigoFabricante" id="buscar_por_codigo_fabricante" placeholder="Cod. Fabricante" onkeyup="enter_press(event,'f')" onclick="clic_en(7)">

                     </div>

                     <div class="form-group col-12 col-sm-12 col-md-12" style="margin-bottom:5px;">

                         <input type="search" class="form-control" style="height:25px;" name="txtMedidas" id="buscar_por_medidas" placeholder="Medidas" onkeyup="enter_press(event,'m')" onclick="clic_en(4)">

                     </div>

                     <div class="form-group col-12 col-sm-12 col-md-12" style="margin-bottom:5px;display:none">

                          <input type="search" class="form-control" style="height:25px;" name="txtDescripcion" id="buscar_por_codint" placeholder="Código Interno Pancho" onkeyup="enter_press(event,'c')" onclick="clic_en(6)">

                     </div>

                 -->

                     <div class="col-12 col-sm-12 col-md-12" style="margin-bottom:5px;">

                         <button class="btn btn-info btn-md btn-block" style="height:25px; padding-top:2px" onclick="clic_en(5)">Volver a Abrir</button>

                     </div>

                 </div> <!-- FIN zona_elegir -->

                 <div class="row" >

                     <div class="col-sm-12" id="zona_familia"></div>

                 </div>

                 </div> <!-- FIN Columna 1 : elegir -->

 

         <!-- Columna 2 : grilla y detalles -->

                 <div class="col-sm-9" id="grilla" style="background-color: #81BEF7;padding-left:0px;padding-right:0px">

                 <div id="zona_grilla" style="height:300px"></div> <!-- fragm.ventas_repuestos.blade -->

                 <div class="row row-cero-margen" id="zona_detalle">

                     <div class="row row-cero-margen" id="zona_detalle_fieldset" style="width:100%">

                         <div class="col-3" id="zona_fotos" style="background-color: #81BEF7; padding-left: 1px;padding-right: 1px"></div>

                         <div class="col-5" id="zona_similares" style="background-color: #b3e6ff;padding-left: 1px;padding-right: 1px"></div>

                         <div class="col-2" id="zona_oem" style="background-color: #81BEF7; padding-left: 1px;padding-right: 1px"></div>

                         <div class="col-2" id="zona_fab" style="background-color: #96dafe; padding-left: 1px;padding-right: 1px"></div>

                     </div>

                 </div>

 

                 </div><!-- FIN Columna 2 : grilla y detalles  -->

 

          </div> <!-- FIN de row principal -->

       </div> <!-- FIN DE modal-body CONTENIDO -->

 

        <div class="modal-footer" style="flex-direction:column; align-content: start"> <!-- PIE -->

             <div class="row" style="width:90%">

                 <div class="col-sm-8 col-offset-4" id="mensajes-modal"></div>

             </div>

        </div> <!-- FIN MODAL-FOOTER -->

         </div> <!-- modal-content -->

 

 

    </div> <!-- modal-dialog -->

 </div> <!-- Fin ventana modal -->

 <!--MODAL OFERTAS -->
 <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="ultimas-ofertas-modal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pagar-delivery-label">ULTIMOS OFERTAS</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center" >
          <div class="row" id="modal_body_ultimas_ofertas" style="    display: flex;
          justify-content: space-evenly;">

          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
      </div>
      </div>
    </div>
  </div>
 <a href="#" class="back-to-top"></a>
</body>

</html>


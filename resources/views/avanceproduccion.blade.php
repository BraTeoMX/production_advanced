@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('avanceproduccion')])

<!-- El siguiente script refrescara la pagina transcurridos los 10 minuto. -->
<meta http-equiv="refresh" content="600" />

@section('content')

  <div class="content">

    <div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">

                <i class="card-tittle"></i>
                <font size=4+><p> Intimark </br>Avance Diario</br>General </p></font>
              </div>
              <p class="card-category">
              @php
                date_default_timezone_set('America/Mexico_City');
                setlocale(LC_TIME, "spanish");

                $hoy= strftime(" %d de %B ");
                $dia_semana = date("N");
              @endphp
              @if(strtotime($hora_actualizacionI) != strtotime($hora3) || $hora_actualizacionI == 'N/D'  )
                @php
                  $color2 = "red";
                @endphp
              @else
                @php
                  $color2 = "blue";
                @endphp
              @endif
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ color={{ $color2 }} >Última Actualización : {{$hora_actualizacion.' hrs.' }}</font></b>
                <small></small>
              </h3>
            </div>
            <div class="card-footer" style="align:center">
              @if(  $eficiencia_dia >$eficiencia_planta )
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif

                <p class="stats"><span class="text-info"> Eficiencia Meta del día : </span><b>  {{ ' '.number_format($eficiencia_dia,1).' %' }}</b></p>
                <p class="stats"><span class="text-info"> Eficiencia Real al horario : </span><b><font color={{ $color }}>  {{ ' '.number_format($eficiencia_planta,1).' %' }}</font></b></p>

            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-info"> Piezas Meta : </span><b>  {{ ' '.number_format( $cantidad_plantaI+$cantidad_plantaII,0) }}</b></p>
                <p class="stats"><span class="text-info"> Piezas Reales :</span><b>  {{ ' '.number_format( ($real_x_plantaI)+($real_x_plantaII-$real_x_plantaI831),0) }}</b></p>
                <p class="stats"><span class="text-info"> Diferencia :</span><b>@if(((($real_x_plantaI)+($real_x_plantaII-$real_x_plantaI831))-($cantidad_plantaI+$cantidad_plantaII))<0)<font color=red> @else <font color=blue> @endif {{ ' '.number_format(((($real_x_plantaI)+($real_x_plantaII-$real_x_plantaI831))-($cantidad_plantaI+$cantidad_plantaII)),0) }}</font></b></p>
            </div>

          </div>
        </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">

                <i class="card-tittle"></i>

                <font size=4+><p> Intimark </br>Avance Acumulado </br>General </p></font>
              </div>
              <p class="card-category">
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ color={{ $color2 }} >Última Actualización : {{$hora_actualizacion.' hrs.' }}</font></b>
                <small></small>
              </h3>
            </div>

            <div class="card-footer" style="align:center">
            @if($eficiencia_total > $eficiencia_planta)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
              <p class="stats"><span class="text-info"> Eficiencia Meta Semanal :</span><b>  {{ number_format($eficiencia_total,1).' %' }}</b></p>
              <p class="stats"><span class="text-info"> Eficiencia Real al horario :</span><b> <font color={{ $color }}> {{ ' '.number_format($eficiencia_planta,1).' %'  }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-info"> Piezas Meta : </span><b>  {{ ' '.number_format( $cantidad_total,0) }}</b></p>
                <p class="stats"><span class="text-info"> Piezas Reales :</span><b>  {{ ' '.number_format($cantidad_semanal+(($real_x_plantaI)+($real_x_plantaII-$real_x_plantaI831)),0) }}</b></p>
                <p class="stats"><span class="text-info"> Diferencia :</span><b> @if(($cantidad_semanal+(($real_x_plantaI)+($real_x_plantaII-$real_x_plantaI831))-$cantidad_total)<0)<font color=red> @else <font color=blue> @endif  {{ ' '.number_format($cantidad_semanal+(($real_x_plantaI)+($real_x_plantaII-$real_x_plantaI831))-$cantidad_total,0) }}</font></b></p>
            </div>
          <!--
           <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
            <div class="card-icon">

                <i class="card-tittle"></i>
                <font size=3+><p>{{ __('Planta Ixtlahuaca') }}</p>{{ 'Avance Diario ' }}</p></font>
              </div>
              <p class="card-category">
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ @if(strtotime($hora_actualizacionI) < strtotime($hora3) || $hora_actualizacionI == 'N/D' ) color=red @else color=blue @endif  >Última Actualización : {{$hora_actualizacionI.' hrs.' }}</font></b>
                <small></small>
              </h3>
            </div>


            @if($eficiencia_dia_plantaI > $eficiencia_plantaI)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
              <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día  :</span><b>  {{ ' '.number_format($eficiencia_dia_plantaI,1).' %' }}</b></p>
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b> <font color={{ $color }}>  {{ ' '.number_format($eficiencia_plantaI,1).' %'}}</font></b></p>

            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{ ' '.number_format($cantidad_plantaI,0) }}</b></p>
                <p class="stats"><span class="text-success"> Piezas Reales :</span><b>  {{ ' '.number_format($real_x_plantaI,0) }}</b></p>
                <p class="stats"><span class="text-success"> Diferencia :</span><b> @if((($real_x_plantaI)-$cantidad_plantaI)<0)<font color=red> @else <font color=blue> @endif  {{ number_format(($real_x_plantaI)-$cantidad_plantaI,0) }} </font></b></p>

          <!--
           <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
              </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">

                <i class="card-tittle"></i>
                <font size=3+><p>{{ __('Planta San Bartolo ') }}</p><p>{{ __('Avance Diario ') }}</p></font>
              </div>
              <p class="card-category">
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ @if(strtotime($hora_actualizacionI) < strtotime($hora3) || $hora_actualizacionI == 'N/D' ) color=red @else color=blue @endif >Última Actualización : {{$hora_actualizacionI.' hrs.' }}</font></b>
                <small></small>
              </h3>
            </div>

            <div class="card-footer" style="align:center">
            @if($eficiencia_dia_plantaII > $eficiencia_plantaII)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <p class="stats"><span class="text-warning"> Eficiencia Meta del día :</span><b>  {{ ' '.number_format($eficiencia_dia_plantaII,1).' %' }}</b></p>
              <p class="stats"><span class="text-warning"> Eficiencia Real al horario:</span><b>  <font color={{ $color }}> {{ ' '.number_format($eficiencia_plantaII,1).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-warning"> Piezas Meta : </span><b>  {{ ' '.number_format($cantidad_plantaII,0) }}</b></p>
                <p class="stats"><span class="text-warning"> Piezas Reales :</span><b>  {{ ' '.number_format($real_x_plantaII-$real_x_plantaI831,0) }}</b></p>
                <p class="stats"><span class="text-warning"> Diferencia :</span><b> @if((($real_x_plantaII-$real_x_plantaI831)-$cantidad_plantaII)<0)<font color=red> @else <font color=blue> @endif{{ ' '.number_format(($real_x_plantaII-$real_x_plantaI831)-$cantidad_plantaII,0) }} </font></b></p>
                    </div>
          <!--
           <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>
      </div>


    <!--  <div class="row">
        <div class="col-lg-6 col-md-6">
          <label for="join"
            class="col-md-4 col-form-label text-md-end">{{ __('Fecha Inicial') }}</label>
            <div class="col-md-6" >
              <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicial" max ="2023-08-08" min ="2023-08-08" value={{ $inicio }} >
            </div>
          </div>
          <div class="col-lg-6 col-md-6" >
          <label for="join"
            class="col-md-4 col-form-label text-md-end">{{ __('Fecha Final') }}</label>
            <div class="col-md-6" style="display: block" id='id_fecha_final'>
              <input type="date" class="form-control" name="fecha_final" id="fecha_final" value={{ $fin }}>
            </div>
          </div>
      </div>-->

      <div class="row">
        @if($cantidad_VS != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('V.S.') }}</b>     <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_VS > $eficiencia_VS)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_VS,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b> <font color={{ $color }}>  {{ number_format($eficiencia_VS,1).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_VS,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{number_format($real_VS,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>   @if(($real_VS-$cantidad_VS)<0)<font color=red> @else <font color=blue> @endif{{ number_format($real_VS-$cantidad_VS,0) }}</font></b></p>
            </div>

            @if($VS_plantaI <>0 and $VS_plantaII <> 0)
           <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleVS') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif
       @if ($cantidad_CHICOS != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('CHICO´S') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_CHICOS > $eficiencia_CHICOS)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_CHICOS,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b><font color={{ $color }}>  {{ number_format($eficiencia_CHICOS,1).' %'  }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_CHICOS,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_CHICOS,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_CHICOS-$cantidad_CHICOS)<0)<font color=red> @else <font color=blue> @endif {{  number_format($real_CHICOS-$cantidad_CHICOS,0)  }}</font></b></p>
            </div>

            @if($CHICOS_plantaI <>0 and $CHICOS_plantaII <> 0)

            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleCHICOS') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif
        @if($cantidad_BN3 != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('BN3TH') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_BN3 > $eficiencia_BN3)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{number_format($eficiencia_dia_BN3,1).' %' }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b> <font color={{ $color }}> {{ number_format($eficiencia_BN3,1).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta al horario : </span><b>  {{  number_format($cantidad_BN3,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario:</span><b>  {{ number_format($real_BN3,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b> @if(($real_BN3-$cantidad_BN3)<0)<font color=red> @else <font color=blue> @endif {{ number_format($real_BN3-$cantidad_BN3,0) }}</font></b></p>
            </div>

            @if($BN3_plantaI <>0 and $BN3_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleBN3') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif
        @if ($cantidad_NU != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('NUUDS') }}</b>
              </h3>
            </div>
            @if($eficiencia_dia_NU > $eficiencia_NU)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_NU,1).' %' }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b> <font color={{ $color }}> {{ number_format($eficiencia_NU,1).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_NU,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Reales al horario :</span><b>  {{ number_format($real_NU,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b> @if(($real_NU-$cantidad_NU)<0)<font color=red> @else <font color=blue> @endif {{ number_format($real_NU-$cantidad_NU,0) }} </font></b></p>
            </div>

            @if($NU_plantaI <>0 and $NU_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleNU') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>
        </div>
      @endif
     <!-- </div>

      <div class="row">-->
        @if ($cantidad_MARENA != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('MARENA') }}</b>     <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_MARENA > $eficiencia_MARENA)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_MARENA,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b> <font color={{ $color }}> {{ number_format($eficiencia_MARENA,1).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_MARENA,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_MARENA,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia  :</span><b> @if(($real_MARENA-$cantidad_MARENA)<0)<font color=red> @else <font color=blue> @endif{{ number_format($real_MARENA-$cantidad_MARENA,0) }}</font></b></p>
            </div>

            @if($MARENA_plantaI <>0 and $MARENA_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleMARENA') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif
       @if($cantidad_PACIFIC != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('PACIFIC') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_PACIFIC > $eficiencia_PACIFIC)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_PACIFIC,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>   <font color={{ $color }}>{{ number_format($eficiencia_PACIFIC,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_PACIFIC,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b> {{ number_format($real_LECOQ,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_LECOQ-$cantidad_PACIFIC)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_LECOQ-$cantidad_PACIFIC,0)  }}</font></b></p>
            </div>

            @if($PACIFIC_plantaI <>0 and $PACIFIC_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detallePACIFIC') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif
        @if($cantidad_BELL != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('BELLEFIT') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_BELL > $eficiencia_BELL)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_BELL,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_BELL,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_BELL,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_BELL,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_BELL-$cantidad_BELL)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_BELL-$cantidad_BELL,0)  }}</font></b></p>
            </div>

            @if($BELL_plantaI <>0 and $BELL_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleBELL') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif
        @if($cantidad_WP != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('ATHLUX') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_WP > $eficiencia_WP)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_WP,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_WP,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_WP,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_WP,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_WP-$cantidad_WP)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_WP-$cantidad_WP,0)  }}</font></b></p>
            </div>

            @if($WP_plantaI <>0 and $WP_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleWP') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif

        @if($cantidad_HOOEY != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('HOOEY') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_HOOEY > $eficiencia_HOOEY)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_HOOEY,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_HOOEY,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_HOOEY,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_HOOEY,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_HOOEY-$cantidad_HOOEY)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_HOOEY-$cantidad_HOOEY,0)  }}</font></b></p>
            </div>

            @if($HOOEY_plantaI <>0 and $HOOEY_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleHOOEY') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif

        @if($cantidad_RV != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('ROPA VIVA') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_RV > $eficiencia_RV)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_RV,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_RV,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_RV,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_RV,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_RV-$cantidad_RV)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_RV-$cantidad_RV,0)  }}</font></b></p>
            </div>

            @if($RV_plantaI <>0 and $RV_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleRV') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif

        @if($cantidad_HANES != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('HANES') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('HANES') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_HANES > $eficiencia_HANES)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_HANES,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_HANES,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_HANES,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_HANES,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_HANES-$cantidad_HANES)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_HANES-$cantidad_HANES,0)  }}</font></b></p>
            </div>

            @if($HANES_plantaI <>0 and $HANES_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleHANES') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif

        @if($cantidad_STARBOARD != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('STARBOARD') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('STARBOARD') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_STARBOARD > $eficiencia_STARBOARD)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_STARBOARD,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_STARBOARD,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_STARBOARD,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_STARBOARD,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_STARBOARD-$cantidad_STARBOARD)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_STARBOARD-$cantidad_STARBOARD,0)  }}</font></b></p>
            </div>

            @if($STARBOARD_plantaI <>0 and $STARBOARD_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleSTARBOARD') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif

        @if($cantidad_MAREL != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('MAREL') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('MAREL') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_MAREL > $eficiencia_MAREL)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_MAREL,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_MAREL,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta al horario : </span><b>  {{ number_format($cantidad_MAREL,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real al horario :</span><b>  {{ number_format($real_MAREL,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_MAREL-$cantidad_MAREL)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_MAREL-$cantidad_MAREL,0)  }}</font></b></p>
            </div>

            @if($MAREL_plantaI <>0 and $MAREL_plantaII <> 0)
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('detalleMAREL') }}">Detalle por planta...</a>
              </div>
            </div>
            @endif
          </div>

        </div>
        @endif

        @if($cantidad_Empaque != 0)
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('EMPAQUE.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('EMPAQUE') }}</b>
                <small></small>
              </h3>
            </div>
            @if($eficiencia_dia_Empaque > $eficiencia_Empaque)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta del día :</span><b>  {{  number_format($eficiencia_dia_Empaque,1).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real al horario :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_Empaque,1).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Minutos Producidos Meta al horario : </span><b>  {{ number_format($cantidad_Empaque,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Minutos Producidos Real al horario :</span><b>  {{ number_format($real_Empaque,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>@if(($real_Empaque-$cantidad_Empaque)<0)<font color=red> @else <font color=blue> @endif  {{  number_format($real_Empaque-$cantidad_Empaque,0)  }}</font></b></p>
            </div>

            @if($Empaque_plantaI <>0 and $Empaque_plantaII <> 0)
            <div class="card-footer">
                <div class="stats">
                  <i class="material-icons ">date_range</i>
                  <a href="{{ route('detalleEmpaque') }}">Detalle por planta...</a>
                </div>
              </div>
              @endif
          </div>

        </div>
        @endif
      </div>

     <div class="row">
        <div class="col-lg-12 col-md-12">
          <div class="card">
            <div class="card-header card-header-tabs card-header-info">
              <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                  <span class="nav-tabs-title">Reportes:</span>
                  <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                      <a class="nav-link" href="#profile" data-toggle="tab">
                        <i class="material-icons">code</i> Supervisor
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#messages" data-toggle="tab">
                        <i class="material-icons">code</i> Modulos
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                   <!--  <li class="nav-item">
                      <a class="nav-link" href="#messages2" data-toggle="tab">
                        <i class="material-icons">code</i>Plantas
                        <div class="ripple-container"></div>
                      </a>
                    </li>-->
                    <li class="nav-item">
                      <a class="nav-link active" href="#settings" data-toggle="tab">
                        <i class="material-icons">bug_report</i> Avance
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="card-body  table-responsive">
              <div class="tab-content">
              <div class="tab-pane" id="profile">
                  <table class="table">
                  <div class="card-body table-responsive">

                    <table class="table-cebra table-fixed" id="tablaSupervisorI">
                      <div class="col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-header card-header-tabs card-header-success">
                             Planta I: Ixtlahuaca
                          </div>
                        </div>
                      </div>
                      <thead class="text-primary">
                          <th class="sticky-2">Supervisor</th>
                        <!-- <th>Modulo</th>     -->
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas Meta Diaria</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia Meta Diaria</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas  Reales</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Proyeccion Minutos</th>
                      </thead>
                      <tbody>
                        @foreach($team_leaderI as $team)
                            <tr>
                              @if ($team->min_presencia_netos  != 0)
                                @php
                                  $efic_meta = ($team->min_x_producir/$team->min_presencia_netos)*100;
                                @endphp
                                @if( strtotime($hora_actualizacionII) != strtotime($hora))
                                  @php
                                    $piezas_meta = ($team->piezas_meta/$horas_laboradas)*$valor_hora2;
                                  @endphp
                                @else
                                  @php
                                    $piezas_meta = ($team->piezas_meta/$horas_laboradas)*$valor_hora;
                                  @endphp
                                @endif
                                @php
                                  $aux = 0;
                                @endphp
                              @else
                                @php
                                  $efic_meta = 0;
                                  $piezas_meta =0;
                                @endphp
                              @endif
                              <td class="text-info">{{ $team->team_leader }} </td>
                              <td  style="text-align: center">{{ number_format($piezas_meta,0) }}</td>
                              <td  style="text-align: center">{{ number_format($efic_meta,1).' %' }}</td>

                              @foreach($teams_leaderI as $teams)
                                @if($team->team_leader == $teams->team_leader)
                                  @php
                                    $aux=1;
                                  @endphp
                                  @if  ($team->min_presencia_netos== 0)
                                    @php
                                    $efic_real=0;
                                    @endphp
                                  @else
                                    @php
                                        $efic_real = ((($teams->min_producidos/$valor_hora)*$horas_laboradas)/$team->min_presencia_netos)*100;
                                    @endphp
                                  @endif
                                  <td  style="text-align: center">{{ number_format($teams->piezas,0) }}</td>
                                  @if($efic_meta > $efic_real)
                                    @php
                                      $color = "red";
                                    @endphp
                                  @else
                                    @php
                                      $color = "black";
                                    @endphp
                                  @endif

                                  <td  style="text-align: center"><font color={{ $color }}>{{ number_format($efic_real,1).' %' }}</font></td>
                                  <td  style="text-align: center">{{ number_format($teams->min_producidos,0) }}</td>
                                  <td  style="text-align: center">{{ number_format($teams->proyeccion_minutos,0) }}</td>

                                @endif
                              @endforeach

                              @if($aux==0)
                                  <td  style="text-align: center">{{ number_format(0,1).' %' }}</td>
                                  <td  style="text-align: center">{{ number_format(0,0) }}</td>
                                  <td  style="text-align: center">{{ number_format(0,0) }}</td>
                                  <td  style="text-align: center">{{ number_format(0,0) }}</td>
                              @endif
                            </tr>

                        @endforeach


                      </tbody>
                    </table>
                    <table class="table-cebra table-fixed" id="tablaSupervisorII">
                      <div class="col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-header card-header-tabs card-header-warning">
                             Planta II: San Bartolo
                          </div>
                        </div>
                      </div>
                      <thead class="text-primary">
                          <th class="sticky-2">Supervisor</th>
                        <!-- <th>Modulo</th>     -->
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas Meta Hora</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia Meta Hora</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas  Reales</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Proyeccion Minutos</th>
                      </thead>
                      <tbody>
                        @foreach($team_leaderII as $teamII)
                        <tr>
                          @if ($team->min_presencia_netos  != 0)
                            @php
                              $efic_meta = ($teamII->min_x_producir/$teamII->min_presencia_netos)*100;
                            @endphp
                            @if( strtotime($hora_actualizacionII) != strtotime($hora))
                              @php
                                $piezas_meta = ($teamII->piezas_meta/$horas_laboradas)*$valor_hora2;
                              @endphp
                            @else
                              @php
                                $piezas_meta = ($teamII->piezas_meta/$horas_laboradas)*$valor_hora;
                              @endphp
                            @endif
                            @php
                              $aux = 0;
                            @endphp
                          @else
                            @php
                              $efic_meta = 0;
                              $piezas_meta =0;
                            @endphp
                          @endif
                          <td class="text-info">{{ $teamII->team_leader }} </td>
                          <td  style="text-align: center">{{ number_format($piezas_meta,0) }}</td>
                          <td  style="text-align: center">{{ number_format($efic_meta,1).' %' }}</td>
                          @foreach($teams_leaderII as $teamsII)

                            @if($teamII->team_leader == $teamsII->team_leader)

                              @php
                                $aux=1;
                              @endphp
                              @if  ($teamII->min_presencia_netos== 0)
                                @php
                                $efic_real=0;
                                @endphp
                              @else
                                @php
                                    $efic_real = ((($teamsII->min_producidos/$valor_hora)*$horas_laboradas)/$teamII->min_presencia_netos)*100;
                                @endphp
                              @endif
                              <td  style="text-align: center">{{ number_format($teamsII->piezas,0) }}</td>
                              @if($efic_meta > $efic_real)
                                @php
                                  $color = "red";
                                @endphp
                              @else
                                @php
                                  $color = "black";
                                @endphp
                              @endif

                              <td  style="text-align: center"><font color={{ $color }}>{{ number_format($efic_real,1).' %' }}</font></td>
                              <td  style="text-align: center">{{ number_format($teamsII->min_producidos,0) }}</td>
                              <td  style="text-align: center">{{ number_format($teamsII->proyeccion_minutos,0) }}</td>

                            @endif
                          @endforeach

                          @if($aux==0)
                              <td  style="text-align: center">{{ number_format(0,0)}}</td>
                              <td  style="text-align: center">{{ number_format(0,1).' %'  }}</td>
                              <td  style="text-align: center">{{ number_format(0,0) }}</td>
                              <td  style="text-align: center">{{ number_format(0,0) }}</td>
                          @endif
                        </tr>

                    @endforeach

                      </tbody>
                    </table>
                  </div>
                <div class="tab-pane active" id="settings">


                <div class="tab-pane active" id="settings">
                  <table class="table-cebra table-fixed">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-header card-header-tabs card-header-success">
                             Planta I: Ixtlahuaca
                          </div>
                        </div>
                      </div>
                      <thead>
                        <th style="text-align: center " class="sticky sticky-2">Supervisor</th>
                        <th style="text-align: center " class="sticky2 sticky-2">Area</th>
                        <th style="text-align: center " class="sticky3 sticky-2">Modulo</th>
                       <!-- <th style="text-align: center" class="sticky4">Estilo</th>-->
                        <th style="text-align: center " class="sticky4 sticky-2">Piezas Meta</th>
                        <th style="text-align: center " class="sticky5 sticky-2">Eficiencia</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 09:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-success sticky-3">Meta 10:00</th>
                        <th style="text-align: center " class="text-success sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-success sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-success sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-success sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-info sticky-3">Meta 11:00</th>
                        <th style="text-align: center " class="text-info sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-info sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-info sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-info sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 12:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 13:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 14:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 15:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 16:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 17:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 18:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                      </thead>


                        <tbody>
                            @foreach($planeacionI as $planI)
                            <tr>
                                 @if ($planI->min_presencia_netos  != 0)
                                   @php
                                     $efic_meta = ($planI->min_x_producir/$planI->min_presencia_netos)*100;
                                   @endphp

                                   @php
                                     $aux = 0;
                                   @endphp
                                 @else
                                   @php
                                     $efic_meta = 0;
                                     $piezas_meta =0;
                                   @endphp
                                 @endif
                                 <td style="text-align: left" class="sticky sticky-22 "><font size=2px">{{ $planI->team_leader}}</font></td>
                                 <td style="text-align: center" class="sticky2 sticky-22"><font size=2px">{{ $planI->cliente}}</font></td>
                                 <td style="text-align: center" class="sticky3 sticky-22"><font size=2px">{{ $planI->modulo}}</font></td>
                                 <td style="text-align: center" class="sticky4 sticky-22"><font size=2px">{{ number_format($planI->piezas_meta,0)}}</font></td>
                                 <td style="text-align: center" class="sticky5 sticky-22"><font size=2px">{{ number_format($efic_meta,1).' %'}}</font></td>
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*1,0)}}</font></td>

                                 @if ($hoy_hora >= '09:00')
                                 @foreach($planeacion_09 as $plan09)
                                     @if($plan09->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                       @endphp
                                       @if ($planI->min_presencia_netos ==0)
                                        @php
                                             $efic_real = 0;
                                        @endphp
                                       @else
                                        @php
                                             $efic_real = ((($plan09->min_producidos/1)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                        @endphp
                                        @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan09->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan09->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format($plan09->proyeccion_minutos,0)}}</font></td>

                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*2,0)}}</font></td>
                                @endif
                                @if ($hoy_hora >= '10:00')

                                 @foreach($planeacion_10 as $plan10)
                                     @if($plan10->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                       @endphp
                                       @if ($planI->min_presencia_netos == 0)
                                       @php
                                           $efic_real=0;
                                       @endphp
                                       @else
                                       @php
                                         $efic_real = ((($plan10->min_producidos/2)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan10->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan10->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan10->min_producidos/2)*$horas_laboradas,0)}}</font></td>

                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*3,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '11:00')
                                 @foreach($planeacion_11 as $plan11)
                                     @if($plan11->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                       @endphp
                                       @if($planI->min_presencia_netos==0)
                                       @php
                                           $efic_real=0;
                                       @endphp
                                       @else

                                       @php
                                         $efic_real = ((($plan11->min_producidos/3)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                        @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan11->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan11->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan11->min_producidos/3)*$horas_laboradas,0)}}</font></td>

                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*4,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '12:00')
                                 @foreach($planeacion_12 as $plan12)
                                     @if($plan12->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                        @endphp
                                        @if($planI->min_presencia_netos==0)
                                        @php
                                         $efic_real = 0;
                                       @endphp
                                       @else
                                       @php
                                       $efic_real = ((($plan12->min_producidos/4)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                     @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan12->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan12->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan12->min_producidos/4)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*5,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '13:00')

                                 @foreach($planeacion_13 as $plan13)
                                     @if($plan13->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                         $efic_real = ((($plan13->min_producidos/5)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan13->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan13->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan13->min_producidos/5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*5.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '14:00')
                                 @foreach($planeacion_14 as $plan14)
                                     @if($plan14->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                         $efic_real = ((($plan14->min_producidos/5.5)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan14->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan14->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan14->min_producidos/5.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*6.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '15:00')
                                 @foreach($planeacion_15 as $plan15)
                                     @if($plan15->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                         $efic_real = ((($plan15->min_producidos/6.5)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan15->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan15->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan15->min_producidos/6.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*7.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '16:00')
                                 @foreach($planeacion_16 as $plan16)
                                     @if($plan16->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                         $efic_real = ((($plan16->min_producidos/7.5)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan16->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan16->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan16->min_producidos/7.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*8.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '17:00')
                                 @foreach($planeacion_17 as $plan17)
                                     @if($plan17->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                         $efic_real = ((($plan17->min_producidos/8.5)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan17->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan17->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan17->min_producidos/8.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planI->piezas_meta/$horas_laboradas)*9.5,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '18:00')
                                 @foreach($planeacion_18 as $plan18)
                                     @if($plan18->modulo == $planI->modulo)
                                       @php
                                         $aux=1;
                                         $efic_real = ((($plan18->min_producidos/9.5)*$horas_laboradas)/$planI->min_presencia_netos)*100;
                                       @endphp
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan18->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan18->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan18->min_producidos/9.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 @endif

                               </tr>
                              @endforeach


                        </tbody>
                    </table>
                    <table class="table-cebra table-fixed">
                      <div class="col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-header card-header-tabs card-header-warning">
                             Planta II: San Bartolo
                          </div>
                        </div>
                      </div>
                      <thead>
                        <th style="text-align: center " class="sticky sticky-2">Supervisor</th>
                        <th style="text-align: center " class="sticky2 sticky-2">Area</th>
                        <th style="text-align: center " class="sticky3 sticky-2">Modulo</th>
                       <!-- <th style="text-align: center" class="sticky4">Estilo</th>-->
                        <th style="text-align: center " class="sticky4 sticky-2">Piezas Meta</th>
                        <th style="text-align: center " class="sticky5 sticky-2">Eficiencia</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 09:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-success sticky-3">Meta 10:00</th>
                        <th style="text-align: center " class="text-success sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-success sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-success sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-success sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-info sticky-3">Meta 11:00</th>
                        <th style="text-align: center " class="text-info sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-info sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-info sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-info sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 12:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 13:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 14:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 15:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 16:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 17:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Meta 18:00</th>
                        <th style="text-align: center " class="text-warning sticky-3">Piezas Reales</th>
                        <th style="text-align: center " class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center " class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                      </thead>


                        <tbody>
                            @foreach($planeacionII as $planII)
                            <tr>
                                 @if ($planII->min_presencia_netos  != 0)
                                   @php
                                     $efic_meta = ($planII->min_x_producir/$planII->min_presencia_netos)*100;
                                   @endphp

                                   @php
                                     $aux = 0;
                                   @endphp
                                 @else
                                   @php
                                     $efic_meta = 0;
                                     $piezas_meta =0;
                                   @endphp
                                 @endif
                                 <td style="text-align: left" class="sticky sticky-22 "><font size=2px">{{ $planII->team_leader}}</font></td>
                                 <td style="text-align: center" class="sticky2 sticky-22"><font size=2px">{{ $planII->cliente}}</font></td>
                                 <td style="text-align: center" class="sticky3 sticky-22"><font size=2px">{{ $planII->modulo}}</font></td>
                                 <td style="text-align: center" class="sticky4 sticky-22"><font size=2px">{{ number_format($planII->piezas_meta,0)}}</font></td>
                                 <td style="text-align: center" class="sticky5 sticky-22"><font size=2px">{{ number_format($efic_meta,1).' %'}}</font></td>
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*1,0)}}</font></td>


                                 @if ($hoy_hora >= '09:00')
                                 @foreach($planeacion_09II as $plan09)
                                     @if($plan09->modulo == $planII->modulo)
                                       @php
                                         $aux=1;
                                       @endphp
                                       @if ($planII->min_presencia_netos ==0)
                                        @php
                                             $efic_real = 0;
                                        @endphp
                                       @else
                                        @php
                                             $efic_real = ((($plan09->min_producidos/1)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                        @endphp
                                        @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan09->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan09->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format($plan09->proyeccion_minutos,0)}}</font></td>

                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*2,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '10:00')
                                 @foreach($planeacion_10II as $plan10)
                                     @if($plan10->modulo == $planII->modulo)
                                       @php
                                         $aux=1;
                                       @endphp
                                       @if ($planII->min_presencia_netos == 0)
                                       @php
                                           $efic_real=0;
                                       @endphp
                                       @else
                                       @php
                                         $efic_real = ((($plan10->min_producidos/2)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                       @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan10->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan10->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan10->min_producidos/2)*$horas_laboradas,0)}}</font></td>

                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*3,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '11:00')
                                 @foreach($planeacion_11II as $plan11)
                                     @if($plan11->modulo == $planII->modulo)
                                       @php
                                         $aux=1;
                                       @endphp
                                       @if($planII->min_presencia_netos==0)
                                       @php
                                           $efic_real=0;
                                       @endphp
                                       @else

                                       @php
                                         $efic_real = ((($plan11->min_producidos/3)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                       @endphp

                                        @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan11->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan11->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan11->min_producidos/3)*$horas_laboradas,0)}}</font></td>

                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*4,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '12:00')
                                 @foreach($planeacion_12II as $plan12)
                                     @if($plan12->modulo == $planII->modulo)
                                       @php
                                         $aux=1;
                                        @endphp
                                        @if($planII->min_presencia_netos==0)
                                        @php
                                         $efic_real = 0;
                                       @endphp
                                       @else
                                       @php
                                       $efic_real = ((($plan12->min_producidos/4)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                     @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan12->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan12->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan12->min_producidos/4)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*5,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '13:00')

                                 @foreach($planeacion_13II as $plan13)
                                     @if($plan13->modulo == $planII->modulo)
                                     @php
                                     $aux=1;
                                    @endphp
                                    @if($planII->min_presencia_netos==0)
                                    @php
                                     $efic_real = 0;
                                   @endphp
                                   @else
                                   @php
                                         $efic_real = ((($plan13->min_producidos/5)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                 @endphp
                                   @endif

                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan13->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan13->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan13->min_producidos/5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*5.5,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '14:00')

                                 @foreach($planeacion_14II as $plan14)
                                     @if($plan14->modulo == $planII->modulo)
                                     @php
                                     $aux=1;
                                    @endphp
                                    @if($planII->min_presencia_netos==0)
                                    @php
                                     $efic_real = 0;
                                   @endphp
                                   @else
                                   @php
                                         $efic_real = ((($plan14->min_producidos/5.5)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                 @endphp
                                   @endif

                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan14->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan14->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan14->min_producidos/5.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*6.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '15:00')
                                 @foreach($planeacion_15II as $plan15)
                                     @if($plan15->modulo == $planII->modulo)
                                     @php
                                     $aux=1;
                                    @endphp
                                    @if($planII->min_presencia_netos==0)
                                    @php
                                     $efic_real = 0;
                                   @endphp
                                   @else
                                   @php
                                         $efic_real = ((($plan15->min_producidos/6.5)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                 @endphp
                                   @endif

                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan15->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan15->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan15->min_producidos/6.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*7.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '16:00')
                                 @foreach($planeacion_16II as $plan16)
                                     @if($plan16->modulo == $planII->modulo)
                                     @php
                                     $aux=1;
                                    @endphp
                                    @if($planII->min_presencia_netos==0)
                                    @php
                                     $efic_real = 0;
                                   @endphp
                                   @else
                                   @php
                                         $efic_real = ((($plan16->min_producidos/7.5)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                 @endphp
                                   @endif

                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan16->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan16->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan16->min_producidos/7.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*8.5,0)}}</font></td>

                                 @endif
                                 @if ($hoy_hora >= '17:00')
                                 @foreach($planeacion_17II as $plan17)
                                     @if($plan17->modulo == $planII->modulo)
                                     @php
                                     $aux=1;
                                    @endphp
                                    @if($planII->min_presencia_netos==0)
                                    @php
                                     $efic_real = 0;
                                   @endphp
                                   @else
                                   @php
                                         $efic_real = ((($plan17->min_producidos/8.5)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                 @endphp
                                   @endif

                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan17->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan17->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan17->min_producidos/8.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 <td style="text-align: right" ><font size=2px">{{ number_format(($planII->piezas_meta/$horas_laboradas)*9.5,0)}}</font></td>
                                 @endif
                                 @if ($hoy_hora >= '18:00')
                                 @foreach($planeacion_18II as $plan18)
                                     @if($plan18->modulo == $planII->modulo)
                                     @php
                                     $aux=1;
                                    @endphp
                                    @if($planII->min_presencia_netos==0)
                                    @php
                                     $efic_real = 0;
                                   @endphp
                                   @else
                                   @php
                                         $efic_real = ((($plan18->min_producidos/9.5)*$horas_laboradas)/$planII->min_presencia_netos)*100;
                                 @endphp
                                   @endif

                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan18->piezas,0)}}</font></td>
                                       @if($efic_meta > $efic_real)
                                         @php
                                           $color = "red";
                                         @endphp
                                       @else
                                         @php
                                           $color = "black";
                                         @endphp
                                       @endif
                                       <td style="text-align: right" ><font size=2px" color={{ $color }}>{{ number_format($efic_real,1).' %'}}</font></td>
                                       <td style="text-align: right" ><font size=2px">{{ number_format($plan18->min_producidos,0)}}</font></td>
                                       <td style="text-align:right" ><font size=2px">{{ number_format(($plan18->min_producidos/9.5)*$horas_laboradas,0)}}</font></td>


                                     @endif
                                 @endforeach

                                  @if($aux==0)
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,1).' %'}}</font></td>
                                   <td style="text-align: right" ><font size=2px">{{ number_format(0,0)}}</font></td>

                                 @endif
                                 @endif

                               </tr>
                              @endforeach

                        </tbody>
                    </table>
                  </div>
              </div>
                <div class="tab-pane" id="messages">
                  <table class="table">
                    <div class="card-body table-responsive">
                    <table class="table-cebra table-fixed" id="tablaModulosI">
                      <div class="col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-header card-header-tabs card-header-success">
                             Planta I: Ixtlahuaca
                          </div>
                        </div>
                      </div>
                        <thead class="text-primary">
                          <th class="sticky-2">Modulo</th>
                          <th class="sticky-2">Supervisor</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas Meta Hora</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia Meta Hora</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas  Reales</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Proyeccion Minutos</th>

                        </thead>
                    <tbody>
                        @foreach($moduloI as $moduloI)
                        <tr>
                        @if ($moduloI->min_presencia_netos  != 0)
                          @php
                            $efic_meta = ($moduloI->min_x_producir/$moduloI->min_presencia_netos)*100;
                          @endphp
                          @if( strtotime($hora_actualizacionII) != strtotime($hora))
                            @php
                              $piezas_meta = ($moduloI->piezas_meta/$horas_laboradas)*$valor_hora2;
                            @endphp
                          @else
                            @php
                              $piezas_meta = ($moduloI->piezas_meta/$horas_laboradas)*$valor_hora;
                            @endphp
                          @endif
                          @php
                            $aux = 0;
                          @endphp
                        @else
                          @php
                            $efic_meta = 0;
                            $piezas_meta =0;
                          @endphp
                        @endif
                        <td class="text-info">{{ $moduloI->modulo }} </td>
                        <td class="text-info">{{ $moduloI->team_leader }} </td>
                        <td  style="text-align: center">{{ number_format($piezas_meta,0) }}</td>
                        <td  style="text-align: center">{{ number_format($efic_meta,1).' %' }}</td>

                        @foreach($modulosI as $modsI)
                          @if($moduloI->modulo == $modsI->modulo)
                            @php
                              $aux=1;
                              $efic_real = ((($modsI->min_producidos/$valor_hora)*$horas_laboradas)/$moduloI->min_presencia_netos)*100;
                            @endphp
                            <td  style="text-align: center">{{ number_format($modsI->piezas,0) }}</td>
                            @if($efic_meta > $efic_real)
                              @php
                                $color = "red";
                              @endphp
                            @else
                              @php
                                $color = "black";
                              @endphp
                            @endif

                            <td  style="text-align: center"><font color={{ $color }}>{{ number_format($efic_real,1).' %' }}</font></td>
                            <td  style="text-align: center">{{ number_format($modsI->min_producidos,0) }}</td>
                            <td  style="text-align: center">{{ number_format((($modsI->min_producidos/$valor_hora)*$horas_laboradas),0) }}</td>

                          @endif
                        @endforeach

                        @if($aux==0)
                            <td  style="text-align: center">{{ number_format(0,1).' %' }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                        @endif
                      </tr>

                    @endforeach

                    </tbody>
                  </table>
                  <table class="table-cebra table-fixed" id="tablaModulosII">
                      <div class="col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-header card-header-tabs card-header-warning">
                             Planta II: San Bartolo
                          </div>
                        </div>
                      </div>
                        <thead class="text-primary">
                          <th class="sticky-2">Modulo</th>
                          <th class="sticky-2">Supervisor</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas Meta Diaria</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia Meta Diaria</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Piezas  Reales</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Eficiencia</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px" class="sticky-2">Proyeccion Minutos</th>

                        </thead>
                    <tbody>
                        @foreach($moduloII as $modII)
                        <tr>
                        @if ($modII->min_presencia_netos  != 0)
                          @php
                            $efic_meta = ($modII->min_x_producir/$modII->min_presencia_netos)*100;
                          @endphp
                          @if( strtotime($hora_actualizacionII) != strtotime($hora))
                            @php
                              $piezas_meta = ($modII->piezas_meta/$horas_laboradas)*$valor_hora2;
                            @endphp
                          @else
                            @php
                              $piezas_meta = ($modII->piezas_meta/$horas_laboradas)*$valor_hora;
                            @endphp
                          @endif
                          @php
                            $aux = 0;
                          @endphp
                        @else
                          @php
                            $efic_meta = 0;
                            $piezas_meta =0;
                          @endphp
                        @endif
                        <td class="text-info">{{ $modII->modulo }} </td>
                        <td class="text-info">{{ $modII->team_leader }} </td>
                        <td  style="text-align: center">{{ number_format($piezas_meta,0) }}</td>
                        <td  style="text-align: center">{{ number_format($efic_meta,1).' %' }}</td>

                        @foreach($modulosII as $modsII)
                          @if($modII->modulo == $modsII->modulo)
                          @php
                          $aux=1;
                          @endphp
                             @if($modII->min_presencia_netos == 0)
                                    @php
                                        $efic_real = 0;
                                    @endphp

                                @else
                                    @php
                                  $efic_real = ((($modsII->min_producidos/$valor_hora)*$horas_laboradas)/$modII->min_presencia_netos)*100;
                                    @endphp
                                @endif

                            <td  style="text-align: center">{{ number_format($modsII->piezas,0) }}</td>
                            @if($efic_meta > $efic_real)
                              @php
                                $color = "red";
                              @endphp
                            @else
                              @php
                                $color = "black";
                              @endphp
                            @endif

                            <td  style="text-align: center"><font color={{ $color }}>{{ number_format($efic_real,1).' %' }}</font></td>
                            <td  style="text-align: center">{{ number_format($modsII->min_producidos,0) }}</td>
                            <td  style="text-align: center">{{ number_format((($modsII->min_producidos/$valor_hora)*$horas_laboradas),0) }}</td>

                          @endif
                        @endforeach

                        @if($aux==0)
                            <td  style="text-align: center">{{ number_format(0,1).' %' }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                        @endif
                      </tr>

                    @endforeach

                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="messages2">
                  <table class="table">
                    <div class="card-body table-responsive">
                      <table class="table table-hover">
                        <thead class="text-primary">
                          <th>Planta</th>
                            <th style="text-align: center; width:200px">Piezas Meta Diaria</th>
                            <th style="text-align: center; width:200px">Eficiencia Meta Diaria</th>
                            <th style="text-align: center; width:200px">Piezas  Reales</th>
                            <th style="text-align: center; width:200px">Eficiencia</th>
                            <th style="text-align: center; width:200px">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px">Proyeccion Minutos</th>

                        </thead>
                    <tbody>
                        @foreach($moduloII as $moduloII)
                        <tr>
                        @if ($moduloII->min_presencia_netos  != 0)
                          @php
                            $efic_meta = ($moduloII->min_x_producir/$moduloII->min_presencia_netos)*100;
                          @endphp
                          @if( strtotime($hora_actualizacionII) != strtotime($hora))
                            @php
                              $piezas_meta = ($moduloII->piezas_meta/$horas_laboradas)*$valor_hora2;
                            @endphp
                          @else
                            @php
                              $piezas_meta = ($moduloII->piezas_meta/$horas_laboradas)*$valor_hora;
                            @endphp
                          @endif
                          @php
                            $aux = 0;
                          @endphp
                        @else
                          @php
                            $efic_meta = 0;
                            $piezas_meta =0;
                          @endphp
                        @endif
                        <td class="text-info">{{ $moduloII->modulo }} </td>
                        <td class="text-info">{{ $moduloII->team_leader }} </td>
                        <td  style="text-align: center">{{ number_format($piezas_meta,0) }}</td>
                        <td  style="text-align: center">{{ number_format($efic_meta,1).' %' }}</td>

                        @foreach($modulosII as $modsII)
                          @if($moduloII->modulo == $modsII->modulo)
                          @if($modII->min_presencia_netos == 0)
                          @php
                              $efic_real = 0;
                          @endphp

                      @else
                          @php
                        $efic_real = ((($modsII->min_producidos/$valor_hora)*$horas_laboradas)/$modII->min_presencia_netos)*100;
                          @endphp
                      @endif

                            <td  style="text-align: center">{{ number_format($modsII->piezas,0) }}</td>
                            @if($efic_meta > $efic_real)
                              @php
                                $color = "red";
                              @endphp
                            @else
                              @php
                                $color = "black";
                              @endphp
                            @endif

                            <td  style="text-align: center"><font color={{ $color }}>{{ number_format($efic_real,1).' %' }}</font></td>
                            <td  style="text-align: center">{{ number_format($modsII->min_producidos,0) }}</td>
                            <td  style="text-align: center">{{ number_format($modsII->proyeccion_minutos,0) }}</td>

                          @endif
                        @endforeach

                        @if($aux==0)
                            <td  style="text-align: center">{{ number_format(0,1).' %' }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                            <td  style="text-align: center">{{ number_format(0,0) }}</td>
                        @endif
                      </tr>

                    @endforeach

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <style>
            .ct-chart {
                position: relative;
            }
            .ct-legend {
                position: relative;
                z-index: 10;
                list-style: none;
                text-align: center;
            }
            .ct-legend li {
                position: relative;
                padding-left: 23px;
                margin-right: 10px;
                margin-bottom: 3px;
                cursor: pointer;
                display: inline-block;
            }
            .ct-legend li:before {
                width: 12px;
                height: 12px;
                position: absolute;
                left: 0;
                content: '';
                border: 3px solid transparent;
                border-radius: 2px;
            }
            .ct-legend li.inactive:before {
                background: transparent;
            }
            .ct-legend.ct-legend-inside {
                position: absolute;
                top: 0;
                right: 0;
            }
            .ct-legend.ct-legend-inside li{
                display: block;
                margin: 0;
            }
            .ct-legend .ct-series-0:before {
                background-color:#1889c2;
                border-color: #1889c2;
            }
            .ct-legend .ct-series-1:before {
                background-color: #d70206;
                border-color:#d70206;
            }

            .table-cebra {
          border: solid 1px #ccc;
          border-spacing: 0;
          height: 400px;

       }
       .table-cebra thead th {
          background: white;
          color:#1889c2;
        /*  position: sticky;
          top:0px;*/
       }
       .table-cebra th,
       .table-cebra td {
          border-right: 1px ;
          min-width: 100px;
          padding: 0.5rem;
          text-align: left;
       }
        .table-cebra th:last-child,
        .table-cebra td:last-child {
          border-right: 0;
        }
        .table-cebra td {
            border-bottom: 1px solid #ccc;
        }
        .table-cebra tbody tr  {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n)  {
          background: #f2f2f2;
       }
       .table-container{
        max-width: 100%;
        overflow-x: scroll;
       }

       .table-cebra .sticky {
          position: sticky ;
          left: 0;
       }
       .table-cebra tbody tr .sticky {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n) .sticky {
          background: #f2f2f2;
       }

       .table-cebra .sticky-2 {
          position: sticky ;
          top: 0;
       }
       .table-cebra tbody tr .sticky-2 {
          background: white;
       }

       .table-cebra .sticky-3 {
          position: sticky ;
          left: 500;
          top: 0;
       }

       .table-cebra .sticky-22 {
          top: 60;
       }

       .table-cebra .sticky2 {
          position: sticky ;
          left:100px;
       }
       .table-cebra tbody tr .sticky2 {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n) .sticky2 {
          background: #f2f2f2;
       }
       .table-cebra .sticky3 {
          position: sticky ;
          left:200px;
       }
       .table-cebra tbody tr .sticky3 {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n) .sticky3 {
          background: #f2f2f2;
       }
       .table-cebra .sticky4 {
          position: sticky ;
          left:300px;
       }
       .table-cebra tbody tr .sticky4 {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n) .sticky4 {
          background: #f2f2f2;
       }
       .table-cebra .sticky5 {
          position: sticky ;
          left:400px;
       }
       .table-cebra tbody tr .sticky5 {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n) .sticky5 {
          background: #f2f2f2;
       }
       .table-cebra .sticky6 {
          position: sticky ;
          left:500px;
       }
       .table-cebra tbody tr .sticky6 {
          background: white;
       }
       .table-cebra tbody tr:nth-child(2n) .sticky6 {
          background: #f2f2f2;
       }
         </style>
@endsection

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <!-- DataTables JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>

$(document).ready(function () {
  $('#dtHorizontalVerticalExample').DataTable({
    "scrollX": true,
    "scrollY": 200,
  });
  $('.dataTables_length').addClass('bs-select');

// Verifica si la tabla ya está inicializada antes de inicializarla nuevamente
if (!$.fn.dataTable.isDataTable('#tablaSupervisorI')) {
              $('#tablaSupervisorI').DataTable({
                  lengthChange: false,
                  searching: true,
                  paging: false,
                  pageLength: 5,
                  autoWidth: false,
                  responsive: true,
                  bInfo: false,

              });
          }

if (!$.fn.dataTable.isDataTable('#tablaSupervisorII')) {
              $('#tablaSupervisorII').DataTable({
                  lengthChange: false,
                  searching: true,
                  paging: false,
                  pageLength: 5,
                  autoWidth: false,
                  responsive: true,
                  bInfo: false,
              });
          }
if (!$.fn.dataTable.isDataTable('#tablaModulosI')) {
              $('#tablaModulosI').DataTable({
                  lengthChange: false,
                  searching: true,
                  paging: false,
                  pageLength: 5,
                  autoWidth: false,
                  responsive: true,
                  bInfo: false,

              });
          }
if (!$.fn.dataTable.isDataTable('#tablaModulosII')) {
              $('#tablaModulosII').DataTable({
                  lengthChange: false,
                  searching: true,
                  paging: false,
                  pageLength: 5,
                  autoWidth: false,
                  responsive: true,
                  bInfo: false,

              });
          }
});
</script>

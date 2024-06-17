@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('avanceproduccion')])

@section('content')
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
  <div class="content">

    <div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">

                <i class="card-tittle"></i>
                <font size=4+><p>{{ __('Avance Diario')  }}</br>General</p></font>
              </div>
              <p class="card-category">
              @php
                date_default_timezone_set('America/Mexico_City');
                setlocale(LC_TIME, "spanish");

                $hoy= strftime(" %d de %B ");
                $hoy_hora = strftime("%H:%M ");
                $hora = date("H").":00 ";
                $dia_semana = date("N");
              @endphp
              @if($hora_actualizacion != $hora || $hora_actualizacion == 'N/D' )
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
              @if($eficiencia_dia > $efic_total)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
                <p class="stats"><span class="text-info"> Eficiencia Meta : </span><b>  {{ ' '.number_format($eficiencia_dia,2).' %' }}</b></p>
                <p class="stats"><span class="text-info"> Eficiencia Real : </span><b><font color={{ $color }}>  {{ ' '.number_format($efic_total,0).' %' }}</font></b></p>

            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-info"> Piezas Meta : </span><b>  {{ ' '.number_format( $cantidad_dia,2) }}</b></p>
                <p class="stats"><span class="text-info"> Piezas Reales :</span><b>  {{ ' '.number_format( $cantidad_diaria,0) }}</b></p>
                <p class="stats"><span class="text-info"> Diferencia :</span><b> <font color=red>  {{ ' '.number_format(($cantidad_diaria-$cantidad_dia),0) }}</font></b></p>
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
      <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">

                <i class="card-tittle"></i>

                <font size=4+><p>{{ __('Avance Acumulado') }}</br>General</p></font>
              </div>
              <p class="card-category">
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ color={{ $color2 }} >Última Actualización : {{$hora_actualizacion.' hrs.' }}</font></b>
                <small></small>
              </h3>
            </div>

            <div class="card-footer" style="align:center">
            @if($eficiencia_total > $eficiencia_semanal)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
              <p class="stats"><span class="text-info"> Eficiencia Meta :</span><b>  {{  ' '.$eficiencia_total.' %' }}</b></p>
              <p class="stats"><span class="text-info"> Eficiencia Real :</span><b> <font color={{ $color }}> {{ ' '.number_format($eficiencia_semanal,0).' %'  }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-info"> Piezas Meta : </span><b>  {{ ' '.number_format( $cantidad_total,2) }}</b></p>
                <p class="stats"><span class="text-info"> Piezas Reales :</span><b>  {{ ' '.number_format($cantidad_diaria,0) }}</b></p>
                <p class="stats"><span class="text-info"> Diferencia :</span><b> <font color=red> {{ ' '.number_format($cantidad_semanal+$cantidad_diaria-$cantidad_total,0) }}</font></b></p>

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
                <font size=3+><p>{{ __('Planta I ') }}</p><p>{{ __('Avance Diario ') }}</p></font>
              </div>
              <p class="card-category">
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ color={{ $color2 }} >Última Actualización : {{$hora_actualizacion.' hrs.' }}</font></b>
                <small></small>
              </h3>
            </div>

            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{ ' '.number_format($eficiencia_dia_plantaI,0).' %' }}</b></p>
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b> <font color={{ $color }}>  {{ ' '.number_format($eficiencia_plantaI,0).' %'}}</font></b></p>

            </div>
            <div class="card-footer" style="align:center">
            @if($eficiencia_dia_plantaI > $eficiencia_plantaI)
                @php
                  $color = "red";
                @endphp
              @else
                @php
                  $color = "black";
                @endphp
              @endif
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{ ' '.number_format($cantidad_plantaI,0) }}</b></p>
                <p class="stats"><span class="text-success"> Piezas Reales :</span><b>  {{ ' '.number_format($real_x_plantaI,0) }}</b></p>
                <p class="stats"><span class="text-success"> Diferencia :</span><b> <font color=red> {{ number_format($real_x_plantaI-$cantidad_plantaI,0) }} </font></b></p>

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
                <font size=3+><p>{{ __('Planta II ') }}</p><p>{{ __('Avance Diario ') }}</p></font>
              </div>
              <p class="card-category">
              <h3 class="card-title"><b><font size=5+> {{ $hoy.'  /  '.$hoy_hora.'hrs.' }}</font></br><font size=5+ color={{ $color2 }} >Última Actualización : {{$hora_actualizacion.' hrs.' }}</font></b>
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
            <p class="stats"><span class="text-warning"> Eficiencia Meta :</span><b>  {{ ' '.number_format($eficiencia_dia_plantaII,0).' %' }}</b></p>
              <p class="stats"><span class="text-warning"> Eficiencia Real :</span><b>  <font color={{ $color }}> {{ ' '.number_format($eficiencia_plantaII,0).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
            <p class="stats"><span class="text-warning"> Piezas Meta : </span><b>  {{ ' '.number_format($cantidad_plantaII,0) }}</b></p>
                <p class="stats"><span class="text-warning"> Piezas Reales :</span><b>  {{ ' '.number_format($real_x_plantaII,0) }}</b></p>
                <p class="stats"><span class="text-warning"> Diferencia :</span><b> <font color=red> {{ ' '.number_format($real_x_plantaII-$cantidad_plantaII,0) }} </font></b></p>
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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{  number_format($eficiencia_dia_VS,0).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b> <font color={{ $color }}>  {{ number_format($eficiencia_VS,0).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{ number_format($cantidad_VS,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real :</span><b>  {{number_format($real_VS,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>  <font color=red>{{ number_format($real_VS-$cantidad_VS,0) }}</font></b></p>
            </div>


        <!--   <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>

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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{  number_format($eficiencia_dia_CHICOS,0).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b><font color={{ $color }}>  {{ number_format($eficiencia_CHICOS,0).' %'  }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{ number_format($cantidad_CHICOS,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real :</span><b>  {{ number_format($real_CHICOS,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b><font color=red> {{  number_format($real_CHICOS-$cantidad_CHICOS,0)  }}</font></b></p>
            </div>



         <!--  <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>

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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{number_format($eficiencia_dia_BN3,0).' %' }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b> <font color={{ $color }}> {{ number_format($eficiencia_BN3,0).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{  number_format($cantidad_BN3,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real :</span><b>  {{ number_format($real_BN3,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b> <font color=red> {{ number_format($real_BN3-$cantidad_BN3,0) }}</font></b></p>
            </div>



        <!--   <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>

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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{  number_format($eficiencia_dia_NU,0).' %' }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b> <font color={{ $color }}> {{ number_format($eficiencia_NU,0).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{ number_format($cantidad_NU,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Reales :</span><b>  {{ number_format($real_NU,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b> <font color=red> {{ number_format($real_NU-$cantidad_NU,0) }} </font></b></p>
            </div>

          -
        <!--   <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>

      </div>
      <div class="row">
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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{  number_format($eficiencia_dia_MARENA,0).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b> <font color={{ $color }}> {{ number_format($eficiencia_MARENA,0).' %' }}</font></b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Meta : </span><b>  {{ number_format($cantidad_MARENA,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real :</span><b>  {{ number_format($real_MARENA,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b>  <font color=red>{{ number_format($real_MARENA-$cantidad_MARENA,0) }}</font></b></p>
            </div>


        <!--   <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
            <!-- <div class="card-icon">

                <i class="card-tittle"></i>
                <p>{{ __('V.S.') }}</p>
              </div>-->
              <p class="card-category"></br>
              <h3 class="card-title"><b>{{ __('LECOQ') }}</b>
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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{  number_format($eficiencia_dia_PACIFIC,0).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b>   <font color={{ $color }}>{{ number_format($eficiencia_PACIFIC,0).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta : </span><b>  {{ number_format($cantidad_PACIFIC,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real :</span><b> {{ number_format($real_LECOQ,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b><font color=red>  {{  number_format($real_LECOQ-$cantidad_PACIFIC,0)  }}</font></b></p>
            </div>


         <!--  <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>
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
            <p class="stats"><span class="text-success"> Eficiencia Meta :</span><b>  {{  number_format($eficiencia_dia_BELL,0).' %'  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
              <p class="stats"><span class="text-success"> Eficiencia Real :</span><b>  <font color={{ $color }}> {{ number_format($eficiencia_BELL,0).' %' }}</font></b></p>
            </div>

            <div class="card-footer" style="align:center">
                <p class="stats" ><span class="text-success"> Piezas Meta : </span><b>  {{ number_format($cantidad_BELL,0)  }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Piezas Real :</span><b>  {{ number_format($real_BELL,0) }}</b></p>
            </div>
            <div class="card-footer" style="align:center">
                <p class="stats"><span class="text-success"> Diferencia :</span><b><font color=red>  {{  number_format($real_BELL-$cantidad_BELL,0)  }}</font></b></p>
            </div>


         <!--  <div class="card-footer">
              <div class="stats">
                <i class="material-icons ">date_range</i>
                <a href="{{ route('excepciones') }}">Detalle...</a>
              </div>
            </div>-->
          </div>
        </div>

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
                        <i class="material-icons">cloud</i> Team Leader
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#messages" data-toggle="tab">
                        <i class="material-icons">code</i> Modulos
                        <div class="ripple-container"></div>
                      </a>
                    </li>
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
                      <table class="table table-hover">
                        <thead class="text-primary">
                          <th>Team Leader</th>
                        <!-- <th>Modulo</th>     -->
                            <th style="text-align: center; width:200px">Piezas Meta Diaria</th>
                            <th style="text-align: center; width:200px">Piezas  Reales</th>
                            <th style="text-align: center; width:200px">Eficiencia</th>
                            <th style="text-align: center; width:200px">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px">Proyeccion Minutos</th>
                        </thead>
                    <tbody>
                    @foreach($team_leader as $team)
                      <tr>
                       <!-- <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="">
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>-->
                        <td class="text-info">{{ $team->team_leader }} </td>
                        <td  style="text-align: center">{{ number_format($team->piezas_meta,0) }}</td>
                        <td  style="text-align: center">{{ number_format($team->piezas,0) }}</td>
                        @if($team->eficiencia_dia > $team->efic)
                          @php
                            $color = "red";
                          @endphp
                        @else
                          @php
                            $color = "black";
                          @endphp
                        @endif

                        <td  style="text-align: center"><font color={{ $color }}>{{ number_format($team->efic,0).' %' }}</font></td>
                        <td  style="text-align: center">{{ number_format($team->min_producidos,0) }}</td>
                        <td  style="text-align: center">{{ number_format($team->proyeccion_minutos,0) }}</td>

                      </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>
                <div class="tab-pane active" id="settings">


                <div class="tab-pane active" id="settings">
                  <table class="table-cebra table-fixed">

                      <thead>
                        <th style="text-align: center" class="sticky sticky-2">Team Leader</th>
                        <th style="text-align: center" class="sticky2 sticky-2">Area</th>
                        <th style="text-align: center" class="sticky3 sticky-2">Modulo</th>
                       <!-- <th style="text-align: center" class="sticky4">Estilo</th>-->
                        <th style="text-align: center" class="sticky4 sticky-2">Piezas Meta</th>
                        <th style="text-align: center" class="sticky5 sticky-2">Eficiencia</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 09:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-success sticky-3">Meta 10:00</th>
                        <th style="text-align: center" class="text-success sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-success sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-success sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-success sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-info sticky-3">Meta 11:00</th>
                        <th style="text-align: center" class="text-info sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-info sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-info sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-info sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 12:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 13:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 14:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 15:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 16:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 17:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Meta 18:00</th>
                        <th style="text-align: center" class="text-warning sticky-3">Piezas</th>
                        <th style="text-align: center" class="text-warning sticky-3">Efic</br>(%)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Minutos </br>(Producidos)</th>
                        <th style="text-align: center" class="text-warning sticky-3">Proyeccion</br>(Minutos)</th>
                      </thead>


                        <tbody>
                       @foreach($planeacion as $plan)
                          <tr>
                            <td style="text-align: left" class="sticky sticky-22 ">{{ $plan->team_leader}}</td>
                            <td style="text-align: center" class="sticky2 sticky-22">{{ $plan->cliente}}</td>
                            <td style="text-align: center" class="sticky3 sticky-22">{{ $plan->Modulo}}</td>
                            @foreach($planeacion_meta as $meta)
                              @if($plan->Modulo == $meta->modulo)
                                <td style="text-align: center" class="sticky4 sticky-22">{{ number_format($meta->cantidad_d1,0)}}</td>
                                <td style="text-align: center" class="sticky5 sticky-22">{{ ($meta->eficiencia_d1).' %'}}</td>
                                @forelse($planeacion_09 as $plan09)
                                  @if($plan09->Modulo == $meta->modulo)
                                    <td style="text-align: right" >{{ number_format($plan09->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan09->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan09->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan09->min_producidos,0)}}</td>
                                    <td style="text-align:right" >{{ number_format($plan09->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_10 as $plan10)
                                  @if($plan10->Modulo == $meta->modulo)
                                    <td style="text-align: right" >{{ number_format($plan10->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan10->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan10->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan10->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan10->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse

                                @forelse($planeacion_11 as $plan11)
                                  @if($plan11->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan11->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan11->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan11->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan11->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan11->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_12 as $plan12)
                                  @if($plan12->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan12->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan12->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan12->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan12->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan12->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_13 as $plan13)
                                  @if($plan13->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan13->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan13->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan13->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan13->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan13->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_14 as $plan14)
                                  @if($plan14->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan14->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan14->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan14->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan14->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan14->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_15 as $plan15)
                                  @if($plan15->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan15->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan15->piezas}}</td>
                                    <td style="text-align: right" >{{ number_format($plan15->efic_total,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan15->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan15->proyeccion_minutos,0)}}</td>
                                  @endif
                               @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_16 as $plan16)
                                  @if($plan16->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan16->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan16->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan16->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan16->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan16->proyeccion_minutos,0)}}</td>
                                    @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_17 as $plan17)
                                  @if($plan17->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan17->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan17->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan17->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan17->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan17->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                                @forelse($planeacion_18 as $plan18)
                                  @if($plan18->Modulo == $plan->Modulo)
                                    <td style="text-align: right" >{{ number_format($plan18->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan18->piezas,0)}}</td>
                                    <td style="text-align: right" >{{ $plan18->efic_total}}</td>
                                    <td style="text-align: right" >{{ number_format($plan18->min_producidos,0)}}</td>
                                    <td style="text-align: right" >{{ number_format($plan18->proyeccion_minutos,0)}}</td>
                                  @endif
                                @empty
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                    <td style="text-align: center" >{{ 0}}</td>
                                @endforelse
                              @endif
                            @endforeach
                          </tr>
                       @endforeach

                        </tbody>
                    </table>
                  </div>
              </div>
                <div class="tab-pane" id="messages">
                  <table class="table">
                    <div class="card-body table-responsive">
                      <table class="table table-hover">
                        <thead class="text-primary">
                          <th>Modulos</th>
                          <th>Team Leader</th>
                            <th style="text-align: center; width:200px">Piezas Meta Diaria</th>
                            <th style="text-align: center; width:200px">Piezas  Reales</th>
                            <th style="text-align: center; width:200px">Eficiencia</th>
                            <th style="text-align: center; width:200px">Minutos  Producidos</th>
                            <th style="text-align: center; width:200px">Proyeccion Minutos</th>

                        </thead>
                    <tbody>
                    @foreach($modulos as $mod)
                      <tr>

                        <td class="text-info">{{ $mod->modulo }} </td>
                        <td class="text-info">{{ $mod->team_leader }} </td>
                       <!-- <td style="text-align: center">{{ $mod->piezas_meta }} </td>         -->
                        <td  style="text-align: center">{{ number_format(($mod->piezas_meta/$horas_laboradas)*$valor_hora,0) }}</td>
                        <td  style="text-align: center">{{ number_format($mod->piezas,0) }}</td>
                        @if($mod->eficiencia_dia > $mod->efic)
                          @php
                            $color = "red";
                          @endphp
                        @else
                          @php
                            $color = "black";
                          @endphp
                        @endif

                        <td  style="text-align: center"><font color={{ $color }}>{{ number_format($mod->efic,0).' %' }}</font></td>
                        <td  style="text-align: center">{{ number_format($mod->min_producidos,0) }}</td>
                        <td  style="text-align: center">{{ number_format($mod->proyeccion_minutos,0) }}</td>

                      </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>

@endsection
<script>

$(document).ready(function () {
  $('#dtHorizontalVerticalExample').DataTable({
    "scrollX": true,
    "scrollY": 200,
  });
  $('.dataTables_length').addClass('bs-select');
});
</script>

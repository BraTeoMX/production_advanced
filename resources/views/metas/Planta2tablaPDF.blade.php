<!DOCTYPE html>
<html>
<head>
    <title>Produccion</title>
<style>
@page {
            margin: 0cm 0cm;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

                /* Estilos generales para la tabla */
                    .propiedadNueva{
                        background-color: #bbcdce;
                    }

                    .propiedadNuevaN{
                        background-color: #bbcdce;
                        font-weight: bold;
                    }
        body {
            margin: 1cm;

        }
        table {
            color: black;
            width: 100%; /* Asegura que la tabla use todo el ancho disponible */
            font-size: 10px; /* Reduce el tamaño de la fuente */
            border-collapse: collapse; /* Elimina el espacio entre las celdas */
        }

        /* Asegúrate de que los colores de fondo se impriman */
        th, td {
            padding: 2px; /* Reduce el padding para hacer más compacta la celda */
            text-align: center; /* Centra el contenido de las celdas */
            border: 1px solid black; /* Define bordes para mejorar la legibilidad */
        }
        /* Estilos específicos para las celdas con bordes */
        .borde {
            border: 1px solid black;
        }

        /* Estilos específicos para las celdas sin bordes */
        .sin-borde {
            border: none;
        }

            /* Colores de las cabeceras de colores */
            .green { background-color: #00B0F0; }
            .light-green { background-color: #00B050; }
            .yellow { background-color: #FFFF00; }
            .SaddleBrown { background-color: #C65911; }
            .red { background-color: #FF0000; }
            .peach { background-color: #A6A6A6; }
            .grey { background-color: #F9F9EB; }


</style>
</head>
<body>
    <div id="encabezado">
        <table style="width:100%; align:center">
        <tr class="sin-borde">
            <td class="sin-borde" width=30% style="text-align:center"><img src="../public/material/img/logo.png" width="100px" heigth="82px" ></td>
            <td class="sin-borde" width=30% style="text-align:center; font-size: 12px "><h3>SEGUIMIENTO A CUMPLIMIENTO DE METAS<br>Planta San Bartolo</h3></td>
            <td class="sin-borde" style="text-align:center; font-size: 10px" >.</td>
         </tr>
        <br>
        <h2 style="font-size: 16px ">Reporte General </h2>
        </table>
        <!-- Este POST manda a llamar la funcion del controller llamdo Reportes -->

          <!-- Inicio Seccion de la primera tabla -->
          <table BORDER>
            <tr>
                <th rowspan="2"></th>
                <th rowspan="2"></th>
                {{-- Mostrar encabezados de mes --}}
                @foreach ($mesesAMostrar as $mes => $semanas)
                    @php
                        $semanasVisibles = count(array_intersect(range($semanaInicio, $semanaFin), $semanas));
                    @endphp
                    @if ($semanasVisibles)
                        <th colspan="{{ $semanasVisibles*2 }}" style="text-align: center;">{{ strtoupper($mes) }}</th>
                    @endif
                @endforeach
            </tr>
            <tr>
                {{-- Mostrar encabezados de semana --}}
                @foreach ($mesesAMostrar as $mes => $semanas)
                    @foreach ($semanas as $semana)
                        @if ($semana >= $semanaInicio && $semana <= $semanaFin)
                            <th colspan="2" style="text-align: center;">SEMANA {{ $semana }}</th>
                        @endif
                    @endforeach
                @endforeach
                </tr>
            <tr>
                <th>&nbsp;#</th>
                <th>Total de Modulos</th>
               {{-- Solo mostrar las columnas de semanas dentro del rango seleccionado --}}
               @for ($semana = $semanaInicio; $semana <= $semanaFin; $semana++)
               @php
                   $valor = $contadorTSplanta2[$semana];
                   $valorSuma = $contadorSumaP2[$semana];
                   //dd($valor, $semana, $contadorTS[$semana]);
                   // Valor total (puede ser cualquier número)
                   $total = $valor;
                   // Calcula el porcentaje

                   $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;
                   //dd($porcentaje, $contadorTS[$semana]);
                   if($porcentaje == 0){
                       $porcentaje = '0';
                   }
                   $porcentajeSuma = ($total != 0) ? number_format(($valorSuma / $total) * 100, 2) : 0;

                   @endphp
                       <td class="semana semana{{ $semana }}">&nbsp;{{ $contadorTSplanta2[$semana] }}&nbsp;</td><td class="semana semana{{ $semana }}">{{-- {{$porcentaje}}% - --}}<strong> {{$porcentaje}}% </strong></td>
                   @endfor
               </tr>
            @for ($i = 1; $i <= 7; $i++)
                <tr>
                    <th>{{ $i }}</th>
                    <th id="dato{{ $i }}" style="background-color: {{ $colores[$i-1] }}; text-align: left;" >&nbsp;{{ $titulos[$i-1] }}&nbsp;</th>
                    @for ($semana = $semanaInicio; $semana <= $semanaFin; $semana++)
                        @php
                            $valor = $contadoresSemanaPlanta2[$semana][$i];
                            $total = $contadorTSplanta2[$semana];
                            $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;
                            if($porcentaje == 0){
                                $porcentaje ='0';
                            }
                            $porcentaje = number_format($porcentaje, 2)
                        @endphp
                        <td class="semana semana{{ $semana }}">&nbsp;&nbsp;{{ $contadoresSemanaPlanta2[$semana][$i] }}&nbsp;</td>
                            <td class="semana semana{{ $semana }}"> {{$porcentaje}}% </td>
                    @endfor
                </tr>
            @endfor
            <!-- Nueva fila al final de la tabla -->
            <tr>
                <td class="propiedadNuevaN">%</td>
                <td class="propiedadNuevaN">Cumplimiento en tiempo </td>
                @for ($semana = $semanaInicio; $semana <= $semanaFin; $semana++)

                    @foreach ($mesesAMostrar as $mes => $semanas)
                        @foreach ($semanas as $semanaMostrar)
                            @if ($semanaMostrar == $semana)
                                <td class="propiedadNueva"> {{ $TcontadorSuma3Planta2[$semana] }} </td>
                                <td class="propiedadNuevaN"> {{ $Tporcentajes3Planta2[$semana] }}% </td>
                            @endif
                        @endforeach
                    @endforeach
                @endfor
            </tr>
            <!-- Nueva fila al final de la tabla -->
            <tr>
                <td class="propiedadNuevaN">%</td>
                <td class="propiedadNuevaN">Cumplimiento con TE (Viernes)</td>
                @for ($semana = $semanaInicio; $semana <= $semanaFin; $semana++)

                    @foreach ($mesesAMostrar as $mes => $semanas)
                        @foreach ($semanas as $semanaMostrar)
                            @if ($semanaMostrar == $semana)
                                <td class="propiedadNueva"> {{ $TcontadorSumaPlanta2[$semana] }} </td>
                                <td class="propiedadNuevaN"> {{ $TporcentajesPlanta2[$semana] }}% </td>
                            @endif
                        @endforeach
                    @endforeach
                @endfor
            </tr>
        </table>
        <br>
        <br>
        <h2 style="font-size: 17px ">Reporte Supervisor</h2>
        <!-- Cambio para anidar ambas tablas  -->

        <table BORDER id="myTable">
            <thead>
                <tr>
                    <th rowspan="2">Supervisor</th>
                    <th rowspan="2">Modulo</th>

                    {{-- Mostrar encabezados de mes --}}
                    @foreach ($mesesAMostrar as $mes => $semanas)
                    <th colspan="{{ count(array_intersect(range($semanaInicio, $semanaFin), $semanas)) }}" style="text-align: center;">
                        {{ strtoupper($mes) }}
                    </th>
                    @endforeach
                </tr>
                <tr>
                {{-- Mostrar encabezados de semana --}}
                @foreach ($mesesAMostrar as $mes => $semanas)
                    @foreach ($semanas as $semana)
                        @if ($semana >= $semanaInicio && $semana <= $semanaFin)
                            <th style="text-align: center;">SEMANA {{ $semana }}</th>
                        @endif
                    @endforeach
                @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($datosProduccionIntimark2 as $produccion)
                    <tr>
                        <td style="text-align: left">{{ $produccion->nombre }}</td>
                        <td>{{ $produccion->modulo }}</td>
                        @for ($i = $semanaInicio; $i <= $semanaFin; $i++)
                        @php
                            $contadoresSemanaPlanta2 = $produccion->{"semana$i"};
                            $colorClass = $colorClasses[$contadoresSemanaPlanta2] ?? '';
                            $extraValue = $produccion->{"extra$i"};
                        @endphp
                        <td class="{{ $colorClass }}">
                            @if(in_array($extraValue, [1, 2, 3]))
                                <strong>* * * </strong>
                            @endif
                        </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <p>Nota:</p>
        <p> <strong>***</strong> CUMPLEN Y APOYAN TIEMPO EXTRA</p>

</body>

</html>

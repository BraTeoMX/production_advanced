<!DOCTYPE html>
<html>
<head>
    <title>Produccion</title>
<style>
@page {
            margin: 0cm 0cm;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
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

</style>
</head>
<body>
    <div id="encabezado">
        <table style="width:100%; align:center">
        <tr class="sin-borde">
            <td class="sin-borde" width=30% style="text-align:center"><img src="../public/img/logo.png" width="100px" heigth="82px" ></td>
            <td class="sin-borde" width=30%>&nbsp;</td>
            <td class="sin-borde" style="text-align:center; font-size: 10px" >encabezado derecho:</td>       
         </tr>
         <tr>
            <td class="sin-borde" style="text-align:center; font-size: 10px " colspan =3><h3>SEGUIMIENTO A CUMPLIMIENTO DE METAS </h3></td>
         </tr>
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
                        $valor = $contadorTS[$semana];
                        // Valor total (puede ser cualquier número)
                        $total = $valor;
                        // Calcula el porcentaje
                        
                        $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;

                        if($porcentaje == 0){
                            $porcentaje = '0';
                        }
                    @endphp
                        <td class="semana semana{{ $semana }}">&nbsp;{{ $contadorTS[$semana] }}&nbsp;</td><td class="semana semana{{ $semana }}"> {{$porcentaje}}% </td>
                @endfor
            </tr>
            @for ($i = 1; $i <= 7; $i++)
                <tr>
                    <th>{{ $i }}</th>
                    <th id="dato{{ $i }}" style="background-color: {{ $colores[$i-1] }}; text-align: left;" >&nbsp;{{ $titulos[$i-1] }}&nbsp;</th>
                    @for ($semana = $semanaInicio; $semana <= $semanaFin; $semana++)
                        @php
                            $valor = $contadoresSemana[$semana][$i];
                            $total = $contadorTS[$semana];
                            $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;
                            if($porcentaje == 0){
                                $porcentaje ='0';
                            }
                            $porcentaje = number_format($porcentaje, 2)
                        @endphp
                        <td class="semana semana{{ $semana }}">&nbsp;&nbsp;{{ $contadoresSemana[$semana][$i] }}&nbsp;</td>
                            <td class="semana semana{{ $semana }}"> {{$porcentaje}}% </td>
                    @endfor
                </tr>
            @endfor
        </table>
        <br>
        
</body>

</html>
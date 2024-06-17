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
            <td class="sin-borde" width=30%>&nbsp;</td>
            <td class="sin-borde" style="text-align:center; font-size: 10px" >.</td>
         </tr>
         <tr>
            <td class="sin-borde" style="text-align:center; font-size: 10px " colspan =3><h3>SEGUIMIENTO A CUMPLIMIENTO DE METAS<br>Planta San Bartolo </h3></td>
         </tr>
        </table>
        <!-- Este POST manda a llamar la funcion del controller llamdo Reportes -->

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
                        @endphp
                        <td class="{{ $colorClass }}">

                    </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>

</body>

</html>

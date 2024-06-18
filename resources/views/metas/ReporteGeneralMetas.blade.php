@extends('layouts.app', ['activePage' => 'reporteGeneral', 'titlePage' => __('Reporte General')])

@section('content')
    <div class="card" style="height: auto; width: auto;">
        <div class="card-header">
            <h3 class="card-title"><b><font size="6">Reporte Cumplimiento de Metas</font></b></h3>
        </div>

        <div id="accordion">
            <!-- Tarjeta para Planta 1 -->
            <div class="card">
                <div class="card-header" id="headingOne" style="background: #4F3C20;">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: white; font-size: 16px; font-weight: bold;">
                        Planta 1 - Ixtlahuaca
                    </button>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <!-- Inicio Sección de la primera tabla -->
                        <table BORDER>
                            <tr>
                                <th rowspan="2"></th>
                                <th rowspan="2"></th>
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    <th colspan="{{ count($semanas) * 2 }}" style="text-align: center;">{{ strtoupper($mes) }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    @foreach ($semanas as $semana)
                                        <th colspan="2" style="text-align: center;">SEMANA {{ $semana }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Total de Módulos</th>
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    @foreach ($semanas as $semana)
                                        <th class="semana semana{{ $semana }}">&nbsp;{{ $contadorTS[$semana] }}&nbsp;</th>
                                        <th class="semana semana{{ $semana }}"><strong>{{ $porcentaje }}%</strong></th>
                                    @endforeach
                                @endforeach
                            </tr>
                            @for ($i = 1; $i <= 7; $i++)
                                <tr>
                                    <th>{{ $i }}</th>
                                    <th id="dato{{ $i }}" style="background-color: {{ $colores[$i-1] }}; text-align: left;">&nbsp;{{ $titulos[$i-1] }}&nbsp;</th>
                                    @foreach ($mesesAMostrar as $mes => $semanas)
                                        @foreach ($semanas as $semana)
                                            @php
                                                $valor = $contadoresSemana[$semana][$i];
                                                $total = $contadorTS[$semana];
                                                $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;
                                            @endphp
                                            <td class="semana semana{{ $semana }}">&nbsp;&nbsp;{{ $valor }}&nbsp;</td>
                                            <td class="semana semana{{ $semana }}"> {{ $porcentaje }}% </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endfor
                        </table>
                        <br>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por nombre o módulo...">
                            </div>
                        </div>

                        <table BORDER id="myTable">
                            <thead>
                                <tr>
                                    <th rowspan="2">Supervisor</th>
                                    <th rowspan="2">Módulo</th>
                                    @foreach ($mesesAMostrar as $mes => $semanas)
                                        <th colspan="{{ count($semanas) }}" style="text-align: center;">{{ strtoupper($mes) }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($mesesAMostrar as $mes => $semanas)
                                        @foreach ($semanas as $semana)
                                            <th style="text-align: center;">SEMANA {{ $semana }}</th>
                                        @endforeach
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supervisoresPlanta1 as $supervisor)
                                    <tr>
                                        <td style="text-align: left">{{ $supervisor->nombre }}</td>
                                        <td>{{ $supervisor->modulo }}</td>
                                        @foreach ($mesesAMostrar as $mes => $semanas)
                                            @foreach ($semanas as $semana)
                                                @php
                                                    $produccion = $produccionPlanta1[$supervisor->id]->firstWhere('semana', $semana);
                                                    $valorSemanal = $produccion ? $produccion->valor : '';
                                                    $colorClass = $colorClasses[$valorSemanal] ?? '';
                                                    $extraValue = $produccion ? $produccion->te : 0;
                                                @endphp
                                                <td class="{{ $colorClass }}">
                                                    @if($extraValue)
                                                        <strong>* * * </strong>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Planta 2 -->
            <div class="card">
                <div class="card-header" id="headingTwo" style="background: #4F3C20;">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="color: white; font-size: 16px; font-weight: bold;">
                        Planta 2 - San Bartolo
                    </button>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <table BORDER>
                            <tr>
                                <th rowspan="2"></th>
                                <th rowspan="2"></th>
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    <th colspan="{{ count($semanas) * 2 }}" style="text-align: center;">{{ strtoupper($mes) }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    @foreach ($semanas as $semana)
                                        <th colspan="2" style="text-align: center;">SEMANA {{ $semana }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Total de Módulos</th>
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    @foreach ($semanas as $semana)
                                        <th class="semana semana{{ $semana }}">&nbsp;{{ $contadorTSplanta2[$semana] }}&nbsp;</th>
                                        <th class="semana semana{{ $semana }}"><strong>{{ $porcentaje }}%</strong></th>
                                    @endforeach
                                @endforeach
                            </tr>
                            @for ($i = 1; $i <= 7; $i++)
                                <tr>
                                    <th>{{ $i }}</th>
                                    <th id="dato{{ $i }}" style="background-color: {{ $colores[$i-1] }}; text-align: left;">&nbsp;{{ $titulos[$i-1] }}&nbsp;</th>
                                    @foreach ($mesesAMostrar as $mes => $semanas)
                                        @foreach ($semanas as $semana)
                                            @php
                                                $valor = $contadoresSemanaPlanta2[$semana][$i];
                                                $total = $contadorTSplanta2[$semana];
                                                $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;
                                            @endphp
                                            <td class="semana semana{{ $semana }}">&nbsp;&nbsp;{{ $valor }}&nbsp;</td>
                                            <td class="semana semana{{ $semana }}"> {{ $porcentaje }}% </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endfor
                        </table>
                        <br>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <input type="text" id="searchInput2" onkeyup="filterTable2()" placeholder="Buscar por nombre o módulo...">
                            </div>
                        </div>

                        <table BORDER id="myTable2">
                            <thead>
                                <tr>
                                    <th rowspan="2">Supervisor</th>
                                    <th rowspan="2">Módulo</th>
                                    @foreach ($mesesAMostrar as $mes => $semanas)
                                        <th colspan="{{ count($semanas) }}" style="text-align: center;">{{ strtoupper($mes) }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($mesesAMostrar as $mes => $semanas)
                                        @foreach ($semanas as $semana)
                                            <th style="text-align: center;">SEMANA {{ $semana }}</th>
                                        @endforeach
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supervisoresPlanta2 as $supervisor)
                                    <tr>
                                        <td style="text-align: left">{{ $supervisor->nombre }}</td>
                                        <td>{{ $supervisor->modulo }}</td>
                                        @foreach ($mesesAMostrar as $mes => $semanas)
                                            @foreach ($semanas as $semana)
                                                @php
                                                    $produccion = $produccionPlanta2[$supervisor->id]->firstWhere('semana', $semana);
                                                    $valorSemanal = $produccion ? $produccion->valor : '';
                                                    $colorClass = $colorClasses[$valorSemanal] ?? '';
                                                    $extraValue = $produccion ? $produccion->te : 0;
                                                @endphp
                                                <td class="{{ $colorClass }}">
                                                    @if($extraValue)
                                                        <strong>* * * </strong>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .propiedadNueva{
            background-color: #bbcdce;
        }
        .propiedadNuevaN{
            background-color: #bbcdce;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: solid 1px #ddd;
            color: black;
        }
        th {
            background-color: #bbcdce;
            color: #333;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .green { background-color: #00B0F0; }
        .light-green { background-color: #00B050; }
        .yellow { background-color: #FFFF00; }
        .SaddleBrown { background-color: #C65911; }
        .red { background-color: #FF0000; }
        .peach { background-color: #A6A6A6; }
        .grey { background-color: #F9F9EB; }
        .centered-content {
            text-align: center;
            vertical-align: middle;
        }
        .card-header {
            background-color: #f8f9fa;
            padding: 16px;
            border-bottom: solid 1px #ddd;
        }
        #searchInput, #searchInput2{
            width: 100%;
            padding: 10px 50px 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            outline: none;
            transition: all 0.3s ease-in-out;
        }
        #searchInput:focus, #searchInput2:focus {
            border-color: #0056b3;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        #searchInput::placeholder, #searchInput2::placeholder {
            color: #999;
        }
        .form-filter {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .form-filter select, .form-filter button {
            padding: 10px 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #fff;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .form-filter button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .form-filter-verde button{
            background-color: #00C9A7;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .form-filter button:hover {
            background-color: #0056b3;
        }
        .form-filter label {
            font-weight: bold;
        }
        .form-container {
            margin-bottom: 20px;
        }
    </style>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                var tdLeader = tr[i].getElementsByTagName("td")[0];
                var tdModule = tr[i].getElementsByTagName("td")[1];
                if (tdLeader || tdModule) {
                    if (tdLeader.textContent.toUpperCase().indexOf(filter) > -1 || tdModule.textContent.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function filterTable2() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput2");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable2");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                var tdLeader = tr[i].getElementsByTagName("td")[0];
                var tdModule = tr[i].getElementsByTagName("td")[1];
                if (tdLeader || tdModule) {
                    if (tdLeader.textContent.toUpperCase().indexOf(filter) > -1 || tdModule.textContent.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection

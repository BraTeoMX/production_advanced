
@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('Produccion Metas')])


@section('content')
    <div class="card" style="height: auto; width: auto;">
        <div class="card-header">
            <!--<h1>REPORTE SEGUIMIENTO A CUMPLIMIENTO DE METAS</h1>-->
            <h3 class="card-title"><b><font size=6+> Reporte Cumplimiento de Metas</font></b>
                <small></small>
              </h3>
        </div>
            <!-- Acordeón -->
            <div id="accordion">

              <!-- Tarjeta para Planta 1 -->
              <div class="card">
                <div class="card-header" id="headingOne">
                  <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Planta 1 - Ixtlahuaca
                    </button>
                  </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">
                    <!-- Contenido para Planta 1 -->
                    {{-- Agregar esto en alguna parte de tu vista --}}
                    <div class="form-container">
                        <form action="{{ route('metas.ReporteGeneral') }}" method="GET" class="form-filter ">
                            <label for="semana_inicio" style="text-align: center; width:200px">Semana de inicio:</label>
                            <select name="semana_inicio" id="semana_inicio">
                                <option value="" {{ request('semana_inicio') ? '' : 'selected' }}></option>
                                @for ($i = 1; $i <= $numSemanas; $i++)
                                    <option value="{{ $i }}" {{ request('semana_inicio') == $i ? 'selected' : '' }}>SEMANA {{ $i }}</option>
                                @endfor
                            </select>

                            <label for="semana_fin" style="text-align: center; width:200px">Semana de Fin:</label>
                            <select name="semana_fin" id="semana_fin">
                                <option value="" {{ request('semana_fin') ? '' : 'selected' }}></option>
                                @for ($i = 1; $i <= $numSemanas; $i++)
                                    <option value="{{ $i }}" {{ request('semana_fin') == $i ? 'selected' : '' }}>SEMANA {{ $i }}</option>
                                @endfor
                            </select>


                            <button type="submit" style="text-align: center; width:200px">Buscar</button>
                        </form>
                        <div class="form-container form-filter">
                            {{-- Botón desplegable para las opciones de PDF --}}
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Generar Reporte PDF
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" id="generatePdf1">Tabla General</a>
                                    <a class="dropdown-item" href="#" id="generatePdf2">Tabla Supervisor</a>
                                </div>
                            </div>
                            {{-- Formularios ocultos para cada acción de PDF --}}
                            <form method="POST" action="{{ route('metas.tablaPDF') }}" class="d-none" id="formPDF">
                                @csrf
                                <input type="hidden" name="semana_inicio" id="pdfSemanaInicio">
                                <input type="hidden" name="semana_fin" id="pdfSemanaFin">
                            </form>

                            <form method="POST" action="{{ route('metas.tabla2PDF') }}" class="d-none" id="form2PDF">
                                @csrf
                                <input type="hidden" name="semana_inicio" id="pdfSemanaInicio2">
                                <input type="hidden" name="semana_fin" id="pdfSemanaFin2">
                            </form>

                            {{-- <div class = "form-filter-verde">
                                {{-- Botón para exportar a Excel
                                <form id="formExportExcel" action="{{ route('metas.exportExcel') }}" method="POST" >
                                    @csrf
                                    <input type="hidden" name="semana_inicio" id="excelSemanaInicio">
                                    <input type="hidden" name="semana_fin" id="excelSemanaFin">
                                    <button type="button" id="exportarExcelBtn" class="btn btn-success">Exportar a Excel</button>
                                </form>
                            </div> --}}
                        </div>
                    </div>

                        <!-- Inicio Seccion de la primera tabla -->
                        <table BORDER>
                            <tr>
                                <th rowspan="2"></th>
                                <th rowspan="2"></th>
                                {{-- Mostrar encabezados de mes --}}
                                @foreach ($mesesAMostrar as $mes => $semanas)
                                    @php
                                        $semanasVisibles = count(array_intersect(range($semanaInicio, $semanaFin), $semanas));
                                        //dd($semanasVisibles);
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
                                        //dd($valor, $semana, $contadorTS[$semana]);
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
                        <!-- Fin Seccion de la primera tabla -->
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        {{-- Campo de búsqueda --}}
                        <div>
                            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por nombre o módulo...">
                        </div>
                    </div>

                    <!-- Inicio de tabla general -->
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
                            @foreach($datosProduccionIntimark1 as $produccion)
                                <tr>
                                    <td style="text-align: left">{{ $produccion->nombre }}</td>
                                    <td>{{ $produccion->modulo }}</td>
                                    @for ($i = $semanaInicio; $i <= $semanaFin; $i++)
                                    @php
                                        $valorSemanal = $produccion->{"semana$i"};
                                        $colorClass = $colorClasses[$valorSemanal] ?? '';
                                    @endphp
                                    <td class="{{ $colorClass }}">
                                        {{-- $valorSemanal --}}
                                </td>
                                    @endfor
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
                <div class="card-header" id="headingTwo">
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Planta 2 San Bartolo
                    </button>
                  </h5>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                  <div class="card-body">
                    <!-- Contenido para Planta 2 -->
                    {{-- Agregar esto en alguna parte de tu vista --}}
                    <div class="form-container">
                        <form action="{{ route('metas.ReporteGeneral') }}" method="GET" class="form-filter">
                            <label for="semana_inicio">Semana de inicio:</label>
                            <select name="semana_inicio" id="semana_inicioPlanta2">
                                <option value="" {{ request('semana_inicio') ? '' : 'selected' }}></option>
                                @for ($i = 1; $i <= $numSemanas; $i++)
                                    <option value="{{ $i }}" {{ request('semana_inicio') == $i ? 'selected' : '' }}>SEMANA {{ $i }}</option>
                                @endfor
                            </select>

                            <label for="semana_fin">Semana de Fin:</label>
                            <select name="semana_fin" id="semana_finPlanta2">
                                <option value="" {{ request('semana_fin') ? '' : 'selected' }}></option>
                                @for ($i = 1; $i <= $numSemanas; $i++)
                                    <option value="{{ $i }}" {{ request('semana_fin') == $i ? 'selected' : '' }}>SEMANA {{ $i }}</option>
                                @endfor
                            </select>


                            <button type="submit">Filtrar Semanas</button>
                        </form>
                        <div class="form-container form-filter">
                            {{-- Botón desplegable para las opciones de PDF --}}
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonPlanta2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Generar Reporte PDF
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonPlanta2">
                                    <a class="dropdown-item" href="#" id="generatePdf1Planta2">Tabla General</a>
                                    <a class="dropdown-item" href="#" id="generatePdf2Planta2">Tabla Supervisor</a>
                                </div>
                            </div>
                            {{-- Formularios ocultos para cada acción de PDF --}}
                            <form method="POST" action="{{ route('metas.Planta2tablaPDF') }}" class="d-none" id="formPDFplanta2">
                                @csrf
                                <input type="hidden" name="semana_inicio" id="pdfSemanaInicioPlanta2">
                                <input type="hidden" name="semana_fin" id="pdfSemanaFinPlanta2">
                            </form>

                            <form method="POST" action="{{ route('metas.Planta2tabla2PDF') }}" class="d-none" id="form2PDFplanta2">
                                @csrf
                                <input type="hidden" name="semana_inicio" id="pdfSemanaInicio2Planta2">
                                <input type="hidden" name="semana_fin" id="pdfSemanaFin2Planta2">
                            </form>

                            {{-- <div class = "form-filter-verde">
                                {{-- Botón para exportar a Excel
                                <form id="formExportExcel" action="{{ route('metas.exportExcel') }}" method="POST" >
                                    @csrf
                                    <input type="hidden" name="semana_inicio" id="excelSemanaInicio">
                                    <input type="hidden" name="semana_fin" id="excelSemanaFin">
                                    <button type="button" id="exportarExcelBtn" class="btn btn-success">Exportar a Excel</button>
                                </form>
                            </div> --}}
                        </div>
                    </div>

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
                                        // Valor total (puede ser cualquier número)
                                        $total = $valor;
                                        // Calcula el porcentaje

                                        $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;

                                        if($porcentaje == 0){
                                            $porcentaje = '0';
                                        }
                                    @endphp
                                        <td class="semana semana{{ $semana }}">&nbsp;{{ $contadorTSplanta2[$semana] }}&nbsp;</td><td class="semana semana{{ $semana }}"> {{$porcentaje}}% </td>
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
                        </table>
                        <br>
                        <!-- Fin Seccion de la primera tabla -->
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        {{-- Campo de búsqueda --}}
                        <div>
                            <input type="text" id="searchInput2" onkeyup="filterTable2()" placeholder="Buscar por nombre o módulo...">
                        </div>
                    </div>

                    <!-- Inicio de tabla general -->
                    <table BORDER id="myTable2">
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
                                        $valorSemanal = $produccion->{"semana$i"};
                                        $colorClass = $colorClasses[$valorSemanal] ?? '';
                                    @endphp
                                    <td class="{{ $colorClass }}">
                                        {{-- $valorSemanal --}}
                                </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                  </div>
                </div>
              </div>

            </div>
            <!-- Fin Acordeón -->

        <br>
        <br>
    </div>
    <style>
        /* Estilos generales para la tabla */
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-radius: 8px;
            overflow: hidden; /* Asegura que los bordes redondeados se apliquen en los bordes de la tabla */
        }

        th, td {
            padding: 12px 15px; /* Ajusta el padding para más espacio */
            text-align: center;
            border-bottom: solid 1px #ddd; /* Línea sutil entre filas */
            color: black;
        }

        th {
            background-color: #bbcdce; /* Color de fondo para los encabezados */
            color: #333; /* Color del texto para los encabezados */
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5; /* Color al pasar el ratón por encima de las filas */
        }

        /* Colores de las cabeceras de colores */
        .green { background-color: #548235; }
        .light-green { background-color: #00B050; }
        .yellow { background-color: #FFFF00; }
        .SaddleBrown { background-color: #C65911; }
        .red { background-color: #FF0000; }
        .peach { background-color: #FF9966; }
        .grey { background-color: #CFC7C5; }

        /* Clases adicionales */
        .centered-content {
            text-align: center;
            vertical-align: middle;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 16px;
            border-bottom: solid 1px #ddd;
        }

        /*Apartado para los diselos del input */
        #searchInput,
        #searchInput2{
        width: 100%; /* O un ancho específico, según tu diseño */
        padding: 10px 50px 10px;
        margin: 10px 0;
        border: 1px solid #ddd; /* Color de borde suave */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra suave */
        border-radius: 4px; /* Bordes redondeados */
        outline: none; /* Remueve el contorno al enfocar */
        transition: all 0.3s ease-in-out; /* Transición suave */
    }

    #searchInput:focus,
    #searchInput2:focus {
        border-color: #0056b3; /* Cambia el color del borde al enfocar */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada al enfocar */
    }

    #searchInput::placeholder,
    #searchInput2::placeholder {
        color: #999; /* Color del texto del placeholder */
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

    .form-filter select,
    .form-filter button {
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

    /* Estilo del texto de las etiquetas */
    .form-filter label {
        font-weight: bold;
    }

    /* Estilo del contenedor del formulario */
    .form-container {
        margin-bottom: 20px;
    }
    </style>

<script>
    function filterTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable"); // Asegúrate de poner el id correcto de tu tabla
        tr = table.getElementsByTagName("tr");

        // Recorre todas las filas de la tabla y oculta las que no coinciden con la búsqueda
        for (i = 1; i < tr.length; i++) { // Comienza en 1 para saltar el encabezado de la tabla
            // Obtén las celdas de "Team Leaders" y "Modulo"
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
{{-- Apartado de script de la segunda tabla --}}
<script>
    function filterTable2() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput2");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable2"); // Asegúrate de poner el id correcto de tu tabla
        tr = table.getElementsByTagName("tr");

        // Recorre todas las filas de la tabla y oculta las que no coinciden con la búsqueda
        for (i = 1; i < tr.length; i++) { // Comienza en 1 para saltar el encabezado de la tabla
            // Obtén las celdas de "Team Leaders" y "Modulo"
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

<script>
    // Asigna los valores a los formularios ocultos cuando se selecciona el rango de semanas
    document.getElementById('dropdownMenuButton').addEventListener('click', function() {
        var semanaInicio = document.getElementById('semana_inicio').value;
        var semanaFin = document.getElementById('semana_fin').value;

        document.getElementById('pdfSemanaInicio').value = semanaInicio;
        document.getElementById('pdfSemanaInicio2').value = semanaInicio;
        document.getElementById('pdfSemanaFin').value = semanaFin;
        document.getElementById('pdfSemanaFin2').value = semanaFin;
    });

    // Envía el primer formulario
    document.getElementById('generatePdf1').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('formPDF').submit();
    });

    // Envía el segundo formulario
    document.getElementById('generatePdf2').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('form2PDF').submit();
    });
</script>
{{-- Script para planta 2  --}}
<script>
    // Asigna los valores a los formularios ocultos cuando se selecciona el rango de semanas
    document.getElementById('dropdownMenuButtonPlanta2').addEventListener('click', function() {
        var semanaInicio = document.getElementById('semana_inicioPlanta2').value;
        var semanaFin = document.getElementById('semana_finPlanta2').value;

        document.getElementById('pdfSemanaInicioPlanta2').value = semanaInicio;
        document.getElementById('pdfSemanaInicio2Planta2').value = semanaInicio;
        document.getElementById('pdfSemanaFinPlanta2').value = semanaFin;
        document.getElementById('pdfSemanaFin2Planta2').value = semanaFin;
    });

    // Envía el primer formulario
    document.getElementById('generatePdf1Planta2').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('formPDFplanta2').submit();
    });

    // Envía el segundo formulario
    document.getElementById('generatePdf2Planta2').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('form2PDFplanta2').submit();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var exportButton = document.getElementById('exportarExcelBtn');
    var semanaInicioSelect = document.getElementById('semana_inicio');
    var semanaFinSelect = document.getElementById('semana_fin');
    var excelSemanaInicio = document.getElementById('excelSemanaInicio');
    var excelSemanaFin = document.getElementById('excelSemanaFin');

    exportButton.addEventListener('click', function() {
        // Asignar los valores seleccionados a los campos ocultos
        excelSemanaInicio.value = semanaInicioSelect.value;
        excelSemanaFin.value = semanaFinSelect.value;

        // Envía el formulario
        document.getElementById('formExportExcel').submit();
    });
});


</script>


<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
        } else {
          panel.style.display = "block";
        }
      });
    }
    </script>


@endsection

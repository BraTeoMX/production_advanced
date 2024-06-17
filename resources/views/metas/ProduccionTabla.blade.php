@extends('layouts.main')
@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialfront/assets/vendor/datatables.net.extensions/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="card" style="height: auto;" style="width: auto;">
    <div class="card-header">
        <h1>Titulo de encabezado: Tabla de produccion </h1>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3>SEGUIMIENTO A CUMPLIMIENTO DE METAS</h3>
        <div class="d-flex align-items-start">

            <form method="POST" action="{{ route('metas.filtrarSemanas') }}" id="registroProduccionForm">
                <div class="col-lg-6 col-md-6">
                    <label for="semana_inicio">Semana de inicio:</label>
                    <select name="semana_inicio" id="semana_inicio">
                        @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('semana_inicio', session('semana_inicio')) == $i ? 'selected' : '' }}>SEMANA {{ $i }}</option>
                        @endfor
                    </select>
                    <label for="semana_inicio">Semana de Fin:</label>
                    <select name="semana_fin" id="semana_fin">
                        @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('semana_fin', session('semana_fin')) == $i ? 'selected' : '' }}>SEMANA {{ $i }}</option>
                        @endfor
                    </select>
                    <button class="btn btn-primary" type="button" onclick="filtrarSemanas()">Filtrar Semanas</button>
                </div>
            </form>
            <div class="col-lg-6 col-md-6">
                <form method="POST" action="{{ route('metas.tablaPDF') }} ">
                    @csrf

                    <button type="submit" class="btn btn-primary" id="registrarBtnPDF">Generar reporte PDF</button>
                </form>
            </div>
        </div>
            <!-- Este POST manda a llamar la funcion del controller llamdo Reportes -->
            <form method="POST" action="{{ route('metas.ProduccionTabla') }}" id="registroProduccionForm">
                @csrf

                <br>
                <!--Inicio tabla de prueba  -->
                <table BORDER>
                    <tr>
                        <th></th>
                        <th></th>
                        @for ($semana = 1; $semana <= $numSemanas; $semana++)
                            <th colspan="2" class="semana{{ $semana}}">&nbsp;SEMANA {{ $semana }}&nbsp;</th>
                        @endfor
                    </tr>
                    <tr>
                        <th>&nbsp;#</th>
                        <th>Total de Modulos</th>
                        @for ($semana = 1; $semana <= $numSemanas; $semana++)
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
                    @php
                        $titulos = [
                            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
                            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
                            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
                            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
                            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
                            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
                            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
                            '** CUMPLEN Y APOYAN TE'
                        ];
                        $colores = [
                            '#548235',
                            '#00B050',
                            '#FFFF00',
                            '#FFC000',
                            '#FF0000',
                            '#FF9966',
                            '#FFFFFF',
                            ''
                        ];
                    @endphp
                    @for ($i = 1; $i <= 7; $i++)
                        <tr>
                            <th>&nbsp;{{ $i }}&nbsp;</th>
                            <th id="dato{{ $i }}" style="background-color: {{ $colores[$i-1] }};" >&nbsp;{{ $titulos[$i-1] }}&nbsp;</th>
                            @for ($semana = 1; $semana <= $numSemanas; $semana++)
                                @php
                                    $valor = $contadoresSemana[$semana][$i];
                                    $total = $contadorTS[$semana];
                                    $porcentaje = ($total != 0) ? number_format(($valor / $total) * 100, 2) : 0;
                                    if($porcentaje == 0){
                                        $porcentaje ='0';
                                    }
                                    $porcentaje = number_format($porcentaje, 2)
                                @endphp
                                <td class="semana semana{{ $semana }}">&nbsp;&nbsp;{{ $contadoresSemana[$semana][$i] }}&nbsp;</td><td class="semana semana{{ $semana }}"> {{$porcentaje}}% </td>
                            @endfor
                        </tr>
                    @endfor
                </table>
                <br>
            </form>
            <form method="POST" action="{{ route('metas.actualizarSeleccion') }}">
                @csrf
                <!-- Fin de tabla de prueba -->
                <table BORDER>
                    <thead>
                        <tr>
                            <th rowspan="2">&nbsp;Supervisor &nbsp;</th>
                            <th rowspan="2">&nbsp;Modulo&nbsp;</th>
                            <th colspan="4" style="text-align: center;">Enero </th>
                            <th colspan="4" style="text-align: center;">Febrero </th>
                            <th colspan="4" style="text-align: center;">Marzo </th>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= 10; $i++)
                                <th class="semana{{ $i }}">&nbsp;SEMANA {{ $i }}&nbsp;</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datosProduccion as $produccion)
                            <tr>
                                <td>{{ $produccion->nombre }}</td>
                                <td>{{ $produccion->modulo }}</td>
                                @for ($i = 1; $i <= $numSemanas; $i++)
                                    @if($i >= 1 && $i <= 10)
                                        <td class="celda-color semana{{ $i }}" data-semana="{{ $i }}">
                                            {{ $produccion->{"semana$i"} }}
                                            <select name="semana{{ $i }}[{{ $produccion->id }}]" id="semana{{ $i }}-{{ $produccion->id }}">
                                                @for ($j = 0; $j <= 7; $j++)
                                                    <option value="dato{{ $j }}" {{ $produccion->{"semana$i"} == "dato$j" ? 'selected' : '' }}>
                                                        {{ $j }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </td>
                                    @endif
                                @endfor
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div>
                    <br>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
                <br>
            </form>
    </div>
</div>
<style>
    table th, td {
      color: black;
    }
  </style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtiene todos los elementos con la clase "celda-color"
        var celdas = document.querySelectorAll('.celda-color');
        // Itera sobre los elementos y agrega un event listener
        celdas.forEach(function (celda) {
            var select = celda.querySelector('select');
            // Agrega un event listener para detectar el cambio en la selección
            select.addEventListener('change', function () {
                var selectedValue = select.value;
                // Define un objeto que mapea los valores a colores
                var colorMapping = {
                    'dato0': 'white',
                    'dato1': '#548235',
                    'dato2': '#00B050',
                    'dato3': '#FFFF00',
                    'dato4': '#FFC000',
                    'dato5': '#FF0000',
                    'dato6': '#FF9966',
                    'dato7': '#FFFFFF'
                };
                // Aplica el color al fondo de la celda
                celda.style.backgroundColor = colorMapping[selectedValue];
            });
            // Inicializa los colores basados en la selección actual al cargar la página
            var selectedValue = select.value;
            var colorMapping = {
                'dato0': 'white',
                'dato1': '#548235',
                'dato2': '#00B050',
                'dato3': '#FFFF00',
                'dato4': '#FFC000',
                'dato5': '#FF0000',
                'dato6': '#FF9966',
                'dato7': '#FFFFFF'
            };
            celda.style.backgroundColor = colorMapping[selectedValue];
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var sesionSemanaInicio = "{{ session('semana_inicio') }}";
    var sesionSemanaFin = "{{ session('semana_fin') }}";

    if (sesionSemanaInicio && sesionSemanaFin) {
        document.getElementById('semana_inicio').value = sesionSemanaInicio;
        document.getElementById('semana_fin').value = sesionSemanaFin;
        filtrarSemanas(); // Asume que esta función existe y filtra las semanas como se espera
    }
});

function filtrarSemanas() {
    var semanaInicio = parseInt(document.getElementById('semana_inicio').value);
    var semanaFin = parseInt(document.getElementById('semana_fin').value);

    if(semanaInicio > semanaFin) {
        alert('La semana de inicio debe ser menor o igual a la semana de fin');
        return;
    }

    for (var semana = 1; semana <= 52; semana++) {
        var columnasSemana = document.querySelectorAll('.semana' + semana);
        columnasSemana.forEach(function(columna) {
            columna.style.display = 'none';
        });
    }

    for (var semana = semanaInicio; semana <= semanaFin; semana++) {
        var columnasSemana = document.querySelectorAll('.semana' + semana);
        columnasSemana.forEach(function(columna) {
            columna.style.display = 'table-cell';
        });
    }
}

    </script>


@endsection

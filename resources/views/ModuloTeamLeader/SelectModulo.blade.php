@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-eFS5v37H8Y7IRWpT0deqLjMT2OVK4oAqB7MZx7kubE1L8d9Fnpp0xPnLAuwo8pg+2" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.7/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.4.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.js"></script>

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data del Supervisor {{ $User }} </h4>
                            <div>
                                <h6 class="card-title"></h6>
                                <div id="clock" class="text-right"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col center">
                                    <a href="{{ '/Modulo-TeamLeader'}}" class="btn btn-info">Regresar</a>
                                </div>
                            </div>
                            <br>
                            <br>

                            <form method="POST" action="{{ route('ModuloTeamL.guardarModulos', ['team_leader' => $User]) }}" id="dataForm">
                                @csrf
                                <div class="col-lg-12 col-md-12 text-right">
                                    <button type="submit" class="btn btn-info" name="actualizarData">Actualizar</button>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="miTabla" class="table">
                                        <thead class="text-info">
                                            <!--<th style="text-align: center">#</th>-->
                                            <th style="text-align: center">Modulo</th>
                                            <th style="text-align: center">Cliente</th>
                                            <th style="text-align: center">OP_REAL</th>
                                            <th style="text-align: center">OP_PRESENCIA</th>
                                            <th style="text-align: center">PxHRS</th>
                                            <th style="text-align: center">CALIBRACIONES</th>
                                            <th style="text-align: center">UTILITY</th>
                                            <th style="text-align: center">SAM</th>
                                            <th style="text-align: center">PIEZAS META</th>
                                            <th style="text-align: center">MINUTOS </br>META CUMPLIDA</th>

                                        </thead>
                                        <tbody>
                                            @foreach($teamModul as $modulo)
                                            @if( $modulo->piezas_meta <> 0)
                                                <tr>
                                                    <td style="display:none" >{{ $modulo->id }}</td>
                                                    <td style="text-align: center">{{ $modulo->Modulo }}
                                                        <input type="hidden" name="modulo[]" value="{{ $modulo->Modulo }}">
                                                        <input type="hidden" name="planta" value="{{ $modulo->planta }}">

                                                    </td>
                                                    <td><select class="form-select form-control" name="cliente[]">
                                                        @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->cliente }}" {{ $modulo->cliente == $cliente->cliente ? 'selected="selected"' : '' }}>{{ $cliente->cliente }}</option>
                                                        @endforeach
                                                        </select></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="op_real[]" value="{{ $modulo->op_real }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="op_presencia[]" value="{{ $modulo->op_presencia }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="pxhrs[]" value="{{ $modulo->pxhrs }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="capacitacion[]" value="{{ $modulo->capacitacion }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="utility[]" value="{{ $modulo->utility }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="sam[]" value="{{ $modulo->sam }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="piezas_meta[]" value="{{ $modulo->piezas_meta }}"></td>
                                                    <td><input style="text-align: center" class="form-control" type="text" name="meta_cumplida[]" value="{{ $modulo->meta_cumplida }}"></td>

                                                </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" style="text-align: left;">
                                                    <button type="button" class="button" id="insertarFila">
                                                        <span class="button__text">Add row</span>
                                                        <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg"><line y2="19" y1="5" x2="12" x1="12"></line><line y2="12" y1="12" x2="19" x1="5"></line></svg></span>
                                                      </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
 .button {
  position: relative;
  width: 150px;
  height: 40px;
  cursor: pointer;
  display: flex;
  align-items: center;
  border: 1px solid #34974d;
  background-color: #3aa856;
}

.button, .button__icon, .button__text {
  transition: all 0.3s;
}

.button .button__text {
  transform: translateX(30px);
  color: #fff;
  font-weight: 600;
}

.button .button__icon {
  position: absolute;
  transform: translateX(109px);
  height: 100%;
  width: 39px;
  background-color: #34974d;
  display: flex;
  align-items: center;
  justify-content: center;
}

.button .svg {
  width: 30px;
  stroke: #fff;
}

.button:hover {
  background: #34974d;
}

.button:hover .button__text {
  color: transparent;
}

.button:hover .button__icon {
  width: 148px;
  transform: translateX(0);
}

.button:active .button__icon {
  background-color: #2e8644;
}

.button:active {
  border: 1px solid #2e8644;
}


 .form-select option {
	appearance: none;
	-webkit-appearance: none;
	-moz-appearance: none;

}
.form-select {
    padding: 0.375rem 1.75rem 0.375rem 0.75rem; /* Ajusta el relleno según tus preferencias */
    font-size: 1rem; /* Tamaño de fuente */
    line-height: 1.5; /* Altura de línea */
    background-color:mix(blue, white, 25%); /* Color de fondo */
    border: 1px solid #ced4da; /* Borde */
    border-radius: 0.25rem; /* Radio de borde */
    color: white;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; /* Transiciones suaves */
}

/* Estilo para las opciones del campo de selección */
.form-select option {
    padding: 0.375rem 1.75rem; /* Ajusta el relleno según tus preferencias */
    font-size: 1rem; /* Tamaño de fuente */
    line-height: 1.5; /* Altura de línea */
    background-color: mix(blue, white, 25%); /* Color de fondo */
    color: #000; /* Color del texto */
}

/* Estilo para la opción seleccionada */
.form-select option[selected] {
   font-weight: bold; /* Texto en negrita para la opción seleccionada */
   color: #000;
}
#miTabla{
background: linear-gradient(90deg, #027989, #012a54); /* Degradado de negro a azul */
color: white; /* Texto blanco */
border: 5px;
border-radius: 20px;
}
#miTabla th, #miTabla td {
border: 10;
border-radius: 20px;

  }
  #miTabla input,option {
color: white; /* Ajusta el color del texto de los input según tus preferencias */
        }
</style>
    <script>
        $(document).ready(function () {
            var rowCount = {{ count($teamModul) }};
            $('#insertarFila').on('click', function () {
                var newRow = `
                    <tr>
                        <td style="text-align: center"></td>
                        <td style="text-align: center">
                        <select class="form-select form-control" name="modulos">
                        <option disabled selected >Selecciona un modulo</option>
                        @foreach ($modulos as $modulo)
                        <option style="text-align: center" value="{{ $modulo->Modulo }}">{{ $modulo->Modulo }}</option>
                        @endforeach
                        </select>
                        </td>
                        <td><input style="text-align: center" class="form-control" type="text" name="sam[]" value=""></td>
                        <td><input style="text-align: center" class="form-control" type="text" name="op_real[]" value=""></td>
                        <td><input style="text-align: center" class="form-control" type="text" name="op_presencia[]" value=""></td>
                        <td><input style="text-align: center" class="form-control" type="text" name="pxhrs[]" value=""></td>
                        <td><input style="text-align: center" class="form-control" type="text" name="capacitacion[]" value=""></td>
                        <td><input style="text-align: center" class="form-control" type="text" name="utility[]" value=""></td>
                        <td><input style="text-align: center" class="form-control" type="text" name="meta_cumplida[]" value=""></td>

                    </tr>
                `;
                $('#miTabla tbody').append(newRow);
            });
        });
    </script>
    <script>
        $(document).ready(function () {

            function updateClock() {
                var now = new Date();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();
                var day = now.getDate();
                var month = now.getMonth() + 1;
                var year = now.getFullYear();
                var formattedTime = hours + ':' + minutes + ':' + seconds;
                var formattedDate = day + '-' + (month < 10 ? '0' : '') + month + '-' + year;
                document.getElementById('clock').innerHTML = formattedTime + '</p>' + formattedDate;
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>

@endsection


@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('Produccion Metas')])


@section('content')
    <div class="card" style="height: auto; width: auto;">
        {{-- ... dentro de tu vista ... --}}
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(session('status')) {{-- A menudo utilizado para mensajes de estado genéricos --}}
            <div class="alert alert-secondary">
                {{ session('status') }}
            </div>
        @endif
        <div class="card-header">
            <h1>SUPERVISOR - MODULO</h1>

        </div>

        <br>
        <!-- Acordeón -->
    <div id="accordion">

        <!-- Tarjeta para Planta 1 -->
        <div class="card">
          <div class="card-header" id="headingOne" style="background: #4F3C20;">
            <h5 class="mb-0">
              <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: white; font-size: 16px; font-weight: bold;">
                Planta 1 - Ixtlahuaca
              </button>
            </h5>
          </div>

          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
              <!-- Contenido para Planta 1 -->

              <!-- Formulario para Planta 1 - Intimark1 -->
              <form action="{{ route('agregarTeamLeader') }}" method="post" class="form-inline">
                @csrf
                <div class="form-group mb-2">
                    <label for="nombre" class="sr-only">Nombre del Supervisor:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Supervisor" required>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="modulo" class="sr-only">Módulo:</label>
                    <input type="text" class="form-control" id="modulo" name="modulo" placeholder="Módulo" required>
                </div>
                <input type="hidden" name="planta" value="Intimark1">
                <button type="submit" class="btn btn-primary mb-2">Agregar Supervisor</button>
            </form>
            {{--Fin del formulario  --}}
            {{-- Campo de búsqueda --}}
            <div>
                <input type="text" id="searchInput" class="form-control mb-4" onkeyup="filterTable()" placeholder="Buscar por supervisor o módulo...">
            </div>
                <table class="table-custom table-datos" id="myTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supervisor</th>
                            <th>Módulo</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosProduccionIntimark1 as $datoIntimark1)
                            <tr>
                                <td>{{ $datoIntimark1->id }}</td>
                                <td class="align-left">{{ $datoIntimark1->nombre }}</td>
                                <td>{{ $datoIntimark1->modulo }}</td>
                                <td>
                                    @if ($datoIntimark1->estatus == 'A')
                                        ALTA
                                    @elseif ($datoIntimark1->estatus == 'B')
                                        BAJA
                                    @else
                                        {{ $datoIntimark1->estatus }}
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('ActualizarEstatusP1', $datoIntimark1->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if($datoIntimark1->estatus == 'A')
                                            <input type="hidden" name="estatus" value="B">
                                            <button class="btn-danger" type="submit">Dar de Baja</button>
                                        @else
                                            <input type="hidden" name="estatus" value="A">
                                            <button class="btn-secondary" type="submit">Dar de Alta</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
          </div>
        </div>

        <!-- Tarjeta para Planta 2 -->
        <div class="card">
          <div class="card-header" id="headingTwo" style="background: #4F3C20;">
            <h5 class="mb-0">
              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="color: white; font-size: 16px; font-weight: bold;">
                Planta 2 San Bartolo
              </button>
            </h5>
          </div>

          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              <!-- Contenido para Planta 2 -->

              <!-- Formulario para Planta 2 - Intimark2 -->
              <form action="{{ route('agregarTeamLeader') }}" method="post" class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="nombre" class="sr-only">Nombre del Supervisor:</label>
                        <input type="text" class="form-control" id="nombre2" name="nombre" placeholder="Nombre del Supervisor" required>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="modulo" class="sr-only">Módulo:</label>
                        <input type="text" class="form-control" id="modulo2" name="modulo" placeholder="Módulo" required>
                    </div>
                    <input type="hidden" name="planta" value="Intimark2">
                    <button type="submit" class="btn btn-primary mb-2">Agregar Supervisor</button>
                </form>
            {{-- Campo de búsqueda --}}
            <div>
                <input type="text" id="searchInput2" class="form-control mb-4" onkeyup="filterTable2()" placeholder="Buscar por Supervisor o módulo...">
            </div>
                <table class="table-custom table-datos" id="myTable2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supervisor</th>
                            <th>Módulo</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosProduccionIntimark2 as $datoIntimark2)
                            <tr>
                                <td>{{ $datoIntimark2->id }}</td>
                                <td class="align-left">{{ $datoIntimark2->nombre }}</td>
                                <td>{{ $datoIntimark2->modulo }}</td>
                                <td>
                                    @if ($datoIntimark2->estatus == 'A')
                                        ALTA
                                    @elseif ($datoIntimark2->estatus == 'B')
                                        BAJA
                                    @else
                                        {{ $datoIntimark2->estatus }}
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('ActualizarEstatusP2', $datoIntimark2->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if($datoIntimark2->estatus == 'A')
                                            <input type="hidden" name="estatus" value="B">
                                            <button class="btn-danger" type="submit">Dar de Baja</button>
                                        @else
                                            <input type="hidden" name="estatus" value="A">
                                            <button class="btn-secondary" type="submit">Dar de Alta</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
          </div>
        </div>

      </div>
      <!-- Fin Acordeón -->
        <br>
    </div>
    <style>
        /* Estilos generales para la tabla */
        .align-left {
            text-align: left;
        }
        .table-datos {
            color: black;
        }

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
        .orange { background-color: #C65911; }
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
        #searchInput, #searchInput2 {
        width: 100%; /* O un ancho específico, según tu diseño */
        padding: 10px 50px 10px;
        margin: 10px 0;
        border: 1px solid #ddd; /* Color de borde suave */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra suave */
        border-radius: 4px; /* Bordes redondeados */
        outline: none; /* Remueve el contorno al enfocar */
        transition: all 0.3s ease-in-out; /* Transición suave */
    }

    #searchInput:focus, #searchInput2:focus {
        border-color: #0056b3; /* Cambia el color del borde al enfocar */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada al enfocar */
    }

    #searchInput::placeholder, #searchInput2::placeholder {
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
        padding: 15px 35px;
        border-radius: 10px;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    input[type="checkbox"] {
        /* Ajusta la escala al tamaño que desees */
        transform: scale(1.5);
        /* Asegúrate de centrar el checkbox después de escalarlo */
        margin: 0;
        /* Puedes necesitar ajustar la alineación vertical si se desplaza */
        vertical-align: middle;
    }


    </style>

<script>
function uncheckOthers(checkbox) {
    // Encuentra el elemento tr (fila) más cercano
    var row = checkbox.closest('tr');

    // Encuentra todas las celdas de la fila y elimina cualquier estilo de resaltado
    var cells = row.getElementsByTagName('td');
    for (var i = 2; i < cells.length; i++) { // Comienza desde el índice 2 para omitir las dos primeras celdas
        cells[i].classList.remove('green', 'light-green', 'yellow', 'orange', 'red', 'peach', 'grey');
    }

    // Encuentra todos los checkboxes en la misma fila
    var checkboxes = row.querySelectorAll('input[type="checkbox"]');

    // Deselecciona todos los checkboxes
    checkboxes.forEach(function(cb) {
        cb.checked = false;
        var cell = cb.parentElement;
        cell.classList.remove('green', 'light-green', 'yellow', 'orange', 'red', 'peach', 'grey');
    });

    // Selecciona el checkbox y aplica la clase de color correspondiente
    checkbox.checked = true;
    var cellIndex = checkbox.parentElement.cellIndex - 2; // Ajusta el índice para las dos primeras celdas
    var headerCells = document.querySelectorAll('#header-row th');
    if (cellIndex >= 0 && cellIndex < headerCells.length) {
        var headerClass = headerCells[cellIndex].classList[0]; // Obtiene la primera clase del encabezado
        checkbox.parentElement.classList.add(headerClass);
    }
}

</script>
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
            var tdLeader = tr[i].getElementsByTagName("td")[1];
            var tdModule = tr[i].getElementsByTagName("td")[2];
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
    function filterTable2() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput2");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable2"); // Asegúrate de poner el id correcto de tu tabla
        tr = table.getElementsByTagName("tr");

        // Recorre todas las filas de la tabla y oculta las que no coinciden con la búsqueda
        for (i = 1; i < tr.length; i++) { // Comienza en 1 para saltar el encabezado de la tabla
            // Obtén las celdas de "Team Leaders" y "Modulo"
            var tdLeader = tr[i].getElementsByTagName("td")[1];
            var tdModule = tr[i].getElementsByTagName("td")[2];
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
        document.getElementById('nombre').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
        document.getElementById('modulo').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
        });
        document.getElementById('nombre2').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        document.getElementById('modulo2').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>

@endsection

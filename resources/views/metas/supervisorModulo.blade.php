@extends('layouts.app', ['activePage' => 'supervisorModulo', 'titlePage' => __('Supervisor Modulo')])

@section('content')
    <div class="card" style="height: auto; width: auto;">
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

        @if(session('status'))
            <div class="alert alert-secondary">
                {{ session('status') }}
            </div>
        @endif

        <div class="card-header">
            <h1>SUPERVISOR - MODULO</h1>
        </div>

        <br>

        <div class="row">
            <!-- Columna para Planta 1 -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: #4F3C20;">
                        <h5 style="color: white; font-size: 16px; font-weight: bold;">
                            Planta 1 - Ixtlahuaca
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('agregarSupervisor') }}" method="post" class="form-inline">
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

                        <div>
                            <input type="text" id="searchInput" class="form-control mb-4" onkeyup="filterTable('myTable', 'searchInput')" placeholder="Buscar por supervisor o módulo...">
                        </div>

                        <div class="table-responsive">
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
                                    @foreach ($supervisoresPlanta1 as $supervisor)
                                        <tr>
                                            <td>{{ $supervisor->id }}</td>
                                            <td class="align-left">{{ $supervisor->nombre }}</td>
                                            <td>{{ $supervisor->modulo }}</td>
                                            <td>
                                                @if ($supervisor->estatus == 'A')
                                                    ALTA
                                                @elseif ($supervisor->estatus == 'B')
                                                    BAJA
                                                @else
                                                    {{ $supervisor->estatus }}
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('ActualizarEstatusSupervisor', $supervisor->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($supervisor->estatus == 'A')
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

            <!-- Columna para Planta 2 -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: #4F3C20;">
                        <h5 style="color: white; font-size: 16px; font-weight: bold;">
                            Planta 2 San Bartolo
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('agregarSupervisor') }}" method="post" class="form-inline">
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

                        <div>
                            <input type="text" id="searchInput2" class="form-control mb-4" onkeyup="filterTable('myTable2', 'searchInput2')" placeholder="Buscar por supervisor o módulo...">
                        </div>

                        <div class="table-responsive">
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
                                    @foreach ($supervisoresPlanta2 as $supervisor)
                                        <tr>
                                            <td>{{ $supervisor->id }}</td>
                                            <td class="align-left">{{ $supervisor->nombre }}</td>
                                            <td>{{ $supervisor->modulo }}</td>
                                            <td>
                                                @if ($supervisor->estatus == 'A')
                                                    ALTA
                                                @elseif ($supervisor->estatus == 'B')
                                                    BAJA
                                                @else
                                                    {{ $supervisor->estatus }}
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('ActualizarEstatusSupervisor', $supervisor->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($supervisor->estatus == 'A')
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
        </div>
    </div>

    <style>
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
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: solid 1px #ddd;
        }
        th {
            background-color: #bbcdce;
            color: #333;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        #searchInput, #searchInput2 {
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
    </style>

    <script>
        function filterTable(tableId, searchInputId) {
            var input, filter, table, tr, td, i;
            input = document.getElementById(searchInputId);
            filter = input.value.toUpperCase();
            table = document.getElementById(tableId);
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
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

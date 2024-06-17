@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('avanceproduccion')])

@section('content')

    <div class="content">
        <div class="container-fluid">
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
            {{-- ... el resto de tu vista ... --}}
            <div class="card card-stats">
                <div class="card-header card-header-tabs card-header-info">
                    <div class="nav-tabs-navigation">
                      <h2> Registro Día Anterior </h2>
                    </div>
                  </div>
                <br>
                <div style="display: flex; flex-wrap: wrap;">

                    {{-- Columna para Team Leaders --}}
                    <div >
                        <h2>Registro dia : {{ $dia}}</h2>
                        {{-- Formulario para agregar nuevo Team Leader --}}
                        <form action="{{ route('actualiza_cifras') }}" method="POST" class="form-custom">
                            @csrf
                            <div class="card-footer" style="align:center">
                                <p class="stats"><span class="text-success"> Intimark I :</span><b> </b></p>
                                <input type="text" name="producidasI" placeholder="Piezas Producidas">
                            </div>
                            <div class="card-footer" style="align:center">
                                <p class="stats"><span class="text-success"> Intimark II :</span><b> </b></p>
                                <input type="text" name="producidasII" placeholder="Piezas Producidas">
                            </div>
                            <button type="submit">Agregar</button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>

    </script>
    <style>
        /* Estilos para las tablas */
        .table-custom {
            width: 80%;
            border-collapse: collapse;
        }

        .table-custom th, .table-custom td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .table-custom th {
            background-color: #458ea0;
            color: white;
        }

        .table-custom tr:nth-child(even){background-color: #f2f2f2;}

        .table-custom tr:hover {background-color: #ddd;}

        /* Estilos para los formularios */
        .form-custom {
            margin-bottom: 20px;
        }

        .form-custom input[type="text"] {
            width: 70%;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-custom button {
            padding: 10px 20px;
            background-color: #458ea0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-custom button:hover {
            background-color: #458ea0;
        }

        /* Estilos específicos para la tabla de Team Leaders */
        .table-leaders th {
            background-color: #458ea0;
            color: white;
        }

        .table-leaders button {
            background-color: #458ea0;
            /* otros estilos para el botón si es necesario */
        }

        .table-leaders button:hover {
            background-color: #306872; /* Un tono más oscuro para el hover */
        }

        /* Estilos específicos para la tabla de Módulos */
        .table-modulos th {
            background-color: #572c69; /* Un gris oscuro para diferenciar */
            color: white;
        }

        .table-modulos button {
            background-color: #572c69;
            /* otros estilos para el botón si es necesario */
        }

        .table-modulos button:hover {
            background-color: #5a6268; /* Un tono más oscuro para el hover */
        }

        /* Estilos para los formularios se mantienen igual */
        .form-custom {
            /* ... */
        }

        /* Ajustes adicionales para la responsividad */
        @media (max-width: 600px) {
            .form-custom input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }
        }

    </style>
@endsection


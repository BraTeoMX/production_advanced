@extends('layouts.app')

<!-- En el head de tu documento -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-eFS5v37H8Y7IRWpT0deqLjMT2OVK4oAqB7MZx7kubE1L8d9Fnpp0xPnLAuwo8pg+2" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.7/css/jquery.dataTables.css">

<!-- Antes de cerrar el body de tu documento -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.js"></script>

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Relación Supervisor - Módulo</h4>
                            <div>
                                <h6 class="card-title"></h6>
                                <div id="clock" class="text-right"></div>
                                <input type="hidden" name="planta" id="planta" value="{{ $planta }}">
                            </div>
                        </div>
                        <br>
                        <div class="col-md-2 ml-auto">
                            <button type="button" class="button" id="addcard" data-toggle="modal" data-target="#myModal">
                                <span class="button__text">Añadir</span>
                                <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"
                                        stroke="currentColor" height="24" fill="none" class="svg">
                                        <line y2="19" y1="5" x2="12" x1="12"></line>
                                        <line y2="12" y1="12" x2="19" x1="5"></line>
                                    </svg></span>
                            </button>
                        </div>
                        <!-- Dentro de tu contenido Blade -->
                        <div class="modal" tabindex="-1" role="dialog" id="myModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header card-header-info">
                                        <h5 class="modal-title">Relación Supervisor con Modulo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" style="width: 100%;">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th style="text-align: center; width: 70%;">Supervisor</th>
                                                        <th style="text-align: center; width: 100%;">Modulo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            <label for="LeaderSelect">Seleccion de Supervisor:</label>
                                                            <select class="form-control" id="LeaderSelect" name="LeaderSelect" required>
                                                                @foreach($teamLeaders as $teamLeader)
                                                                    <option value="{{ $teamLeader->team_leader }}">{{ $teamLeader->team_leader }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <label for="ModuloSelect">Seleccion de Modulo:</label>
                                                            <select class="form-control" id="ModuloSelect" name="ModuloSelect" required>
                                                                @foreach($modulos as $modulo)
                                                                    <option value="{{ $modulo->Modulo }}">{{ $modulo->Modulo }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                        <button class="btn btn-primary" id="guardarBtn" style="width: 100%;">Guardar</button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <!-- Puedes agregar más botones según tus necesidades -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            @foreach ($teamModulsGrouped as $teamLeader)
                                <div class="team-leader-card team-card-container"
                                    style="display: inline-block; margin: 40; perspective: 1000px;">
                                    <div class="card front team-card card-header-info d-flex justify-content-between align-items-center"
                                        style="width: 15rem; position: relative; z-index: 3; transform-style: preserve-4d; transition: transform 1s;"
                                        data-team-leader-id="{{ $teamLeader[0]->team_leader_id }}">
                                        <div
                                            class="card-header card-header-primary d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">Supervisor: {{ $teamLeader[0]->team_leader }}</h5>
                                        </div>
                                    </div>
                                    <div class="card back modules-card" style="display: none;">
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
                                                <th style="text-align: center">MINUTOS</br> META CUMPLIDA</th>

                                            </thead>
                                            <tbody>
                                                @foreach ($teamLeader as $modulo)
                                                    <tr>
                                                        <td id="ident" style="display:none">
                                                            {{ $modulo->id }}</td>
                                                        <td id="modul" style="text-align: center">
                                                            {{ $modulo->Modulo }}</td>
                                                        <td id="modul" style="text-align: center">
                                                            {{ $modulo->cliente }}</td>
                                                        <td id="op_real" style="text-align: center">
                                                            {{ $modulo->op_real }}</td>
                                                        <td id="op_presencia" style="text-align: center">
                                                            {{ $modulo->op_presencia }}</td>
                                                        <td id="pxhrs" style="text-align: center">
                                                            {{ $modulo->pxhrs }}</td>
                                                        <td id="capacitacion" style="text-align: center">
                                                            {{ $modulo->capacitacion }}</td>
                                                        <td id="utility" style="text-align: center">
                                                            {{ $modulo->utility }}</td>
                                                        <td id="sam" style="text-align: center">
                                                            {{ $modulo->sam }}</td>
                                                        <td id="piezas_meta" style="text-align: center">
                                                            {{ $modulo->piezas_meta }}</td>
                                                        <td id="meta_cumplida" style="text-align: center">
                                                            {{ $modulo->meta_cumplida }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <a
                                            href="{{ route('ModuloTeamL.SelectModulo', ['team_leader' => $teamLeader[0]->team_leader]) }}">
                                            <button class="edit-icon btn btn-success" id="updateData">Editar</button>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
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

        .button,
        .button__icon,
        .button__text {
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
    </style>
    <style>
        .edit-icon {
            background: linear-gradient(90deg, #00bcd4, #000);
            color: #fff;
            padding: 10px 15px;
            border: 10;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            right: 10px;

        }

        .edit-icon:hover {
            background: linear-gradient(90deg, #000, #5f00d4);
        }

        .card-title {
            text-align: center;
            color: #fff;
        }

        .modules-card {
            background: linear-gradient(90deg, #000, #027685);
            color: #fff;
            position: relative;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 10px 10px 50px #39dbf0;
        }

        .table {
            display: inline-block;
            color: #fff;
            width: 28cm;
            height: auto;
            border-radius: 10px;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.team-card-container').click(function(event) {
                event.stopPropagation();
                var currentCard = $(this);
                $('.team-card-container').not(currentCard).hide();
                currentCard.find('.front').css('transform', 'rotateY(360deg)');
                currentCard.find('.back').css('transform', 'rotateY(360deg)');
                currentCard.addClass('flipped');
                currentCard.find('.back').fadeIn();
            });

            $(document).click(function() {
                $('.team-card-container .back').fadeOut();
                $('.team-card-container').show();
                $('.team-card-container').removeClass('flipped');
            });

            $('#updateData').click(function(event) {
                event.stopPropagation();
                var currentCard = $(this).closest('.team-card-container');
                $('.team-card-container').not(currentCard).hide();
                currentCard.show();
            });
        });
    </script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#addcard', function() {
            $('#myModal').modal('show');
        });
    });
</script>

    <script>
        $(document).ready(function() {
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
    <script>
        $(document).ready(function () {
            $('#guardarBtn').click(function () {
                var teamLeaderId = $('#LeaderSelect').val();
                var moduloId = $('#ModuloSelect').val();
                var plantaId = $('#planta').val();

                // Realiza una solicitud AJAX para guardar los datos
                $.ajax({
                    url: '/guardarRelacion', // Cambia la ruta según tus necesidades
                    type: 'POST',
                    data: {
                        'teamLeaderId': teamLeaderId,
                        'moduloId': moduloId,
                        'plantaId': plantaId,
                        '_token': '{{ csrf_token() }}' // Agrega el token CSRF
                    },
                    success: function (response) {
                        // Puedes realizar acciones adicionales después de guardar, si es necesario
                        console.log('Datos guardados correctamente');
                    },
                    error: function (error) {
                        console.error('Error al guardar datos:', error);
                    }
                });
            });
        });
    </script>

@endsection

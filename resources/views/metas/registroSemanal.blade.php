@extends('layouts.app', ['activePage' => 'registroSemanal', 'titlePage' => __('Registro Semanal')])

@section('content')
    <div class="card" style="height: auto; width: auto;">
        <div class="card-header encabezado">
            <h2 class="card-title"> Registro Semana {{ $current_week }} </h2>
            <h2 class="card-title"> {{ $current_month.'  ' }}{{ $currentYear }}</h2>
        </div>

        <br>

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
                        <div>
                            <input type="text" id="searchInput" class="form-control mb-4" onkeyup="filterTable('myTable', 'searchInput')" placeholder="Buscar por supervisor o módulo...">
                        </div>

                        <div class="table-responsive">
                            <table class="table-custom table-datos" id="myTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center; width:400px">Supervisor</th>
                                        <th rowspan="2" style="text-align: center; width:400px">Módulo</th>
                                        <th colspan="7" style="text-align: center;">SEMANA {{ $current_week }}</th>
                                        <th rowspan="2" style="text-align: center;">TE</th>
                                    </tr>
                                    <tr id="header-row">
                                        <th class="green">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="light-green">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="yellow">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="orange">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="red">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="peach">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="grey">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supervisoresPlanta1 as $supervisor)
                                        <tr>
                                            <td style="text-align: left">{{ $supervisor->nombre }}</td>
                                            <td>{{ $supervisor->modulo }}</td>
                                            @for($i = 1; $i <= 7; $i++)
                                                @php
                                                    $isChecked = $supervisor->{'semana' . $current_week} == $i;
                                                    $colorClass = $isChecked ? 'class-name-for-color-' . $i : '';
                                                @endphp
                                                <td class="centered-content {{ $colorClass }}">
                                                    <input type="checkbox" id="checkbox-{{ $supervisor->id }}-{{ $i }}"
                                                        name="semanas[{{ $supervisor->id }}][semana{{ $current_week }}]"
                                                        value="{{ $i }}"
                                                        onclick="uncheckOthers(this, {{ $i }}, '#extra-checkbox-{{ $supervisor->id }}')"
                                                        {{ $isChecked ? 'checked' : '' }} >
                                                </td>
                                            @endfor
                                            <td class="centered-content oculto">
                                                @php
                                                    $extraCheckboxName = 'extra' . ($current_week);
                                                    $isExtraChecked = $supervisor->$extraCheckboxName == 1;
                                                @endphp
                                                <input type="checkbox" id="extra-checkbox-{{ $supervisor->id }}"
                                                    name="semanas[{{ $supervisor->id }}][{{ $extraCheckboxName }}]"
                                                    value="1"
                                                    {{ $isExtraChecked ? 'checked' : '' }} >
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                        <div>
                            <input type="text" id="searchInput2" class="form-control mb-4" onkeyup="filterTable('myTable2', 'searchInput2')" placeholder="Buscar por supervisor o módulo...">
                        </div>

                        <div class="table-responsive">
                            <table class="table-custom table-datos" id="myTable2">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center; width:400px">Supervisor</th>
                                        <th rowspan="2" style="text-align: center; width:400px">Módulo</th>
                                        <th colspan="7" style="text-align: center;">SEMANA {{ $current_week }}</th>
                                        <th rowspan="2" style="text-align: center;">TE</th>
                                    </tr>
                                    <tr id="header-row">
                                        <th class="green">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="light-green">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="yellow">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="orange">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="red">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="peach">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        <th class="grey">&nbsp; &nbsp; &nbsp; &nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supervisoresPlanta2 as $supervisor)
                                        <tr>
                                            <td style="text-align: left">{{ $supervisor->nombre }}</td>
                                            <td>{{ $supervisor->modulo }}</td>
                                            @for($i = 1; $i <= 7; $i++)
                                                @php
                                                    $isChecked = $supervisor->{'semana' . $current_week} == $i;
                                                    $colorClass = $isChecked ? 'class-name-for-color-' . $i : '';
                                                @endphp
                                                <td class="centered-content {{ $colorClass }}">
                                                    <input type="checkbox" id="checkbox-{{ $supervisor->id }}-{{ $i }}"
                                                        name="semanas[{{ $supervisor->id }}][semana{{ $current_week }}]"
                                                        value="{{ $i }}"
                                                        onclick="uncheckOthers(this, {{ $i }}, '#extra-checkbox-{{ $supervisor->id }}')"
                                                        {{ $isChecked ? 'checked' : '' }} >
                                                </td>
                                            @endfor
                                            <td class="centered-content oculto">
                                                @php
                                                    $extraCheckboxName = 'extra' . ($current_week);
                                                    $isExtraChecked = $supervisor->$extraCheckboxName == 1;
                                                @endphp
                                                <input type="checkbox" id="extra-checkbox-{{ $supervisor->id }}"
                                                    name="semanas[{{ $supervisor->id }}][{{ $extraCheckboxName }}]"
                                                    value="1"
                                                    {{ $isExtraChecked ? 'checked' : '' }} >
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
        .green { background-color: #00B0F0; }
        .light-green { background-color: #00B050; }
        .yellow { background-color: #FFFF00; }
        .orange { background-color: #C65911; }
        .red { background-color: #FF0000; }
        .peach { background-color: #A6A6A6; }
        .grey { background-color: #F9F9EB; }
        .centered-content {
            text-align: center;
            vertical-align: middle;
        }
        .oculto {
            display: none;
        }
        .encabezado {
            display: flex;
            justify-content: space-between;
        }
        .card-title {
            font-weight: bold;
        }
    </style>

    <script>
        function uncheckOthers(checkbox, weekNumber, secondCheckboxSelector) {
            var row = checkbox.closest('tr');
            var cells = row.getElementsByTagName('td');
            var currentCheckedState = checkbox.checked;

            for (var i = 2; i < cells.length; i++) {
                cells[i].classList.remove('green', 'light-green', 'yellow', 'orange', 'red', 'peach', 'grey');
            }

            var checkboxes = row.querySelectorAll('input[type="checkbox"]');

            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });

            checkbox.checked = currentCheckedState;

            if (checkbox.checked) {
                var cellIndex = checkbox.parentElement.cellIndex - 2;
                var headerCells = document.querySelectorAll('#header-row th');
                if (cellIndex >= 0 && cellIndex < headerCells.length) {
                    var headerClass = headerCells[cellIndex].classList[0];
                    checkbox.parentElement.classList.add(headerClass);
                }
            }

            var secondCheckbox = document.querySelector(secondCheckboxSelector);
            if (weekNumber >= 1 && weekNumber <= 3) {
                secondCheckbox.parentElement.classList.remove('oculto');
            } else {
                secondCheckbox.parentElement.classList.add('oculto');
            }
        }

        function filterTable(tableId, searchInputId) {
            var input, filter, table, tr, td, i;
            input = document.getElementById(searchInputId);
            filter = input.value.toUpperCase();
            table = document.getElementById(tableId);
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

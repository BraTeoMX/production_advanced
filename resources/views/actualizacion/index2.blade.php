@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-eFS5v37H8Y7IRWpT0deqLjMT2OVK4oAqB7MZx7kubE1L8d9Fnpp0xPnLAuwo8pg+2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
              <h4 class="card-title">Avances de la Producción (Actualización)</h4>
              <div>
               <!-- <h6 class="card-title">Reloj</h6>-->
                <div id="clock" class="text-right"></div>
              </div>
            </div>
            <div class="card-body table-responsive">
                <form method="POST" action="{{ route('actualizacion.actualizarDatos') }}" id="dataForm">
                @csrf
                <table id="miTabla" class="table table-hover">
                  <thead class="text-info">
                  <!--  <th style="text-align: center">#</th>-->
                    <th style="text-align: left">Team Leader</th>
                    <th style="text-align: center">Modulo</th>
                    <th style="text-align: center">Piezas</th>
                  <!--  <th style="text-align: center">Efic(%)</th>
                    <th style="text-align: center">Minutos <br>(Producidos)</th>
                    <th style="text-align: center">Proyección<br>(Minutos)</th>-->
                  </thead>
                  <tbody>
                    @foreach($teamModul->sortBy('team_leader') as $teamModu)
                    <div class="row">
                  <div class="col-md-6"></div>
                  <div class="col-md-6 text-right">
                      <tr>
                      
                        <td style="text-align: center">{{ $teamModu->team_leader }}</td>
                        <td style="text-align: center">{{ $teamModu->modulo }}</td>
                        <td style="text-align: center">
                          <input type="number" class="form-control"  name="piezas" data-id="{{ $teamModu->id }}" placeholder="Ingrese piezas">
                        </td>
                        <td style="text-align: center">

                        </td>
                        <td style="text-align: center">

                        </td>
                        <td style="text-align: center">

                        </td>
                      </tr>
                    </div>
                    </div>
                    </div>
                    @endforeach
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-md-6"></div>
                  <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success" id="updateData">
                        <i class="fas fa-check"></i> Actualizar datos
                     </button>
                  </div>
                </div>
              </form>
              <div class="row">
                <div class="col center">
                  <a href="{{ url()->previous() }}" class="btn btn-primary">Regresar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready( function () {
      $('#miTabla').DataTable();
    });
  </script>
  <script>
    $(document).ready(function () {
      function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var day = now.getDate();
        var month = now.getMonth() + 1;
        var year = now.getFullYear();
        var formattedTime = hours + ':' + '00' + ':'  +  '00' ;
        var formattedDate = day + '-' + (month < 10 ? '0' : '') + month + '-' + year;
        document.getElementById('clock').innerHTML = formattedTime +'</p>'+ formattedDate;
      }
      setInterval(updateClock, 1000);
      updateClock();

      // Botón de actualización
      $("#updateData").on("click", function () {
        var formData = [];

        // Recorre los inputs y agrega los datos al array formData
        $("input[name='piezas']").each(function () {
          var id = $(this).data("id");
          var name = $(this).attr("name");
          var value = $(this).val();

          if (value !== "") {
            formData.push({
              id: id,
              name: name,
              value: value
            });
          }
        });

        // Agrega los datos al formulario como un campo oculto en formato JSON
        $("#dataForm").append('<input type="hidden" name="formData" value=\'' + JSON.stringify(formData) + '\'>');

        // Envía el formulario
        $("#dataForm").submit();
      });
    });
  </script>
@endsection

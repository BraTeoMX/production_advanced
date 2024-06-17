@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('Actualizacion')])

@section('content')
  <div class="content">



     <div class="row">

        <div class="col-lg-12 col-md-12">
          <div class="card">
          <div class="card-header card-header-info d-flex justify-content-between align-items-center">
              <h4 class="card-title">Avances de la Producción (Actualización)</h4>
              <div>
              <p class="card-category"></br>
              <h3 class="card-title"><b><font size=6+>{{ $hoy.'  ' }}</br>{{'  '.$hora.' hrs.' }}</font></b>
                <small></small>
              </h3>

              </div>
            </div>
            <div class="card-body table-responsive">
            <form method="POST" action="{{ route('actualizacion.actualizarDatos') }}" id="dataForm">
                @csrf
                <table id="miTabla" class="table table-hover">
                <thead class="text-info">
                 <th  style="visibility:collapse; display:none;" >#</th>
                  <th style="width:300px">Supervisor</th>
                  <th style="text-align: center; width:300px">Modulo</th>
                  <th style="text-align: center; width:300px">Piezas</th>

                </thead>
                <tbody>
                     <div class="col-md-12 text-center">

                        <div class="row">
                            @foreach($teamModul as $teamModu)
                                <tr>
                                    <td id="ident" style="visibility:collapse; display:none;">{{ $teamModu->id }}    </td>
                                    <td style="text-align: left" style="width:15px">{{ $teamModu->team_leader }}</td>
                                    <td style="text-align: center">{{ $teamModu->Modulo }}</td>
                                    <td style="text-align: center">
                                        <input type="number" class="form-control"  name="piezas"  data-id="{{ $teamModu->id }}" placeholder="No. piezas" required>
                                    </td>

                                </tr>
                         @endforeach

                        </div>
                    </div>


                </tbody>
              </table>
              <div class="row">

                    <div class="col-md-6 text-left">
                        <button type="button" class="btn btn-success" id="updateData">
                            <i class="fa fa-check"></i> Actualizar datos
                        </button>
                    </div>
                    <div class="col-md-6 text-center"><a href="{{ url()->previous() }}" class="btn btn-primary">Limpiar</a>
                    </div>
              </div>
              </form>
            </div>
          </div>
        </div>
        </div>

      </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-eFS5v37H8Y7IRWpT0deqLjMT2OVK4oAqB7MZx7kubE1L8d9Fnpp0xPnLAuwo8pg+2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.7/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.4.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.js"></script>

<script>
    $(document).ready( function () {
      $('#miTabla').DataTable();
    });
  </script>
  <script>
    $(document).ready(function () {
           // Botón de actualización
      var aux = 1;
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

           }else{
            aux = 0;

            }

        });
        if (aux == 0){
          Swal.fire('Ingresar la información del No. de Piezas de todos los modulos, si no existieran colocar 0');
          aux = 1;
          //Actualizamos la página
         // location.reload();
        }else{
        // Agrega los datos al formulario como un campo oculto en formato JSON
        $("#dataForm").append('<input type="hidden" name="formData" value=\'' + JSON.stringify(formData) + '\'>');
                Swal.fire('Informacion actualizada con exito');
                // Envía el formulario
                $("#dataForm").submit();

        }

      });
    });
  </script>
@endpush

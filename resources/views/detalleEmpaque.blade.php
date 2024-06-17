@extends('layouts.app', ['activePage' => 'avanceproduccion', 'titlePage' => __('avanceproduccion')])

@section('content')
  <div class="content">

    <!--<div class="container-fluid">
    <div class="card">
            <div class="card-header card-header-tabs card-header-primary">
              <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                  <span class="nav-tabs-title">ViS</span>
    </div></div></div></div></div>
-->

      <div class="row">

        <div class="col-lg-12 col-md-12">
          <div class="card">
            <div class="card-header card-header-info">
              <h4 class="card-title">Reporte por Planta Empaque </h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-hover">
                <thead class="text-info">
                  <th  style="text-align: center;  width:200px">Planta</th>
                  <th  style="text-align: center;  width:200px">Eficiencia Meta</th>
                  <th  style="text-align: center;  width:200px">Eficiencia Real</th>
                  <th  style="text-align: center;  width:200px">Minutos Producidos Meta</th>
                  <th  style="text-align: center;  width:200px">Minutos Producidos Real</th>
                  <th  style="text-align: center;  width:200px">Diferencia</th>
                </thead>
                <tbody>
                    <tr>
                        <td  style="text-align: center;  width:200px">IntimarkI</td>
                        <td  style="text-align: center;  width:200px"> {{  number_format($efic_dia_830A*100,0).' %'  }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format($eficiencia_EmpaqueI,0).' %' }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format($cantidad_planta_EmpaqueI,0) }}</td>
                        <td  style="text-align: center;  width:200px">{{number_format($real_EmpaqueI,0) }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format($real_EmpaqueI-$cantidad_planta_EmpaqueI,0) }}</font></b></p>
            </div>  </td>
                    </tr>
                    <tr>
                        <td  style="text-align: center;  width:200px">IntimarkII</td>
                        <td  style="text-align: center;  width:200px">{{  number_format($efic_dia_831A*100,0).' %'  }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format($eficiencia_EmpaqueII,0).' %' }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format($cantidad_planta_EmpaqueII,0) }}</td>
                        <td  style="text-align: center;  width:200px">{{number_format($real_EmpaqueII,0) }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format($real_EmpaqueII-$cantidad_planta_EmpaqueII,0) }}</font></b></p>

                    </tr>
                    <tr>
                        <td  style="text-align: center;  width:200px"><b>TOTAL</b></td>
                        <td  style="text-align: center;  width:200px"></td>
                        <td  style="text-align: center;  width:200px"></td>
                        <td  style="text-align: center;  width:200px">{{ number_format($cantidad_planta_EmpaqueI+$cantidad_planta_EmpaqueII,0) }}</td>
                        <td  style="text-align: center;  width:200px">{{number_format($real_EmpaqueI+$real_EmpaqueII,0) }}</td>
                        <td  style="text-align: center;  width:200px">{{ number_format(($real_EmpaqueI-$cantidad_planta_EmpaqueI)+($real_EmpaqueII-$cantidad_planta_EmpaqueII),0) }}</font></b></p>

                    </tr>

                </tbody>
              </table>
              <div class="row">
              <div class="col center"><a href="{{ route('home') }}"  class="btn btn-primary">Regresar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
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
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

<script>

  </script>
@endpush

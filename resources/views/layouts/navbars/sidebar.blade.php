<div class="sidebar" data-color="brown" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="#" class="simple-text logo-normal"> <img class="navbar-brand-logo-mini" src="{!! asset('/material/img/logo.png') !!}" alt="Logo" width='80%'>
    </a>
  </div>
  <div class="sidebar-wrapper" >
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'avanceproduccion' ? ' active' : '' }}"  >
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons" >avanceproduccion</i>
            <p >{{ __('Avance Producción') }}</p>
        </a>
      </li>
      @if (auth()->user()->email!='alara@intimark.com.mx'   )

      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample2" aria-expanded="true">
          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>
          <p>{{ __('Planeación Planta I') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse hide" id="laravelExample2">
          <ul class="nav">
          <!--  <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('actualizacion.index') }}">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal">{{ __('Registro x Hora') }} </span>
              </a>
            </li>-->
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('ModuloTeamL.ModulTeam') }}">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal"> {{ __('Proyección') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      @endif
      @if (auth()->user()->email!='alara@intimark.com.mx'   )

      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample21" aria-expanded="true">
          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>
          <p>{{ __('Planeación Planta II') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse hide" id="laravelExample21">
          <ul class="nav">
           <!-- <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('actualizacion.indexII') }}">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal">{{ __('Registro x Hora ') }} </span>
              </a>
            </li>-->
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('ModuloTeamL.ModulTeamII') }}">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal"> {{ __('Proyección') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      @endif
      @if (auth()->user()->email!='alara@intimark.com.mx'   )

      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample4" aria-expanded="true">
          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>
          <p>{{ __('Catálogos') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse hide" id="laravelExample4">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('ModuloTeamL.altasybajasTLyM') }}">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal">{{ __('Supervisor - Modulos') }} </span>
              </a>
            </li>
         <!--   <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('dia_anterior') }}">
                  <span class="sidebar-mini">  </span>
                  <span class="sidebar-normal"> {{ __('Registro día anterior') }} </span>
                </a>
              </li> -->
          </ul>
        </div>
      </li>
      @endif
      @if (auth()->user()->email=='bteofilo@intimark.com.mx' || auth()->user()->email=='gvergara@intimark.com.mx' || auth()->user()->email=='rbarrera@intimark.com.mx'  || auth()->user()->email=='ereyes@intimark.com.mx' || auth()->user()->email=='fhinojosa@intimark.com.mx'  || auth()->user()->email=='azago@intimark.com.mx' || auth()->user()->email=='madissi@intimark.com.mx' || auth()->user()->email=='rlopez@intimark.com.mx'   )
      <!-- End Pages -->
      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample3" aria-expanded="true">
          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>
          <p>{{ __('Producción Metas') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse hide" id="laravelExample3">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
            <a class="nav-link" href="{!! url('SemanaActual') !!}" title="Captura semana actual"  data-placement="left">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal">{{ __('Registro Semanal') }} </span>
              </a>
            </li>

           <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
           <a class="nav-link" href="{!! url('/ReporteGeneral') !!}" title="Reporte"
                                        data-placement="left">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal"> {{ __('Reporte') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
            <a class="nav-link" href="{!! url('/TeamLeaderModulo') !!}" title="Reporte"
                                        data-placement="left">
                <span class="sidebar-mini">  </span>
                <span class="sidebar-normal"> {{ __('Supervisor - Modulo') }} </span>
              </a>
            </li>

          </ul>
        </div>
      </li>
      @endif
    </ul>
  </div>
</div>

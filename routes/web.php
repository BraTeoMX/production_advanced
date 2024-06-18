<?php

use App\Http\Controllers\ModuloTeamL;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduccionMetasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('table-list', function () {
		return view('pages.table_list');
	})->name('table');

	Route::get('typography', function () {
		return view('pages.typography');
	})->name('typography');

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('map', function () {
		return view('pages.map');
	})->name('map');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

	Route::get('rtl-support', function () {
		return view('pages.language');
	})->name('language');

	Route::get('upgrade', function () {
		return view('pages.upgrade');
	})->name('upgrade');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	Route::get('actualizacion', ['as' => 'actualizacion.index', 'uses' => 'App\Http\Controllers\ActualizacionController@index']);
	Route::get('actualizacion/actualizar-datos', ['as' => 'actualizacion.actualizarDatos', 'uses' => 'App\Http\Controllers\ActualizacionController@actualizarDatos']);
    Route::post('actualizacion/actualizar-datos', ['as' => 'actualizacion.actualizarDatos', 'uses' => 'App\Http\Controllers\ActualizacionController@actualizarDatos']);

	Route::get('actualizacionII', ['as' => 'actualizacion.indexII', 'uses' => 'App\Http\Controllers\ActualizacionController@indexII']);


	Route::get('vpf', ['as' => 'vpf.index', 'uses' => 'App\Http\Controllers\VpfController@index']);
	Route::get('vprh', ['as' => 'vprh.index', 'uses' => 'App\Http\Controllers\VprhController@index']);
	Route::get('vpm', ['as' => 'vpm.index', 'uses' => 'App\Http\Controllers\VpmController@index']);
	Route::get('vpv', ['as' => 'vpv.index', 'uses' => 'App\Http\Controllers\VpvController@index']);

	Route::get('fechas', ['as' => 'fechas', 'uses' => 'App\Http\Controllers\HomeController@fechas']);//verificar fechas
	Route::get('fechasVPF', ['as' => 'fechasVPF', 'uses' => 'App\Http\Controllers\VpfController@fechasVPF']);
	Route::get('fechasVPM', ['as' => 'fechasVPM', 'uses' => 'App\Http\Controllers\VpmController@fechasVPM']);
	Route::get('fechasVPRH', ['as' => 'fechasVPRH', 'uses' => 'App\Http\Controllers\VprhController@fechasVPRH']);
	Route::get('fechasVPV', ['as' => 'fechasVPV', 'uses' => 'App\Http\Controllers\VpvController@fechasVPV']);

	Route::get('detalleVPF', ['as' => 'detalleVPF', 'uses' => 'App\Http\Controllers\HomeController@detalleVPF']);
	Route::get('detalleVPM', ['as' => 'detalleVPM', 'uses' => 'App\Http\Controllers\HomeController@detalleVPM']);
	Route::get('detalleVPRH', ['as' => 'detalleVPRH', 'uses' => 'App\Http\Controllers\HomeController@detalleVPRH']);
	Route::get('detalleVPV', ['as' => 'detalleVPV', 'uses' => 'App\Http\Controllers\HomeController@detalleVPV']);

	Route::get('fechasVPF2', ['as' => 'fechasVPF2', 'uses' => 'App\Http\Controllers\VpfController@fechasVPF2']);
	Route::get('fechasVPM2', ['as' => 'fechasVPM2', 'uses' => 'App\Http\Controllers\VpmController@fechasVPM2']);
	Route::get('fechasVPRH2', ['as' => 'fechasVPRH2', 'uses' => 'App\Http\Controllers\VprhController@fechasVPRH2']);
	Route::get('fechasVPV2', ['as' => 'fechasVPV2', 'uses' => 'App\Http\Controllers\VpvController@fechasVPV2']);

	Route::get('excepciones', ['as' => 'excepciones', 'uses' => 'App\Http\Controllers\HomeController@excepciones']);
	Route::get('fechas_excep', ['as' => 'fechas_excep', 'uses' => 'App\Http\Controllers\HomeController@fechas_excep']);
	Route::get('excepcionesVPF', ['as' => 'excepcionesVPF', 'uses' => 'App\Http\Controllers\VpfController@excepcionesVPF']);
	Route::get('fechas_excepVPF', ['as' => 'fechas_excepVPF', 'uses' => 'App\Http\Controllers\VpfController@fechas_excepVPF']);
	Route::get('excepcionesVPM', ['as' => 'excepcionesVPM', 'uses' => 'App\Http\Controllers\VpmController@excepcionesVPM']);
	Route::get('fechas_excepVPM', ['as' => 'fechas_excepVPM', 'uses' => 'App\Http\Controllers\VpmController@fechas_excepVPM']);
	Route::get('excepcionesVPRH', ['as' => 'excepcionesVPRH', 'uses' => 'App\Http\Controllers\VprhController@excepcionesVPRH']);
	Route::get('fechas_excepVPRH', ['as' => 'fechas_excepVPRH', 'uses' => 'App\Http\Controllers\VprhController@fechas_excepVPRH']);
	Route::get('excepcionesVPV', ['as' => 'excepcionesVPV', 'uses' => 'App\Http\Controllers\VpvController@excepcionesVPV']);
	Route::get('fechas_excepVPV', ['as' => 'fechas_excepVPV', 'uses' => 'App\Http\Controllers\VpvController@fechas_excepVPV']);

	Route::get('hojadevida', ['as' => 'hojadevida.index', 'uses' => 'App\Http\Controllers\HojadevidaController@index']);
	Route::get('detalleHojadevida/{id}', ['as' => 'detalleHojadevida', 'uses' => 'App\Http\Controllers\HojadevidaController@detalleHojadevida']);
	Route::get('fechashojadevida', ['as' => 'fechashojadevida', 'uses' => 'App\Http\Controllers\HojadevidaController@fechashojadevida']);

	///Relacion Modulos TeamLeader
    Route::get('Modulo-TeamLeader', ['as' => 'ModuloTeamL.ModulTeam', 'uses' => 'App\Http\Controllers\ModuloTeamL@ModulTeam']);
    Route::get('Update-Data/{team_leader}', ['as' => 'ModuloTeamL.SelectModulo', 'uses' => 'App\Http\Controllers\ModuloTeamL@SelectModulo']);
    Route::get('guardar-modulos',['as' => 'ModuloTeamL.guardarModulos', 'uses' => 'App\Http\Controllers\ModuloTeamL@guardarModulos']);

    Route::get('Modulo-TeamLeaderII', ['as' => 'ModuloTeamL.ModulTeamII', 'uses' => 'App\Http\Controllers\ModuloTeamL@ModulTeamII']);


    Route::post('guardar-modulos/{team_leader}', ['as' => 'ModuloTeamL.guardarModulos', 'uses' => 'App\Http\Controllers\ModuloTeamL@guardarModulos']);

// ...

// apartado para actualizar tabla de VPF
	Route::post('actualizarTabla', 'App\Http\Controllers\ModuloTeamL@actualizarTabla')->name('ModuloTeamL.Modulo-TeamLeaderactualizarTabla');
	Route::post('/transferir-datos', 'App\Http\Controllers\ModuloTeamL@transferirDatosDiarios')->name('transferir.datos');

	Route::get('/altasybajasTLyM', 'App\Http\Controllers\ModuloTeamL@altasybajasTLyM')->name('ModuloTeamL.altasybajasTLyM');
	Route::get('/tablaTLyM', 'App\Http\Controllers\ModuloTeamL@tablaTLyM')->name('ModuloTeamL.tablaTLyM');
	Route::get('/modificacionTablaTLyM', 'App\Http\Controllers\ModuloTeamL@showModificacionTablaTLyM')->name('ModuloTeamL.modificacionTablaTLyM');
    Route::get('/dia_anterior', 'App\Http\Controllers\ModuloTeamL@dia_anterior')->name('dia_anterior');

	// Ruta para mostrar los datos de las tablas TeamLeader y Modulos
	Route::post('/team-leader/store', 'App\Http\Controllers\ModuloTeamL@altasybajasTLyM')->name('team-leader.store');
	Route::post('/Modulo/store', 'App\Http\Controllers\ModuloTeamL@altasybajasTLyM')->name('Modulo.store');
	// Ruta para actualizar el estado de un Team Leader
	Route::patch('/team-leader/{id}/update-status', 'App\Http\Controllers\ModuloTeamL@ActualizarEstatus')->name('team-leader.ActualizarEstatus');
	// Ruta para actualizar el estado de un Módulo
	Route::patch('/Modulo/{id}/update-status', 'App\Http\Controllers\ModuloTeamL@ActualizarEstatusM')->name('Modulo.ActualizarEstatusM');
	Route::post('/asignar-modulos', 'App\Http\Controllers\ModuloTeamL@asignarModulos')->name('asignar.modulos');
	// Ruta para modificar datos de team leader y modulos en la tabla "team_modulo"
	Route::post('/team_modulo/modificar', 'App\Http\Controllers\ModuloTeamL@modificacionTablaTLyM')->name('team_modulo.modificar');

	Route::post('/actualiza_cifras', 'App\Http\Controllers\ModuloTeamL@actualiza_cifras')->name('actualiza_cifras');

    Route::get('detalleVS', ['as' => 'detalleVS', 'uses' => 'App\Http\Controllers\HomeController@detalleVS']);
	Route::get('detalleEmpaque', ['as' => 'detalleEmpaque', 'uses' => 'App\Http\Controllers\HomeController@detalleEmpaque']);
	Route::get('detalleCHICOS', ['as' => 'detalleCHICOS', 'uses' => 'App\Http\Controllers\HomeController@detalleCHICOS']);
	Route::get('detalleBN3', ['as' => 'detalleBN3', 'uses' => 'App\Http\Controllers\HomeController@detalleBN3']);
	Route::get('detalleNU', ['as' => 'detalleNU', 'uses' => 'App\Http\Controllers\HomeController@detalleNU']);
	Route::get('detalleMARENA', ['as' => 'detalleMARENA', 'uses' => 'App\Http\Controllers\HomeController@detalleMARENA']);
	Route::get('detallePACIFIC', ['as' => 'detallePACIFIC', 'uses' => 'App\Http\Controllers\HomeController@detallePACIFIC']);
	Route::get('detalleBELL', ['as' => 'detalleBELL', 'uses' => 'App\Http\Controllers\HomeController@detalleBELL']);
	Route::get('detalleWP', ['as' => 'detalleWP', 'uses' => 'App\Http\Controllers\HomeController@detalleWP']);
	Route::get('detalleHOOEY', ['as' => 'detalleHOOEY', 'uses' => 'App\Http\Controllers\HomeController@detalleHOOEY']);
    Route::get('detalleHANES', ['as' => 'detalleHANES', 'uses' => 'App\Http\Controllers\HomeController@detalleHANES']);
    Route::get('detalleRV', ['as' => 'detalleRV', 'uses' => 'App\Http\Controllers\HomeController@detalleRV']);


	//apartado de produccion
	Route::get('ProduccionTabla','App\Http\Controllers\'App\Http\Controllers\ProduccionController@ProduccionTabla')->name('metas.ProduccionTabla');
	Route::POST('ProduccionTabla', 'App\Http\Controllers\'App\Http\Controllers\ProduccionController@ProduccionTabla')->name('metas.ProduccionTabla');

	Route::post('actualizarSeleccion', 'App\Http\Controllers\ProduccionController@actualizarSeleccion')->name('metas.actualizarSeleccion');


	//apartado de Produccion filtrar semanas en la vista
	Route::get('filtrarSemanas','App\Http\Controllers\ProduccionController@filtrarSemanas')->name('metas.filtrarSemanas');
	Route::POST('filtrarSemanas', 'App\Http\Controllers\ProduccionController@filtrarSemanas')->name('metas.filtrarSemanas');

	// apartado para tablaPDF para generar archivo pdf
	Route::get('tablaPDF','App\Http\Controllers\ProduccionController@tablaPDF')->name('metas.tablaPDF');
	Route::POST('tablaPDF', 'App\Http\Controllers\ProduccionController@tablaPDF')->name('metas.tablaPDF');

	// apartado para tablaPDF para generar archivo pdf
	Route::get('Planta2tablaPDF','App\Http\Controllers\ProduccionController@Planta2tablaPDF')->name('metas.Planta2tablaPDF');
	Route::POST('Planta2tablaPDF', 'App\Http\Controllers\ProduccionController@Planta2tablaPDF')->name('metas.Planta2tablaPDF');


	// apartado para tablaPDF para generar archivo pdf
	Route::get('Planta2tabla2PDF','App\Http\Controllers\ProduccionController@Planta2tabla2PDF')->name('metas.Planta2tabla2PDF');
	Route::POST('Planta2tabla2PDF', 'App\Http\Controllers\ProduccionController@Planta2tabla2PDF')->name('metas.Planta2tabla2PDF');


	// apartado para tabla2PDF para generar archivo pdf
	Route::get('tabla2PDF','App\Http\Controllers\ProduccionController@tablaPDF')->name('metas.tabla2PDF');
	Route::POST('tabla2PDF', 'App\Http\Controllers\ProduccionController@tabla2PDF')->name('metas.tabla2PDF');

	// apartado para tablaEXCEL para generar archivo EXCEL
	Route::get('tablaEXCEL','App\Http\Controllers\ProduccionController@tablaEXCEL')->name('metas.tablaEXCEL');
	Route::POST('tablaEXCEL', 'App\Http\Controllers\ProduccionController@tablaEXCEL')->name('metas.tablaEXCEL');

	// apartado para tabla2EXCEL para generar archivo EXCEL
	Route::get('tabla2EXCEL','App\Http\Controllers\ProduccionController@tabla2EXCEL')->name('metas.tabla2EXCEL');
	Route::POST('tabla2EXCEL', 'App\Http\Controllers\ProduccionController@tabla2EXCEL')->name('metas.tabla2EXCEL');

	// apartado para Semana Actual
	Route::get('SemanaActual','App\Http\Controllers\ProduccionController@SemanaActual')->name('metas.SemanaActual');
	Route::POST('SemanaActual', 'App\Http\Controllers\ProduccionController@SemanaActual')->name('metas.SemanaActual');

	// apartado para Semana Actual

	Route::get('actualizarTabla','App\Http\Controllers\ProduccionController@actualizarTabla')->name('metas.actualizarTabla');
	Route::POST('actualizarTabla', 'App\Http\Controllers\ProduccionController@actualizarTabla')->name('metas.actualizarTabla');
    Route::POST('actualizarTablaP2', 'App\Http\Controllers\ProduccionController@actualizarTablaP2')->name('metas.actualizarTablaP2');

	// apartado para Reporte General de Produccion
	Route::get('ReporteGeneral','App\Http\Controllers\ProduccionController@ReporteGeneral')->name('metas.ReporteGeneral');
	Route::POST('ReporteGeneral', 'App\Http\Controllers\ProduccionController@ReporteGeneral')->name('metas.ReporteGeneral');

	// apartado para Team Leader - Modulo
	Route::get('TeamLeaderModulo','App\Http\Controllers\ProduccionController@TeamLeaderModulo')->name('TeamLeaderModulo');
	Route::POST('TeamLeaderModulo', 'App\Http\Controllers\ProduccionController@TeamLeaderModulo')->name('metas.TeamLeaderModulo');
	Route::post('/agregarTeamLeader', 'App\Http\Controllers\ProduccionController@agregarTeamLeader')->name('agregarTeamLeader');
	Route::patch('/ActualizarEstatusP1/{id}', 'App\Http\Controllers\ProduccionController@ActualizarEstatusP1')->name('ActualizarEstatusP1');
	Route::patch('/ActualizarEstatusP2/{id}', 'App\Http\Controllers\ProduccionController@ActualizarEstatusP2')->name('ActualizarEstatusP2');


	// apartado para generar archivos excel
	Route::post('/export-excel', 'App\Http\Controllers\ProduccionController@exportExcel')->name('metas.exportExcel');

    Route::get('/LeaderModulo', [ModuloTeamL::class, 'LeaderModulo']);
    Route::post('/guardarRelacion', [ModuloTeamL::class, 'guardarRelacion']);

	//nuevo apartado
	Route::get('supervisorModulo', [ProduccionMetasController::class, 'supervisorModulo'])->name('metas.supervisorModulo');
	Route::post('supervisorModulo', [ProduccionMetasController::class, 'storeSupervisor'])->name('agregarSupervisor');
	Route::patch('supervisorModulo/{id}', [ProduccionMetasController::class, 'updateStatusSupervisor'])->name('ActualizarEstatusSupervisor');

	Route::get('registroSemanal', [ProduccionMetasController::class, 'registroSemanal'])->name('metas.registroSemanal');
	Route::post('storeProduccion1', [ProduccionMetasController::class, 'storeProduccion1'])->name('metas.storeProduccion1');

	Route::get('reporteGeneralMetas', [ProduccionMetasController::class, 'reporteGeneralMetas'])->name('metas.reporteGeneralMetas');

});


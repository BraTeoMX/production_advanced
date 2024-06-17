<?php

namespace App\Http\Controllers;

use App\TeamModulo;

use App\cat_team_leader;
use App\cat_modulos;
use App\cat_clientes;
use App\team_modulo;
use App\Plan_diar;
use App\Team_Leader;
use App\Modulos;
use App\ProduccionDiaAnterior;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ModuloTeamL extends Controller
{
    public function ModulTeam()
{
    $activePage = '';
    $teamLeaders = Cat_team_leader::select('id', 'team_leader')->where('estatus','A')->get();

    $modulos = Cat_modulos::select('id', 'Modulo')->get();

    // Obtén los datos de la base de datos
    $teamModul = team_modulo::join('cat_team_leader', 'team_modulo.team_leader', '=', 'cat_team_leader.team_leader')
        ->join('cat_modulos', 'team_modulo.modulo', '=', 'cat_modulos.Modulo')
        ->select('team_modulo.id', 'cat_team_leader.team_leader', 'cat_modulos.Modulo', 'team_modulo.sam','team_modulo.op_real',
        'team_modulo.op_presencia','team_modulo.pxhrs', 'team_modulo.capacitacion', 'team_modulo.utility','team_modulo.piezas_meta','team_modulo.cliente','team_modulo.meta_cumplida')
        ->where('team_modulo.piezas_meta','>',0)
        ->where('team_modulo.planta','IntimarkI')
        ->orderBy('team_modulo.team_leader')
        ->orderBy('team_modulo.modulo')
        ->get();
    $teamModulsGrouped = $teamModul->groupBy('team_leader');
    $planta='IntimarkI';


    return view('ModuloTeamLeader.ModulTeam', compact('activePage', 'teamModulsGrouped','teamModul','teamLeaders', 'modulos','planta'));
}
public function RelacionLiderModulo()
{
    $activePage = '';


    // Obtén los datos de la base de datos
    $teamModul = team_modulo::join('cat_team_leader', 'team_modulo.team_leader', '=', 'cat_team_leader.team_leader')
        ->join('cat_modulos', 'team_modulo.modulo', '=', 'cat_modulos.Modulo')
        ->select('team_modulo.id', 'cat_team_leader.team_leader', 'cat_modulos.Modulo', 'team_modulo.sam','team_modulo.op_real',
        'team_modulo.op_presencia','team_modulo.pxhrs', 'team_modulo.capacitacion', 'team_modulo.utility','team_modulo.piezas_meta','team_modulo.cliente','team_modulo.meta_cumplida')
        ->where('team_modulo.piezas_meta','>',0)
        ->where('team_modulo.planta','IntimarkI')
        ->orderBy('team_modulo.team_leader')
        ->orderBy('team_modulo.modulo')
        ->get();
    $teamModulsGrouped = $teamModul->groupBy('team_leader');

    return view('ModuloTeamLeader.ModulTeam', compact('activePage', 'teamModulsGrouped','teamModul'));
}
public function LeaderModulo()
{
    $activePage = '';

    $teamLeaders = Cat_team_leader::select('id', 'team_leader')->get();
    $modulos = Cat_modulos::select('id', 'Modulo')->get();

    return view('ModuloTeamLeader.ModulTeam', compact('teamLeaders', 'modulos','activePage'));
}
public function guardarRelacion(Request $request)
{
    try {
        $teamLeaderId = $request->input('teamLeaderId');
        $moduloId = $request->input('moduloId');
        $plantaId = $request->input('plantaId');

        // Guarda los datos en tu modelo TeamModulo

        DB::table('team_modulo')->insert([
            'team_leader' => $teamLeaderId,
            'modulo' => $moduloId,
            'planta' =>  $plantaId,
            'piezas_meta' => 1,
            'meta_cumplida' =>0,

        ]);

/*
        Team_Modulo::create([
            'team_leader' => $teamLeaderId,
            'modulo' => $moduloId,
            'piezas_meta' => 1,
        ]);
*/
        // Puedes realizar acciones adicionales después de guardar, si es necesario

        return response()->json(['success' => $teamLeaderId]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
}


public function ModulTeamII()
{
    $activePage = '';

    $teamLeaders = Cat_team_leader::select('id', 'team_leader')->get();


    $modulos = Cat_modulos::select('id', 'Modulo')->get();

    // Obtén los datos de la base de datos
    $teamModul = team_modulo::join('cat_team_leader', 'team_modulo.team_leader', '=', 'cat_team_leader.team_leader')
        ->join('cat_modulos', 'team_modulo.modulo', '=', 'cat_modulos.Modulo')
        ->select('team_modulo.id', 'cat_team_leader.team_leader', 'cat_modulos.Modulo', 'team_modulo.sam','team_modulo.op_real',
        'team_modulo.op_presencia','team_modulo.pxhrs', 'team_modulo.capacitacion', 'team_modulo.utility','team_modulo.piezas_meta','team_modulo.cliente','team_modulo.meta_cumplida')
        ->where('team_modulo.piezas_meta','>',0)
        ->where('team_modulo.planta','IntimarkII')
       ->orderBy('team_modulo.team_leader')
        ->orderBy('team_modulo.modulo')
        ->get();
    $teamModulsGrouped = $teamModul->groupBy('team_leader');
    $planta='IntimarkII';

    return view('ModuloTeamLeader.ModulTeam', compact('activePage', 'teamModulsGrouped','teamModul','teamLeaders', 'modulos','planta'));
}

public function SelectModulo($team_leader)
{
  $User = $team_leader;
    $activePage = '';
    $teamModul = team_modulo::join('cat_team_leader', 'team_modulo.team_leader', '=', 'cat_team_leader.team_leader')
        ->join('cat_modulos', 'team_modulo.modulo', '=', 'cat_modulos.Modulo')
        ->select('team_modulo.id', 'cat_team_leader.team_leader', 'cat_modulos.Modulo', 'team_modulo.sam','team_modulo.op_real',
        'team_modulo.op_presencia','team_modulo.pxhrs', 'team_modulo.capacitacion', 'team_modulo.utility', 'team_modulo.piezas_meta', 'team_modulo.cliente', 'team_modulo.planta' ,'team_modulo.meta_cumplida')
        ->where('cat_team_leader.team_leader', $team_leader)
        ->orderBy('team_modulo.modulo')
       /* ->where('team_modulo.piezas_meta','>',0)
*/        ->get();
        $teamModulsGrouped = $teamModul->groupBy('team_leader');

    $modulos = cat_modulos::leftjoin('team_modulo', 'cat_modulos.Modulo', 'team_modulo.modulo')
    ->where('team_modulo.modulo', Null)
    ->orwhere('team_modulo.piezas_meta',0)
    ->select('cat_modulos.Modulo')
    ->orderby('cat_modulos.Modulo')
    ->get();

    //$clientes = team_modulo::select('cliente')->groupby('cliente')->get();
    $clientes = cat_clientes::select('cliente')->groupby('cliente')->get();

      return view('ModuloTeamLeader.SelectModulo', compact('activePage','teamModul','teamModulsGrouped','User','modulos','clientes'));
}

public function guardarModulos(Request $request, $team_leader)
    {
        date_default_timezone_set('America/Mexico_City');

        $dia_semana = date("N");

        if($dia_semana != 5)
            $tiempo_desocupacion = 630;
       else
            $tiempo_desocupacion = 360;

         if($dia_semana != 5)
            $horas_laboradas = 10.5;
         else
            $horas_laboradas = 6;

        $User = $team_leader;
        $moduloIds = $request->input('modulo');
        $cliente = $request->input('cliente');
        $sams = $request->input('sam');
        $opReales = $request->input('op_real');
        $op_presencia = $request->input('op_presencia');
        $pxhrs = $request->input('pxhrs');
        $capacitacion = $request->input('capacitacion');
        $utility = $request->input('utility');
        $piezas_meta = $request->input('piezas_meta');
        $meta_cumplida = $request->input('meta_cumplida');

        $modulos = cat_modulos::leftjoin('team_modulo', 'cat_modulos.Modulo', 'team_modulo.modulo')
            ->where('team_modulo.modulo', null)
            ->orwhere('team_modulo.piezas_meta',0)
            ->select('cat_modulos.Modulo')
            ->get();

        if ($request->has('actualizarData')) {
            // Obtén el valor del módulo seleccionado
            $moduloSeleccionado = $request->input('modulos');
            if (substr($moduloSeleccionado,0,1)==1){
                $updateData['planta'] = 'IntimarkI';
                $planta = 'IntimarkI';
            }else{
                $updateData['planta'] = 'IntimarkII';
                $planta = 'IntimarkII';
            }
            foreach ($moduloIds as $key => $moduloId) {
                if (
                    isset($sams[$key]) || isset($opReales[$key]) || isset($op_presencia[$key])
                    || isset($pxhrs[$key]) || isset($capacitacion[$key]) || isset($utility[$key]) || isset($piezas_meta[$key]))
                 {

                    $updateData = [];
                    if (isset($sams[$key])) {
                        $updateData['cliente'] = $cliente[$key];
                    }
                    if (isset($sams[$key])) {
                        $updateData['sam'] = $sams[$key];
                    }

                    if (isset($opReales[$key])) {
                        $updateData['op_real'] = $opReales[$key];
                    }
                    if (isset($op_presencia[$key])) {
                        $updateData['op_presencia'] = $op_presencia[$key];
                    }
                    if (isset($pxhrs[$key])) {
                        $updateData['pxhrs'] = $pxhrs[$key];
                    }
                    if (isset($capacitacion[$key])) {
                        $updateData['capacitacion'] = $capacitacion[$key];
                    }
                    if (isset($utility[$key])) {
                        $updateData['utility'] = $utility[$key];
                    }
                    if (isset($meta_cumplida[$key])) {
                        $updateData['meta_cumplida'] = $meta_cumplida[$key];
                    }
                    if (isset($piezas_meta[$key])) {

                        if($moduloIds[$key] != '830A'){
                            $updateData['piezas_meta'] = $piezas_meta[$key];
                            $updateData['min_x_producir'] = $piezas_meta[$key]*$sams[$key]*1.02;
                            $updateData['min_presencia'] =$op_presencia[$key]*$tiempo_desocupacion;
                            $updateData['min_presencia_netos'] =(($op_presencia[$key]*$tiempo_desocupacion)-($pxhrs[$key]+$capacitacion[$key]))+$utility[$key];
                            if ($op_presencia[$key]!=0){
                                $updateData['eficiencia_dia'] =($piezas_meta[$key]*$sams[$key]*1.02)/((($op_presencia[$key]*$tiempo_desocupacion)-($pxhrs[$key]+$capacitacion[$key]))+$utility[$key])*100;
                            }else{
                                $updateData['eficiencia_dia'] = 0;
                            }

                        }else{
                            $updateData['piezas_meta'] = $piezas_meta[$key];
                            $updateData['min_x_producir'] = $op_presencia[$key]*$tiempo_desocupacion*0.98*.90;
                            $updateData['min_presencia'] =$op_presencia[$key]*$tiempo_desocupacion;
                            $updateData['min_presencia_netos'] =(($op_presencia[$key]*$tiempo_desocupacion)-($pxhrs[$key]+$capacitacion[$key]))+$utility[$key];
                            if ($op_presencia[$key]!=0){
                               // $updateData['eficiencia_dia'] =90($piezas_meta[$key]*$sams[$key])/((($op_presencia[$key]*$tiempo_desocupacion)-($pxhrs[$key]+$capacitacion[$key]))+$utility[$key])*100;
                                $updateData['eficiencia_dia'] =90;

                            }else{
                                $updateData['eficiencia_dia'] = 0;
                            }
                        }
                        if($moduloIds[$key] == '831A'){
                            $updateData['piezas_meta'] = $piezas_meta[$key];
                            $updateData['min_x_producir'] = $op_presencia[$key]*$tiempo_desocupacion*.85;
                            $updateData['min_presencia'] =$op_presencia[$key]*$tiempo_desocupacion;
                            $updateData['min_presencia_netos'] =(($op_presencia[$key]*$tiempo_desocupacion)-($pxhrs[$key]+$capacitacion[$key]))+$utility[$key];
                            if ($op_presencia[$key]!=0){
                                $updateData['eficiencia_dia'] =85;
                            }else{
                                $updateData['eficiencia_dia'] = 0;
                            }
                        }

                    }
                    if ($meta_cumplida[$key] > 0) {
                        $updateData['meta_cumplida'] = $meta_cumplida[$key];
                        $updateData['min_presencia'] =$op_presencia[$key]*$meta_cumplida[$key];
                        $updateData['min_presencia_netos'] =(($op_presencia[$key]*$meta_cumplida[$key])-($pxhrs[$key]+$capacitacion[$key]))+$utility[$key];

                    }
                    team_modulo::where('Modulo', $moduloId)->update($updateData);
                }
            }

            if ($moduloSeleccionado) {
                if (substr($moduloSeleccionado,0,1)==1){
                    $planta = 'IntimarkI';
                }else{
                    $planta = 'IntimarkII';
                }

                $res=Team_Modulo::where('Modulo',$moduloSeleccionado)->delete();

                DB::table('team_modulo')->insert([
                    'team_leader' => $team_leader,
                    'modulo' => $moduloSeleccionado,
                    'planta' => $planta,
                    'piezas_meta' => 1,
                    'meta_cumplida' =>0,

                ]);
            }

            $activePage = '';


            // Obtén los datos de la base de datos
            if($request->input('planta')=='IntimarkI')
            {
                    $teamModul = team_modulo::join('cat_team_leader', 'team_modulo.team_leader', '=', 'cat_team_leader.team_leader')
                    ->join('cat_modulos', 'team_modulo.modulo', '=', 'cat_modulos.Modulo')
                    ->select('team_modulo.id', 'cat_team_leader.team_leader', 'cat_modulos.Modulo', 'team_modulo.sam','team_modulo.op_real',
                    'team_modulo.op_presencia','team_modulo.pxhrs', 'team_modulo.capacitacion', 'team_modulo.utility','team_modulo.piezas_meta','team_modulo.cliente','team_modulo.planta','team_modulo.meta_cumplida')
                    ->where('team_modulo.piezas_meta','>',0)
                    ->where('team_modulo.planta','IntimarkI')
                    ->orderBy('team_modulo.team_leader')
                    ->orderBy('team_modulo.modulo')
                    ->get();
                    $teamModulsGrouped = $teamModul->groupBy('team_leader');
            }else{
                $teamModul = team_modulo::join('cat_team_leader', 'team_modulo.team_leader', '=', 'cat_team_leader.team_leader')
                ->join('cat_modulos', 'team_modulo.modulo', '=', 'cat_modulos.Modulo')
                ->select('team_modulo.id', 'cat_team_leader.team_leader', 'cat_modulos.Modulo', 'team_modulo.sam','team_modulo.op_real',
                'team_modulo.op_presencia','team_modulo.pxhrs', 'team_modulo.capacitacion', 'team_modulo.utility','team_modulo.piezas_meta','team_modulo.cliente','team_modulo.planta','team_modulo.meta_cumplida')
                ->where('team_modulo.piezas_meta','>',0)
                ->where('team_modulo.planta','IntimarkII')
                ->orderBy('team_modulo.team_leader')
                ->orderBy('team_modulo.modulo')
                ->get();
                $teamModulsGrouped = $teamModul->groupBy('team_leader');
            }

            $teamLeaders = Cat_team_leader::select('id', 'team_leader')->get();


            $modulos = Cat_modulos::select('id', 'Modulo')->get();
          //  return redirect()->route('ModuloTeamL.SelectModulo', ['team_leader' => $User])->with('modulos', $modulos);
          return view('ModuloTeamLeader.ModulTeam', compact('activePage', 'teamModulsGrouped','teamModul','planta','teamLeaders','modulos'));
         }
    }


    public function altasybajasTLyM(Request $request)
    {
        // Si es un POST request, entonces intentamos agregar un nuevo registro.
        if ($request->isMethod('post')) {
            // Validación básica
            $request->validate([
                'team_leader' => 'sometimes|required|max:255',
                'Modulo' => 'sometimes|required|max:255',
            ]);

            // Agregar un Team Leader
            if ($request->input('team_leader')) {
                // Buscar si ya existe un Team Leader con ese nombre
                $existingLeader = Cat_team_leader::where('team_leader', $request->input('team_leader'))->first();
                if ($existingLeader) {
                    // Si existe, regresa con un mensaje de error
                    return back()->with('error', 'El Team Leader ya existe.');
                } else {
                    // Si no existe, crea uno nuevo
                    $newLeader = new Cat_team_leader(['team_leader' => $request->input('team_leader'), 'estatus' => 'A']);
                    $newLeader->save();
                    return back()->with('success', 'Nuevo Team Leader agregado.');
                }
            }
            // Agregar un Módulo
            elseif ($request->input('Modulo')) {
                // Buscar si ya existe un Módulo con ese nombre
                $existingModulo = Cat_modulos::where('Modulo', $request->input('Modulo'))->first();
                if ($existingModulo) {
                    // Si existe, regresa con un mensaje de error
                    return back()->with('error', 'El Módulo ya existe.');
                } else {
                    // Si no existe, crea uno nuevo
                    $newModulo = new Cat_modulos(['Modulo' => $request->input('Modulo'), 'estatus' => 'A']);
                    $newModulo->save();
                    return back()->with('success', 'Nuevo Módulo agregado.');
                }
            }
        }
        $mensaje = "Hola mundo";
        $teamLeaders = team_leader::all();
        $modulos = Modulos::all();
        /*
        $teamLeaders = Cat_team_leader::where('estatus', 'A')->get();
        $modulos = Cat_modulos::where('estatus', 'A')->get();
        */
        return view('ModuloTeamLeader.altasybajasTLyM', compact('mensaje','teamLeaders', 'modulos'));

    }

    public function ActualizarEstatus(Request $request, $id) {
        $teamLeader = Cat_team_leader::findOrFail($id);
        $teamLeader->estatus = $request->input('estatus', 'A'); // Asumiendo 'A' como valor por defecto para "Dar de Alta"
        $teamLeader->save();

        $mensaje = $teamLeader->estatus == 'A' ? 'El Team Leader ha sido dado de alta.' : 'El Team Leader ha sido dado de baja.';

        return back()->with('status', $mensaje);
    }

    public function ActualizarEstatusM(Request $request, $id) {
        $modulo = Cat_modulos::findOrFail($id);

        // Cambia el estatus basado en el valor recibido del formulario
        $nuevoEstatus = $request->input('estatus');
        $modulo->estatus = $nuevoEstatus;
        $modulo->save();

        // Mensaje de éxito personalizado basado en la acción realizada
        $mensaje = $nuevoEstatus == 'A' ? 'El módulo ha sido dado de alta.' : 'El módulo ha sido dado de baja.';

        return back()->with('status', $mensaje);
    }



    public function tablaTLyM(Request $request)
    {

        $mensaje = "Hola mundo";
        $teamLeaders = Cat_team_leader::all();
        $modulos = Cat_modulos::all();
        // Obtiene todos los registros de TeamModulo y sus relaciones
        //$teamModulos = TeamModulo::with('catTeamLeader', 'catModulo')->get();
        $teamModulos = TeamModulo::with(['catTeamLeader' => function ($query) {
            $query->where('estatus', 'A');
        }, 'catModulo' => function ($query) {
            $query->where('estatus', 'A');
        }])->whereHas('catTeamLeader', function ($query) {
            $query->where('estatus', 'A');
        })->whereHas('catModulo', function ($query) {
            $query->where('estatus', 'A');
        })->get();

        return view('ModuloTeamLeader.tablaTLyM', compact('mensaje', 'teamModulos'));

    }

    public function dia_anterior(Request $request)
    {
        $dia_actual = date('Y-m-d');
        $dia_anterior = strtotime('-1 day', strtotime($dia_actual));
        $dia = date('Y-m-d', $dia_anterior);



        return view('ModuloTeamLeader.dia_anterior', compact('dia'));

    }

    public function actualiza_cifras(Request $request)
    {
        $dia_actual = date('Y-m-d');
        $dia_anterior = strtotime('-1 day', strtotime($dia_actual));
        $dia = date('Y-m-d', $dia_anterior);

        $new = new ProduccionDiaAnterior();
        $new->dia_anterior = $dia;
        $new->producidasI = $request->input('producidasI');
        $new->producidasII = $request->input('producidasII');

        $new->save();
        return redirect('/home');
        // return  back()->with('success', 'Registrado con Exito.');

    }


}

<?php

namespace App\Http\Controllers;


use App\team_leader;
use App\modulos;
use App\team_modulo;
use App\Plan_diar;
use App\Plan_diar2;
use Illuminate\Http\Request;


class ActualizacionController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        date_default_timezone_set('America/Mexico_City');

        //cambio de horario
        $fecha = (localtime(time(), true));

        if ($fecha["tm_isdst"] == 1) {
            $hora_aux = $fecha["tm_hour"] - 1;
            $dia_aux = $fecha["tm_year"] . '-' . $fecha["tm_mon"] . '-' . $fecha["tm_mday"];
            $hora_aux = $hora_aux . ':' . $fecha["tm_min"] . ':' . $fecha["tm_sec"];
        } else {
            $dia_aux = $fecha["tm_year"] . '-' . $fecha["tm_mon"] . '-' . $fecha["tm_mday"];
            $hora_aux = $fecha["tm_hour"] . ':' . $fecha["tm_min"] . ':' . $fecha["tm_sec"];
        }


        $inicio='';
        $fin ='';
        $hoy =date('d/m');
        $hora = date("G", strtotime($hora_aux)).":00";
        $hora2 = date("G", strtotime($hora_aux)).":59";
        $hora3 = date("G", strtotime($hora_aux)).":35";
        $hoy_hora = strftime("%H:%M ", strtotime($hora_aux));

        $dia_semana = date("N");
        $actual = date('Y-m-d');
        $minutos = strftime("%M, ");

        $hoy =date('d/m');
      //  $hora = date("G").":00";
        $activePage = '';
        $teamModul = team_modulo::where('team_leader','<>','0')->where('piezas_meta','<>','0')->where('planta','IntimarkI')->orderby('team_leader', 'asc')->orderby('modulo', 'asc')->get();

        $team_leaders = team_leader::all();
        $modulos = modulos::all();


        return view('actualizacion.index', compact('team_leaders', 'activePage', 'modulos', 'teamModul','hoy','hora'));
    }

    public function indexII()
    {
       // date_default_timezone_set('America/Mexico_City');
        date_default_timezone_set('America/Mexico_City');

        //cambio de horario
        $fecha = (localtime(time(), true));

        if ($fecha["tm_isdst"] == 1) {
            $hora_aux = $fecha["tm_hour"] - 1;
            $dia_aux = $fecha["tm_year"] . '-' . $fecha["tm_mon"] . '-' . $fecha["tm_mday"];
            $hora_aux = $hora_aux . ':' . $fecha["tm_min"] . ':' . $fecha["tm_sec"];
        } else {
            $dia_aux = $fecha["tm_year"] . '-' . $fecha["tm_mon"] . '-' . $fecha["tm_mday"];
            $hora_aux = $fecha["tm_hour"] . ':' . $fecha["tm_min"] . ':' . $fecha["tm_sec"];
        }


        $inicio='';
        $fin ='';
        $hoy =date('d/m');
        $hora = date("G", strtotime($hora_aux)).":00";
        $hora2 = date("G", strtotime($hora_aux)).":59";
        $hora3 = date("G", strtotime($hora_aux)).":35";
        $hoy_hora = strftime("%H:%M ", strtotime($hora_aux));

        $dia_semana = date("N");
        $actual = date('Y-m-d');
        $minutos = strftime("%M, ");

        $hoy =date('d/m');
       // $hora = date("G").":00";
        $activePage = '';
        $teamModul = team_modulo::where('team_leader','<>','0')->where('piezas_meta','<>','0')->where('planta','IntimarkII')->orderby('team_leader', 'asc')->orderby('modulo', 'asc')->get();

        $team_leaders = team_leader::all();
        $modulos = modulos::all();


        return view('actualizacion.index', compact('team_leaders', 'activePage', 'modulos', 'teamModul','hoy','hora'));
    }

    public function actualizarDatos(Request $request)
    {

        //date_default_timezone_set('America/Mexico_City');
        date_default_timezone_set('America/Mexico_City');

        //cambio de horario
        $fecha = (localtime(time(), true));

        if ($fecha["tm_isdst"] == 1) {
            $hora_aux = $fecha["tm_hour"] - 1;
            $dia_aux = $fecha["tm_year"] . '-' . $fecha["tm_mon"] . '-' . $fecha["tm_mday"];
            $hora_aux = $hora_aux . ':' . $fecha["tm_min"] . ':' . $fecha["tm_sec"];
        } else {
            $dia_aux = $fecha["tm_year"] . '-' . $fecha["tm_mon"] . '-' . $fecha["tm_mday"];
            $hora_aux = $fecha["tm_hour"] . ':' . $fecha["tm_min"] . ':' . $fecha["tm_sec"];
        }


        $inicio='';
        $fin ='';
        $hoy =date('d/m');
        $hora = date("G", strtotime($hora_aux)).":00";
        $hora2 = date("G", strtotime($hora_aux)).":59";
        $hora3 = date("G", strtotime($hora_aux)).":35";
        $hoy_hora = strftime("%H:%M ", strtotime($hora_aux));

        $dia_semana = date("N");
        $actual = date('Y-m-d');
        $minutos = strftime("%M, ");


        $formData = json_decode($request->input('formData'), true);

        $resultados = [];
        $proy_min_total = 0;
        $min_pres_neto_total = 0;

        foreach ($formData as $data) {
            $id = $data['id'];
            $piezas = $data['value'];

            $teamModulInfo = team_modulo::select('id', 'team_leader', 'modulo')
                ->where('id', $id)
                ->first();



            if ($teamModulInfo) {
                $resultados[$teamModulInfo->id] = [
                    'id' => $id,
                    'consulta' => $teamModulInfo,
                    'piezas' => $piezas,
                ];
                $teams_leader = team_leader::select('team_leader')
                ->where('id', $teamModulInfo->team_leader)
                ->first();
                $modulos = modulos::select('Modulo')
                ->where('id', $teamModulInfo->modulo)
                ->first();


                // Verifica si ya existe un registro con las mismas características
                $existingRecord = Plan_diar2::where('id_planeacion', $id . '-' . $teamModulInfo->team_leader)
                    ->where('team_leader', $teamModulInfo->team_leader)
                    ->where('Modulo', $teamModulInfo->modulo)
                    ->first();

                $registrarData = new Plan_diar2();

                // Obtén la hora actual con minutos y segundos en 0 si es después de las 09:00 y antes de las 18:00
                //$horaActual = now()->format('H:i:s');
                $horaActual = $hora_aux;

                if (now()->hour >= 9 && now()->hour <= 18) {
//                    $horaActual = now()->format('H:00:00');
                    $horaActual = date("G", strtotime($hora_aux)).":00:00";

                }
                $registrarData->id_planeacion = $id . '-' . $teamModulInfo->team_leader;
                $registrarData->team_leader = $teamModulInfo->team_leader;
                $registrarData->Modulo =$teamModulInfo->modulo;
                $registrarData->piezas = $piezas;

                if ($existingRecord) {
                    // Si existe un registro, copia sus datos a las columnas correspondientes
                    $registrarData->created_at = null;
                    $registrarData->updated_at = $horaActual;
                } else {
                    // Si no existe un registro previo, establece created_at y updated_at
                    $registrarData->created_at = $horaActual;
                    $registrarData->updated_at = $horaActual;
                }



                /**************minutos producidos *********/

                $sam = Team_Modulo::where('modulo', $registrarData->Modulo )->select('sam')->first();

                $registrarData->min_producidos=$registrarData->piezas*$sam->sam;

                if($registrarData->Modulo == '830A'){
                    $sam = Team_Modulo::where('modulo', '830A')->select('sam')->first();
                    $registrarData->min_producidos = $piezas;
                    $registrarData->piezas = $piezas/$sam->sam;
                }


                 /**************proyeccion de minutos  *********/
                 if(date("G", strtotime($registrarData->updated_at))=='09'){
                    $valor_hora=1;
                 }else{
                    if(date("G", strtotime($registrarData->updated_at))=='10'){
                        $valor_hora=2;
                     }else{
                        if(date("G", strtotime($registrarData->updated_at))=='11'){
                            $valor_hora=3;
                         }else{
                            if(date("G", strtotime($registrarData->updated_at))=='12'){
                                $valor_hora=4;
                             }else{
                                if(date("G", strtotime($registrarData->updated_at))=='13'){
                                    $valor_hora=5;
                                 }else{
                                    if(date("G", strtotime($registrarData->updated_at))=='14'){
                                        $valor_hora=5.5;
                                     }else{
                                        if(date("G", strtotime($registrarData->updated_at))=='15'){
                                            $valor_hora=6.5;
                                         }else{
                                            if(date("G", strtotime($registrarData->updated_at))=='16'){
                                                $valor_hora=7.5;
                                             }else{
                                                if(date("G", strtotime($registrarData->updated_at))=='17'){
                                                    $valor_hora=8.5;
                                                 }else{
                                                    if(date("G", strtotime($registrarData->updated_at))=='18'){
                                                        $valor_hora=9.5;
                                                     }else
                                                        $valor_hora=1;
                                                 }
                                             }
                                         }
                                     }
                                 }
                             }
                         }
                     }
                 };
                 if($dia_semana = date("N")<>5)
                    $horas_laboradas = 10.5;
                else
                    $horas_laboradas = 6;

                 $registrarData->proyeccion_minutos=  ($registrarData->min_producidos/$valor_hora)*$horas_laboradas;


                 /************eficiencia ************** */



                 $tiempo_desocupacion = 630;
                 $op_presencia = Team_Modulo::where('modulo', $registrarData->Modulo )->select('op_presencia','pxhrs','capacitacion','utility')->first();
                 $min_presencia=$op_presencia->op_presencia*$tiempo_desocupacion;
                 $min_presencia_netos=($min_presencia-($op_presencia->pxhrs+$op_presencia->capacitacion))+$op_presencia->utility;

                 if($min_presencia_netos<>0)
                    $registrarData->efic = ($registrarData->proyeccion_minutos/ $min_presencia_netos)*100;
                  else
                    $registrarData->efic = 0;

                 $proy_min_total=$proy_min_total+$registrarData->proyeccion_minutos;
                 $min_pres_neto_total = $min_pres_neto_total+$min_presencia_netos;

                 $registrarData->efic_total =($proy_min_total/$min_pres_neto_total)*100;


                $registrarData->save();
            }
        }

        //return redirect()->route('actualizacion.index');
        return redirect('/home');
    }




}


<?php

namespace App\Http\Controllers;

use App\Formato_P07;
use App\Team_Leader;
use App\Team_Modulo;
use App\Planeacion;
use App\Plan_diar2;
use App\Plan_diar;
use App\Modulos;
use App\ProduccionDiaAnterior;
use App\Tickets_empaque;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
   /*     date_default_timezone_set('America/Mexico_City');
        $inicio='';
        $fin ='';
        $hoy =date('d/m');
        $hora = date("G").":00";
        $hora3 = date("G").":35";

        $dia_semana = date("N");
        $actual = date('Y-m-d');
        $minutos = strftime("%M ");

        if($minutos >20){


        }
 */

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

        /**************proyeccion de minutos  *********/
        if(date("G", strtotime($hora))=='09'){
            $valor_hora=1;
         }else{
            if(date("G", strtotime($hora))=='10'){
                $valor_hora=2;
             }else{
                if(date("G", strtotime($hora))=='11'){
                    $valor_hora=3;
                 }else{
                    if(date("G", strtotime($hora))=='12'){
                        $valor_hora=4;
                     }else{
                        if(date("G", strtotime($hora))=='13'){
                            $valor_hora=5;
                         }else{
                            if(date("G", strtotime($hora))=='14'){
                                $valor_hora=5.5;
                             }else{
                                if(date("G", strtotime($hora))=='15'){
                                    $valor_hora=6.5;
                                 }else{
                                    if(date("G", strtotime($hora))=='16'){
                                        $valor_hora=7.5;
                                     }else{
                                        if(date("G", strtotime($hora))=='17'){
                                            $valor_hora=8.5;
                                         }else{
                                            if(date("G", strtotime($hora))=='18'){
                                                $valor_hora=9.5;
                                             }else{
                                                $valor_hora=0;
                                             }
                                         }
                                     }
                                 }
                             }
                         }
                     }
                 }
             }
         };
//dd($valor_hora);
        $inicio=date("Y-m-d",strtotime($actual."- ".$dia_semana." days"));
        $fin = date("Y-m-d",strtotime($actual."+ 6 days"));

         /**************indicadores *************** */
       //  $tiempo_desocupacion = 630*0.98;
       if($dia_semana != 5)
         $tiempo_desocupacion = 630;
       else
         $tiempo_desocupacion = 352.8;

       if($dia_semana != 5)
         $horas_laboradas = 10.5;
       else
         $horas_laboradas = 6;

         $sam_empaque= .910;

           /********** actualizacion ticket offline *******************/

        date_default_timezone_set('America/Mexico_City');

        //$formData = json_decode($request->input('formData'), true);
        $formData = Plan_diar::select('piezas','modulo')
        ->whereDate('fecha', $actual)
        ->where(\DB::raw('substr(modulo, 1, 1)'), '=' , 1)
       // ->GroupBy('modulo')
        ->get();
        $resultados = [];
        $proy_min_total = 0;
        $min_pres_neto_total = 0;


        foreach ($formData as $data) {
            $piezas = $data['piezas'];
            $modulo = $data['modulo'];


            $teamModulInfo = team_modulo::select('team_leader', 'modulo')
                ->where('modulo', $modulo)
                ->first();

            if ($teamModulInfo) {
                $resultados[$teamModulInfo->modulo] = [
                    'consulta' => $teamModulInfo,
                    'piezas' => $piezas,
                ];
                $teams_leader = team_modulo::select('team_leader')
                ->where('modulo', $teamModulInfo->modulo)
                ->first();
                $modulos = modulos::select('Modulo')
                ->where('modulo', $teamModulInfo->modulo)
                ->first();


                // Verifica si ya existe un registro con las mismas características
                $existingRecord = Plan_diar2::where('team_leader', $teamModulInfo->team_leader)
                    ->where('Modulo', $teamModulInfo->modulo)
                    ->first();

                $registrarData = new Plan_diar2();

                // Obtén la hora actual con minutos y segundos en 0 si es después de las 09:00 y antes de las 18:00
                $horaActual = now()->format('H:i:s');

                if (now()->hour >= 9 && now()->hour <= 18) {
                    $horaActual = now()->format('H:00:00');
                }
              //  $registrarData->id_planeacion = $id . '-' . $teamModulInfo->team_leader;
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
                 if(date("G", strtotime($registrarData->created_at))=='09'){
                    $valor_hora2=1;
                 }else{
                    if(date("G", strtotime($registrarData->created_at))=='10'){
                        $valor_hora2=2;
                     }else{
                        if(date("G", strtotime($registrarData->created_at))=='11'){
                            $valor_hora2=3;
                         }else{
                            if(date("G", strtotime($registrarData->created_at))=='12'){
                                $valor_hora2=4;
                             }else{
                                if(date("G", strtotime($registrarData->created_at))=='13'){
                                    $valor_hora2=5;
                                 }else{
                                    if(date("G", strtotime($registrarData->created_at))=='14'){
                                        $valor_hora2=5.5;
                                     }else{
                                        if(date("G", strtotime($registrarData->created_at))=='15'){
                                            $valor_hora2=6.5;
                                         }else{
                                            if(date("G", strtotime($registrarData->created_at))=='16'){
                                                $valor_hora2=7.5;
                                             }else{
                                                if(date("G", strtotime($registrarData->created_at))=='17'){
                                                    $valor_hora2=8.5;
                                                 }else{
                                                    if(date("G", strtotime($registrarData->created_at))=='18'){
                                                        $valor_hora2=9.5;
                                                     }
                                                 }
                                             }
                                         }
                                     }
                                 }
                             }
                         }
                     }
                 };
                 if($dia_semana  <>5)
                    $horas_laboradas = 10.5;
                else
                    $horas_laboradas = 6;

                 $registrarData->proyeccion_minutos=  ($registrarData->min_producidos/$valor_hora2)*$horas_laboradas;
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

              //  $registrarData->efic_total =($proy_min_total/$min_pres_neto_total)*100;

              Plan_diar::where('modulo', $modulo)->whereDate('fecha',$actual)->where('efic',0)->update([
                'team_leader' =>  $registrarData->team_leader,
                'min_producidos' => $registrarData->min_producidos,
                'proyeccion_minutos' => $registrarData->proyeccion_minutos,
                'efic' =>  $registrarData->efic
            ]);

               // $registrarData->save();
            }
        }


        /***************montos generales ******************/

        $meta = Formato_P07::select(DB::raw('sum(cantidad_total) as cantidad_total'), DB::raw('AVG(eficiencia_total) as eficiencia_total'))->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->where('modulo','total')->get();

        if(!$meta){
            $eficiencia_total = 0;
            $cantidad_total = 0;
        }else{
            $eficiencia_total = $meta[0]->eficiencia_total;
            $cantidad_total = $meta[0]->cantidad_total;
        }

        $cantidad_dia =  Team_Modulo::whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
        $cantidad_dia = ($cantidad_dia/$horas_laboradas )*$valor_hora;

        if($dia_semana != 5)
            $tiempo_desocupacion = 630;
        else
            $tiempo_desocupacion = 352.8;

        $efic_dia_830A = .90;

        $op_presencia1 = Team_modulo::where('modulo','<>','830A')->where('modulo','<>','831A')->sum('op_presencia');
        $op_presencia2 = Team_modulo::where('modulo','830A')->where('modulo','<>','831A')->sum('op_presencia');

        $pxhrs = Team_modulo::where('modulo','<>','830A')->where('modulo','<>','831A')->sum('pxhrs');
        $capacitacion = Team_modulo::where('modulo','<>','830A')->where('modulo','<>','831A')->sum('capacitacion');
        $utility = Team_modulo::where('modulo','<>','830A')->where('modulo','<>','831A')->sum('utility');
        $sam = Team_Modulo::where('modulo','<>','830A')->where('modulo','<>','831A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1 = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('modulo','<>','830A')->where('modulo','<>','831A')->get();

        $min_x_producir_1 = $cantidad_1[0]->total*1.02;
        $min_x_producir_2 = ($op_presencia2 * $tiempo_desocupacion)*0.98*$efic_dia_830A;

        $min_x_producir_dia = $min_x_producir_1 + $min_x_producir_2;
        //$min_x_producir_dia = $min_x_producir_dia*1.02;

        $min_pres_dia = ($op_presencia1+$op_presencia2)* $tiempo_desocupacion;
        $min_pres_netos_dia = ($min_pres_dia - ($pxhrs+$capacitacion)) + $utility;


        $eficiencia_dia = ($min_x_producir_dia/$min_pres_netos_dia)*100; //* promedio del dia

        if($cantidad_dia == 0.0){

            $cantidad_dia = Formato_P07::where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
            $eficiencia_dia = Formato_P07::where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);
            $eficiencia_dia = $eficiencia_dia/2;
        }
        $eficiencia_dia = Formato_P07::where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);
        $eficiencia_dia = $eficiencia_dia/2;

/************************** por planta ************* */
        $plantas = Formato_P07::select('planta')->groupby('Planta')->get();

           // $cantidad_plantaI = Formato_P07::where('planta','Intimark I')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
            $cantidad_plantaI =  Team_Modulo::where('planta','IntimarkI')->where('Modulo','<>','830A')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
            $cantidad_plantaI = ($cantidad_plantaI/$horas_laboradas )*$valor_hora;
//dd($cantidad_plantaI,$horas_laboradas,$valor_hora);
            $op_presencia1_plantaI = Team_modulo::where('planta','IntimarkI')->where('modulo','<>','830A')->sum('op_presencia');
            $op_presencia2_plantaI = Team_modulo::where('planta','IntimarkI')->where('modulo','830A')->sum('op_presencia');

            $pxhrs_plantaI = Team_modulo::where('planta','IntimarkI')->sum('pxhrs');
            $capacitacion_plantaI = Team_modulo::where('planta','IntimarkI')->sum('capacitacion');
            $utility_plantaI = Team_modulo::where('planta','IntimarkI')->sum('utility');
            $sam_plantaI = Team_Modulo::where('planta','IntimarkI')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
            $cantidad_1_plantaI = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('planta','IntimarkI')->where('modulo','<>','830A')->get();

            $min_x_producir_1_plantaI = $cantidad_1_plantaI[0]->total*1.02;
            $min_x_producir_2_plantaI = ($op_presencia2_plantaI * $tiempo_desocupacion)*0.98*$efic_dia_830A;

            $min_x_producir_plantaI = $min_x_producir_1_plantaI + $min_x_producir_2_plantaI;

            $min_pres_plantaI = ($op_presencia1_plantaI+$op_presencia2_plantaI)* $tiempo_desocupacion;
            $min_pres_plantaI = Team_Modulo::where('planta','IntimarkI')->where('modulo','<>','830A')->where('modulo','<>','802A')->sum('min_presencia');

            $min_pres_netos_plantaI = ($min_pres_plantaI - ($pxhrs_plantaI+$capacitacion_plantaI)) + $utility_plantaI;

            $min_pres_netos_plantaI= Team_Modulo::where('planta','IntimarkI')->where('piezas_meta','<>',0)->whereDate('updated_at',$actual)->sum('min_presencia_netos');
            $proyeccion_min_plantaI = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
            ->where('planta','IntimarkI')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

            if($min_pres_netos_plantaI == 0){
                $eficiencia_plantaI = 0;
            }else{
                $eficiencia_plantaI = ($proyeccion_min_plantaI / $min_pres_netos_plantaI)*100;
            }

            if($cantidad_plantaI == 0){
                $cantidad_plantaI = Formato_P07::where('planta','IntimarkI')->where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
                $eficiencia_plantaI = Formato_P07::where('planta','IntimarkI')->where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);
            }
            $eficiencia_plantaI_07 = Formato_P07::where('planta','IntimarkI')->where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);

            $op_presencia1_dia_plantaI = Team_modulo::where('planta','IntimarkI')->where('modulo','<>','830A')->whereDate('updated_at',$actual)->sum('op_presencia');
            $op_presencia2_dia_plantaI = Team_modulo::where('planta','IntimarkI')->where('modulo','830A')->whereDate('updated_at',$actual)->sum('op_presencia');

            $pxhrs_dia_plantaI = Team_modulo::where('planta','IntimarkI')->where('modulo','<>','830A')->whereDate('updated_at',$actual)->sum('pxhrs');
            $capacitacion_dia_plantaI = Team_modulo::where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('capacitacion');
            $utility_dia_plantaI = Team_modulo::where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('utility');
            $sam_dia_plantaI = Team_Modulo::where('planta','IntimarkI')->where('modulo','<>','830A')->whereDate('updated_at',$actual)->sum('sam');
          //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
            $cantidad_1_dia_plantaI = Team_Modulo::where('planta','IntimarkI')->select(DB::raw('sum(piezas_meta * sam) as total'))->where('modulo','<>','830A')->whereDate('updated_at',$actual)->get();

            $min_x_producir_1_dia_plantaI = $cantidad_1_dia_plantaI[0]->total*1.02;
            $min_x_producir_2_dia_plantaI = ($op_presencia2_dia_plantaI * $tiempo_desocupacion)*0.98*$efic_dia_830A;

            $min_x_producir_dia_plantaI = $min_x_producir_1_dia_plantaI + $min_x_producir_2_dia_plantaI;

            $min_pres_dia_plantaI = ($op_presencia1_dia_plantaI+$op_presencia2_dia_plantaI)* $tiempo_desocupacion;
            $min_pres_netos_dia_plantaI = ($min_pres_dia_plantaI - ($pxhrs_dia_plantaI+$capacitacion_dia_plantaI)) + $utility_dia_plantaI;
  //dd(   $op_presencia1_dia_plantaI + $op_presencia2_dia_plantaI);

            if($min_pres_netos_dia_plantaI==0){
                $eficiencia_dia_plantaI = $eficiencia_plantaI_07;
            }else{
                $eficiencia_dia_plantaI = $eficiencia_plantaI_07;
               //$eficiencia_dia_plantaI = ($min_x_producir_dia_plantaI/$min_pres_netos_dia_plantaI)*100; //* promedio del dia
            }

            //$cantidad_plantaII = Formato_P07::where('planta','Intimark II')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
            $cantidad_plantaII =  Team_Modulo::where('planta','IntimarkII')->where('Modulo','<>','831A')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
            $cantidad_plantaII = ($cantidad_plantaII/$horas_laboradas )*$valor_hora;
/*
            $op_presencia1_plantaII = Team_modulo::where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->sum('op_presencia');
            $op_presencia2_plantaII = Team_modulo::where('planta','IntimarkII')->where('modulo','831A')->sum('op_presencia');

            $pxhrs_plantaII = Team_modulo::where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->sum('pxhrs');
            $capacitacion_plantaII = Team_modulo::where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->sum('capacitacion');
            $utility_plantaII = Team_modulo::where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->sum('utility');
            $sam_plantaII = Team_Modulo::where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
            $cantidad_1_plantaII = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->get();

            $min_x_producir_1_plantaII = $cantidad_1_plantaII[0]->total*1.02;
            $min_x_producir_2_plantaII = ($op_presencia2_plantaII * $tiempo_desocupacion)*$efic_dia_830A;

            $min_x_producir_plantaII = $min_x_producir_1_plantaII + $min_x_producir_2_plantaII;

            $min_pres_plantaII = ($op_presencia1_plantaII+$op_presencia2_plantaII)* $tiempo_desocupacion;
            $min_pres_plantaII = Team_Modulo::where('planta','IntimarkII')->where('modulo','<>','831A')->where('modulo','<>','804A')->sum('min_presencia');

            $min_pres_netos_plantaII = $min_pres_plantaII - (($pxhrs_plantaII+$capacitacion_plantaII) + $utility_plantaII); */

            $proyeccion_min_plantaII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
            ->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
            $min_pres_netos_plantaII= Team_Modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('min_presencia_netos');


            if($min_pres_netos_plantaII == 0){
                $eficiencia_plantaII = 0;
            }else{
                $eficiencia_plantaII = ($proyeccion_min_plantaII / $min_pres_netos_plantaII)*100;
            }


            if($cantidad_plantaII == 0){
                $cantidad_plantaII = Formato_P07::where('planta','IntimarkII')->where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
                $eficiencia_plantaII = Formato_P07::where('planta','IntimarkII')->where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);
            }
            $eficiencia_plantaII_07 = Formato_P07::where('planta','IntimarkII')->where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);


            $op_presencia1_dia_plantaII = Team_modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('op_presencia');
     //       $op_presencia2_dia_plantaII = Team_modulo::where('planta','IntimarkII')->where('modulo','830A')->sum('op_presencia');

            $pxhrs_dia_plantaII = Team_modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('pxhrs');
            $capacitacion_dia_plantaII = Team_modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('capacitacion');
            $utility_dia_plantaII = Team_modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('utility');
            $sam_dia_plantaII = Team_Modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('sam');
          //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
            $cantidad_1_dia_plantaII = Team_Modulo::where('planta','IntimarkII')->where('Modulo','<>','831A')->whereDate('updated_at',$actual)->select(DB::raw('sum(piezas_meta * sam) as total'))->get();


            //$min_x_producir_1_dia_plantaII = $cantidad_1_dia_plantaII[0]->total*1.02;

            $min_x_producir_dia_plantaII = Team_Modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('min_x_producir');

            $min_pres_dia_plantaII = ($op_presencia1_dia_plantaII)* $tiempo_desocupacion;
          //  $min_pres_netos_dia_plantaII = ($min_pres_dia_plantaII - ($pxhrs_dia_plantaII+$capacitacion_dia_plantaII)) + $utility_dia_plantaII;
            $min_pres_netos_dia_plantaII= Team_Modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('min_presencia_netos');
//dd(    $min_pres_netos_dia_plantaII);

            if($min_pres_netos_dia_plantaII==0){
                $eficiencia_dia_plantaII = $eficiencia_plantaII_07;
            }else{
              //$eficiencia_dia_plantaII = ($min_x_producir_dia_plantaII/$min_pres_netos_dia_plantaII)*100; //* promedio del dia
               $eficiencia_dia_plantaII = $eficiencia_plantaII_07;
            }
//dd($op_presencia1_dia_plantaII);

          //  $eficiencia_plantaI = Formato_P07::where('planta','Intimark I')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
           // $eficiencia_plantaII = Formato_P07::where('planta','Intimark II')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);

/************************** por cliente ************* */
        $clientes = Formato_P07::select('cliente')->groupby('cliente')->get();

        $cantidad_VS =  Team_Modulo::where('cliente','VS')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
        $cantidad_VS = ($cantidad_VS/$horas_laboradas )*$valor_hora;
//dd($valor_hora);
        $cantidad_CHICOS = Team_Modulo::where('cliente','CHICOS')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_CHICOS = ($cantidad_CHICOS/$horas_laboradas )*$valor_hora;

        $cantidad_BN3 = Team_Modulo::where('cliente','BN3')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_BN3 = ($cantidad_BN3/$horas_laboradas )*$valor_hora;

        $cantidad_NU = Team_Modulo::where('cliente','NUUDS')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_NU = ($cantidad_NU/$horas_laboradas )*$valor_hora;

        $cantidad_MARENA = Team_Modulo::where('cliente','MARENA')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_MARENA = ($cantidad_MARENA/$horas_laboradas )*$valor_hora;

        $cantidad_PACIFIC = Team_Modulo::where('cliente','LEQ')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_PACIFIC = ($cantidad_PACIFIC/$horas_laboradas )*$valor_hora;

        $cantidad_BELL = Team_Modulo::where('cliente','BELL')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_BELL = ($cantidad_BELL/$horas_laboradas )*$valor_hora;

        $cantidad_WP = Team_Modulo::where('cliente','WP')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_WP = ($cantidad_WP/$horas_laboradas )*$valor_hora;

        $cantidad_HOOEY = Team_Modulo::where('cliente','HOOEY')->whereDate('updated_at',$actual)->sum('piezas_meta');
        $cantidad_HOOEY = ($cantidad_HOOEY/$horas_laboradas )*$valor_hora;

        //$cantidad_Empaque = Team_Modulo::where('cliente','Empaque')->sum('piezas_meta');
        $cantidad_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_x_producir');

        $cantidad_Empaque = ($cantidad_Empaque/$horas_laboradas )*$valor_hora;


        $op_presencia1_dia_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_VS = Team_modulo::where('cliente','VS')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_VS = Team_Modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_VS = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','VS')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_VS = $cantidad_1_dia_VS[0]->total;
        $min_x_producir_2_dia_VS = ($op_presencia2_dia_VS * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_VS = $min_x_producir_1_dia_VS + $min_x_producir_2_dia_VS;
        $min_x_producir_dia_VS = $min_x_producir_dia_VS*1.02;

        $min_pres_dia_VS = ($op_presencia1_dia_VS+$op_presencia2_dia_VS)* $tiempo_desocupacion;
        $min_pres_netos_dia_VS = $min_pres_dia_VS - (($pxhrs_dia_VS+$capacitacion_dia_VS) + $utility_dia_VS);

       $min_x_producir_dia_VS = Team_Modulo::where('cliente','VS')->where('piezas_meta','<>',0)->where('modulo','<>','830A')->sum('min_x_producir');
       $min_pres_netos_dia_VS = Team_Modulo::where('cliente','VS')->where('piezas_meta','<>',0)->where('modulo','<>','830A')->sum('min_presencia_netos');


        $eficiencia_dia_VS = ($min_x_producir_dia_VS/$min_pres_netos_dia_VS)*100; //* promedio del dia


        $op_presencia1_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_CHICOS = Team_Modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_CHICOS = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','CHICOS')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_CHICOS = $cantidad_1_dia_CHICOS[0]->total;
        $min_x_producir_2_dia_CHICOS = ($op_presencia2_dia_CHICOS * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_CHICOS = $min_x_producir_1_dia_CHICOS + $min_x_producir_2_dia_CHICOS;
        $min_x_producir_dia_CHICOS = $min_x_producir_dia_CHICOS*1.02;

        $min_pres_dia_CHICOS = ($op_presencia1_dia_CHICOS+$op_presencia2_dia_CHICOS)* $tiempo_desocupacion;
        $min_pres_netos_dia_CHICOS = ($min_pres_dia_CHICOS - ($pxhrs_dia_CHICOS+$capacitacion_dia_CHICOS)) + $utility_dia_CHICOS;


        $min_x_producir_dia_CHICOS = Team_Modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('min_x_producir');
        $min_pres_netos_dia_CHICOS = Team_Modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('min_presencia_netos');

        $eficiencia_dia_CHICOS = ($min_x_producir_dia_CHICOS/$min_pres_netos_dia_CHICOS)*100; //* promedio del dia

        $op_presencia1_dia_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_BN3 = Team_Modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_BN3 = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','BN3')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_BN3 = $cantidad_1_dia_BN3[0]->total;
        $min_x_producir_2_dia_BN3 = ($op_presencia2_dia_BN3 * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_BN3 = $min_x_producir_1_dia_BN3 + $min_x_producir_2_dia_BN3;
        $min_x_producir_dia_BN3 = $min_x_producir_dia_BN3*1.02;

        $min_pres_dia_BN3 = ($op_presencia1_dia_BN3+$op_presencia2_dia_BN3)* $tiempo_desocupacion;
        $min_pres_netos_dia_BN3 = ($min_pres_dia_BN3 - ($pxhrs_dia_BN3+$capacitacion_dia_BN3)) + $utility_dia_BN3;


        $min_x_producir_dia_BN3 = Team_Modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('min_x_producir');
        $min_pres_netos_dia_BN3 = Team_Modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_BN3 != 0)
            $eficiencia_dia_BN3 = ($min_x_producir_dia_BN3/$min_pres_netos_dia_BN3)*100; //* promedio del dia
        else
            $eficiencia_dia_BN3 = 0;

        $op_presencia1_dia_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_NU = Team_Modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_NU = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','NUUDS')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_NU = $cantidad_1_dia_NU[0]->total;
        $min_x_producir_2_dia_NU = ($op_presencia2_dia_NU * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_NU = $min_x_producir_1_dia_NU + $min_x_producir_2_dia_NU;
        $min_x_producir_dia_NU = $min_x_producir_dia_NU*1.02;

        $min_pres_dia_NU = ($op_presencia1_dia_NU+$op_presencia2_dia_NU)* $tiempo_desocupacion;
        $min_pres_netos_dia_NU = ($min_pres_dia_NU - ($pxhrs_dia_NU+$capacitacion_dia_NU)) + $utility_dia_NU;


        $min_x_producir_dia_NU = Team_Modulo::where('cliente','NU')->where('modulo','<>','830A')->sum('min_x_producir');
        $min_pres_netos_dia_NU = Team_Modulo::where('cliente','NU')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_NU==0){
            $eficiencia_dia_NU=0;
        }else{
            $eficiencia_dia_NU = ($min_x_producir_dia_NU/$min_pres_netos_dia_NU)*100; //* promedio del dia
        }

        $op_presencia1_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_MARENA = Team_Modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_MARENA = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','MARENA')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_MARENA = $cantidad_1_dia_MARENA[0]->total;
        $min_x_producir_2_dia_MARENA = ($op_presencia2_dia_MARENA * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_MARENA = $min_x_producir_1_dia_MARENA + $min_x_producir_2_dia_MARENA;
        $min_x_producir_dia_MARENA = $min_x_producir_dia_MARENA*1.02;

        $min_pres_dia_MARENA = ($op_presencia1_dia_MARENA+$op_presencia2_dia_MARENA)* $tiempo_desocupacion;
        $min_pres_netos_dia_MARENA = ($min_pres_dia_MARENA - ($pxhrs_dia_MARENA+$capacitacion_dia_MARENA)) + $utility_dia_MARENA;


        $min_x_producir_dia_MARENA = Team_Modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('min_x_producir');
        $min_pres_netos_dia_MARENA = Team_Modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_MARENA==0){
            $eficiencia_dia_MARENA=0;
        }else{
            $eficiencia_dia_MARENA = ($min_x_producir_dia_MARENA/$min_pres_netos_dia_MARENA)*100; //* promedio del dia
        }

        $op_presencia1_dia_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_PACIFIC = Team_Modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_PACIFIC = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','LEQ')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_PACIFIC = $cantidad_1_dia_PACIFIC[0]->total;
        $min_x_producir_2_dia_PACIFIC = ($op_presencia2_dia_PACIFIC * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_PACIFIC = $min_x_producir_1_dia_PACIFIC + $min_x_producir_2_dia_PACIFIC;
        $min_x_producir_dia_PACIFIC = $min_x_producir_dia_PACIFIC*1.02;

        $min_pres_dia_PACIFIC = ($op_presencia1_dia_PACIFIC+$op_presencia2_dia_PACIFIC)* $tiempo_desocupacion;
        $min_pres_netos_dia_PACIFIC = ($min_pres_dia_PACIFIC - ($pxhrs_dia_PACIFIC+$capacitacion_dia_PACIFIC)) + $utility_dia_PACIFIC;


       $min_x_producir_dia_PACIFIC = Team_Modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('min_x_producir');
       $min_pres_netos_dia_PACIFIC = Team_Modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_PACIFIC==0){
            $eficiencia_dia_PACIFIC=0;
        }else{
            $eficiencia_dia_PACIFIC = ($min_x_producir_dia_PACIFIC/$min_pres_netos_dia_PACIFIC)*100; //* promedio del dia
        }

        $op_presencia1_dia_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_BELL = Team_modulo::where('cliente','BELL')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_BELL = Team_Modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_BELL = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','BELL')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_BELL = $cantidad_1_dia_BELL[0]->total;
        $min_x_producir_2_dia_BELL = ($op_presencia2_dia_BELL * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_BELL = $min_x_producir_1_dia_BELL + $min_x_producir_2_dia_BELL;
        $min_x_producir_dia_BELL = $min_x_producir_dia_BELL*1.02;

        $min_pres_dia_BELL = ($op_presencia1_dia_BELL+$op_presencia2_dia_BELL)* $tiempo_desocupacion;
        $min_pres_netos_dia_BELL = ($min_pres_dia_BELL - ($pxhrs_dia_BELL+$capacitacion_dia_BELL)) + $utility_dia_BELL;


       $min_x_producir_dia_BELL = Team_Modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('min_x_producir');
       $min_pres_netos_dia_BELL = Team_Modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_BELL==0){
            $eficiencia_dia_BELL=0;
        }else{
            $eficiencia_dia_BELL = ($min_x_producir_dia_BELL/$min_pres_netos_dia_BELL)*100; //* promedio del dia
        }

        $op_presencia1_dia_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_WP = Team_modulo::where('cliente','WP')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_WP = Team_Modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_WP = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','WP')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_WP = $cantidad_1_dia_WP[0]->total;
        $min_x_producir_2_dia_WP = ($op_presencia2_dia_WP * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_WP = $min_x_producir_1_dia_WP + $min_x_producir_2_dia_WP;
        $min_x_producir_dia_WP = $min_x_producir_dia_WP*1.02;

        $min_pres_dia_WP = ($op_presencia1_dia_WP+$op_presencia2_dia_WP)* $tiempo_desocupacion;
        $min_pres_netos_dia_WP = ($min_pres_dia_WP - ($pxhrs_dia_WP+$capacitacion_dia_WP)) + $utility_dia_WP;


       $min_x_producir_dia_WP = Team_Modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('min_x_producir');
       $min_pres_netos_dia_WP = Team_Modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_WP!=0)
            $eficiencia_dia_WP = ($min_x_producir_dia_WP/$min_pres_netos_dia_WP)*100; //* promedio del dia
        else
        $eficiencia_dia_WP = 0;


        $op_presencia1_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_HOOEY = Team_Modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_dia_HOOEY = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','HOOEY')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_HOOEY = $cantidad_1_dia_HOOEY[0]->total;
        $min_x_producir_2_dia_HOOEY = ($op_presencia2_dia_HOOEY * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_HOOEY = $min_x_producir_1_dia_HOOEY + $min_x_producir_2_dia_HOOEY;
        $min_x_producir_dia_HOOEY = $min_x_producir_dia_HOOEY*1.02;

        $min_pres_dia_HOOEY = ($op_presencia1_dia_HOOEY+$op_presencia2_dia_HOOEY)* $tiempo_desocupacion;
        $min_pres_netos_dia_HOOEY = ($min_pres_dia_HOOEY - ($pxhrs_dia_HOOEY+$capacitacion_dia_HOOEY)) + $utility_dia_HOOEY;


       $min_x_producir_dia_HOOEY = Team_Modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('min_x_producir');
       $min_pres_netos_dia_HOOEY = Team_Modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_dia_HOOEY!=0)
            $eficiencia_dia_HOOEY = ($min_x_producir_dia_HOOEY/$min_pres_netos_dia_HOOEY)*100; //* promedio del dia
        else
        $eficiencia_dia_HOOEY = 0;


        $op_presencia1_dia_Empaque = Team_modulo::where('cliente','Empaque')->sum('op_presencia');

        $pxhrs_dia_Empaque = Team_modulo::where('cliente','Empaque')->sum('pxhrs');
        $capacitacion_dia_Empaque = Team_modulo::where('cliente','Empaque')->sum('capacitacion');
        $utility_dia_Empaque = Team_modulo::where('cliente','Empaque')->sum('utility');
        $sam_dia_Empaque = Team_Modulo::where('cliente','Empaque')->sum('sam');
      //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
       // $cantidad_1_dia_Empaque = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','Empaque')->get();

       // $min_x_producir_1_dia_Empaque = $cantidad_1_dia_Empaque[0]->total;
       // $min_x_producir_2_dia_WP = ($op_presencia2_dia_WP * $tiempo_desocupacion)*$efic_dia_830A;

        //$min_x_producir_dia_Empaque = $min_x_producir_1_dia_Empaque ;
       // $min_x_producir_dia_Empaque = $min_x_producir_dia_Empaque*1.02;

       $min_x_producir_dia_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_x_producir');

       $min_pres_dia_Empaque = ($op_presencia1_dia_Empaque)* $tiempo_desocupacion;
        $min_pres_netos_dia_Empaque = ($min_pres_dia_Empaque - ($pxhrs_dia_Empaque+$capacitacion_dia_Empaque)) + $utility_dia_Empaque;


       $min_x_producir_dia_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_x_producir');
       $min_pres_netos_dia_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_presencia_netos');

        if($min_pres_netos_dia_Empaque!=0)
           $eficiencia_dia_Empaque = ($min_x_producir_dia_Empaque/$min_pres_netos_dia_Empaque)*100; //* promedio del dia
        else
            $eficiencia_dia_Empaque = 0;


        //* promedio del dia
        //$eficiencia_BN3 = Formato_P07::where('cliente','VS  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        //$eficiencia_CHICOS = Formato_P07::where('cliente','Chicos  PI  1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        //$eficiencia_BN3 = Formato_P07::where('cliente','BN3  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
      // $eficiencia_NU = Formato_P07::where('cliente','NU PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
       // $eficiencia_MARENA = Formato_P07::where('cliente','Marena 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        //$eficiencia_PACIFIC = Formato_P07::where('cliente','Pacific  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
      // $eficiencia_BELL = Formato_P07::where('cliente','Pacific  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $op_presencia1_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_VS = Team_modulo::where('cliente','VS')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_VS = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('utility');
        $sam_VS = Team_Modulo::where('cliente','VS')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_VS = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','VS')->where('modulo','<>','830A')->get();

        $min_x_producir_1_VS = $cantidad_1_VS[0]->total;
        $min_x_producir_2_VS = ($op_presencia2_VS * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_VS = $min_x_producir_1_VS + $min_x_producir_2_VS;
        $min_x_producir_VS = $min_x_producir_VS*1.02;

        $min_pres_VS = ($op_presencia1_VS+$op_presencia2_VS)* $tiempo_desocupacion;
        $min_pres_netos_VS = ($min_pres_VS - ($pxhrs_VS+$capacitacion_VS)) + $utility_VS;

        $proyeccion_min_VS = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_VS = Team_Modulo::where('cliente','VS')->where('modulo','<>','830A')->where('piezas_meta','<>',0)->sum('min_presencia_netos');

        if($min_pres_netos_VS == 0){
            $eficiencia_VS = 0;
        }else{
            $eficiencia_VS = ($proyeccion_min_VS / $min_pres_netos_VS)*100;
        }

        $op_presencia1_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_CHICOS = Team_modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('utility');
        $sam_CHICOS = Team_Modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_CHICOS = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','CHICOS')->where('modulo','<>','830A')->get();

        $min_x_producir_1_CHICOS = $cantidad_1_CHICOS[0]->total;
        $min_x_producir_2_CHICOS = ($op_presencia2_CHICOS * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_CHICOS = $min_x_producir_1_CHICOS + $min_x_producir_2_CHICOS;

      //  $min_x_producir_CHICOS =  $min_x_producir_CHICOS*1.02;

        $min_pres_CHICOS = ($op_presencia1_CHICOS+$op_presencia2_CHICOS)* $tiempo_desocupacion;
        $min_pres_netos_CHICOS = ($min_pres_CHICOS - ($pxhrs_CHICOS+$capacitacion_CHICOS)) + $utility_CHICOS;

        $proyeccion_min_CHICOS = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','CHICOS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_CHICOS = Team_Modulo::where('cliente','CHICOS')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_CHICOS == 0){
            $eficiencia_CHICOS = 0;
        }else{
            $eficiencia_CHICOS = ($proyeccion_min_CHICOS / $min_pres_netos_CHICOS)*100;
        }

        $op_presencia1_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_BN3 = Team_modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('utility');
        $sam_BN3 = Team_Modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_BN3 = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','BN3')->where('modulo','<>','830A')->get();

        $min_x_producir_1_BN3 = $cantidad_1_BN3[0]->total;
        $min_x_producir_2_BN3 = ($op_presencia2_BN3 * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_BN3 = $min_x_producir_1_BN3 + $min_x_producir_2_BN3;
        $min_x_producir_BN3  = $min_x_producir_BN3 *1.02;

        $min_pres_BN3 = ($op_presencia1_BN3+$op_presencia2_BN3)* $tiempo_desocupacion;
        $min_pres_netos_BN3 = ($min_pres_BN3 - ($pxhrs_BN3+$capacitacion_BN3)) + $utility_BN3;

        $proyeccion_min_BN3 = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','BN3')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_BN3 = Team_Modulo::where('cliente','BN3')->where('modulo','<>','830A')->sum('min_presencia_netos');


        if($min_pres_netos_BN3 == 0){
            $eficiencia_BN3 = 0;
        }else{
            $eficiencia_BN3 = ($proyeccion_min_BN3 / $min_pres_netos_BN3)*100;
        }

        $op_presencia1_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_NU = Team_modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('utility');
        $sam_NU = Team_Modulo::where('cliente','NUUDS')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_NU = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','NUUDS')->where('modulo','<>','830A')->get();

        $min_x_producir_1_NU = $cantidad_1_NU[0]->total;
        $min_x_producir_2_NU = ($op_presencia2_NU * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_NU = $min_x_producir_1_NU + $min_x_producir_2_NU;
        $min_x_producir_NU =  $min_x_producir_NU*1.02;

        $min_pres_NU = ($op_presencia1_NU+$op_presencia2_NU)* $tiempo_desocupacion;
        $min_pres_netos_NU = ($min_pres_NU - ($pxhrs_NU+$capacitacion_NU)) + $utility_NU;

        $proyeccion_min_NU = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','NU')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_NU = Team_Modulo::where('cliente','NU')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_NU == 0){
            $eficiencia_NU = 0;
        }else{
            $eficiencia_NU = ($proyeccion_min_NU / $min_pres_netos_NU)*100;
        }

        $op_presencia1_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_MARENA = Team_modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('utility');
        $sam_MARENA = Team_Modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_MARENA = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','MARENA')->where('modulo','<>','830A')->get();

        $min_x_producir_1_MARENA = $cantidad_1_MARENA[0]->total;
        $min_x_producir_2_MARENA = ($op_presencia2_MARENA * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_MARENA = $min_x_producir_1_MARENA + $min_x_producir_2_MARENA;
        $min_x_producir_MARENA  =   $min_x_producir_MARENA *1.02;

        $min_pres_MARENA = ($op_presencia1_MARENA+$op_presencia2_MARENA)* $tiempo_desocupacion;
        $min_pres_netos_MARENA = ($min_pres_MARENA - ($pxhrs_MARENA+$capacitacion_MARENA)) + $utility_MARENA;

        $proyeccion_min_MARENA = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','MARENA')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_MARENA = Team_Modulo::where('cliente','MARENA')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_MARENA == 0){
            $eficiencia_MARENA = 0;
        }else{
            $eficiencia_MARENA = ($proyeccion_min_MARENA / $min_pres_netos_MARENA)*100;
        }

        $op_presencia1_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_PACIFIC = Team_modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('utility');
        $sam_PACIFIC = Team_Modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_PACIFIC = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','LEQ')->where('modulo','<>','830A')->get();

        $min_x_producir_1_PACIFIC = $cantidad_1_PACIFIC[0]->total;
        $min_x_producir_2_PACIFIC = ($op_presencia2_PACIFIC * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_PACIFIC = $min_x_producir_1_PACIFIC + $min_x_producir_2_PACIFIC;
        $min_x_producir_PACIFIC =   $min_x_producir_PACIFIC*1.02;

        $min_pres_PACIFIC = ($op_presencia1_PACIFIC+$op_presencia2_PACIFIC)* $tiempo_desocupacion;
        $min_pres_netos_PACIFIC = ($min_pres_PACIFIC - ($pxhrs_PACIFIC+$capacitacion_PACIFIC)) + $utility_PACIFIC;

        $proyeccion_min_PACIFIC = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','LEQ')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_PACIFIC = Team_Modulo::where('cliente','LEQ')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_PACIFIC == 0){
            $eficiencia_PACIFIC = 0;
        }else{
            $eficiencia_PACIFIC = ($proyeccion_min_PACIFIC / $min_pres_netos_PACIFIC)*100;
        }

        $op_presencia1_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_BELL = Team_modulo::where('cliente','BELL')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_BELL = Team_modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('utility');
        $sam_BELL = Team_Modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_BELL = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','BELL')->where('modulo','<>','830A')->get();

        $min_x_producir_1_BELL = $cantidad_1_BELL[0]->total;
        $min_x_producir_2_BELL = ($op_presencia2_BELL * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_BELL = $min_x_producir_1_BELL + $min_x_producir_2_BELL;
        $min_x_producir_BELL = $min_x_producir_BELL*1.02;

        $min_pres_BELL = ($op_presencia1_BELL+$op_presencia2_BELL)* $tiempo_desocupacion;
        $min_pres_netos_BELL = ($min_pres_BELL - ($pxhrs_BELL+$capacitacion_BELL)) + $utility_BELL;

        $proyeccion_min_BELL = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','BELL')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_BELL = Team_Modulo::where('cliente','BELL')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_BELL == 0){
            $eficiencia_BELL = 0;
        }else{
            $eficiencia_BELL = ($proyeccion_min_BELL / $min_pres_netos_BELL)*100;
        }

        $op_presencia1_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_WP = Team_modulo::where('cliente','WP')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_WP = Team_modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('utility');
        $sam_WP = Team_Modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_WP = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','WP')->where('modulo','<>','830A')->get();

        $min_x_producir_1_WP = $cantidad_1_WP[0]->total;
        $min_x_producir_2_WP = ($op_presencia2_WP * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_WP = $min_x_producir_1_WP + $min_x_producir_2_BELL;
        $min_x_producir_WP= $min_x_producir_WP*1.02;

        $min_pres_WP = ($op_presencia1_WP+$op_presencia2_WP)* $tiempo_desocupacion;
        $min_pres_netos_WP = ($min_pres_WP - ($pxhrs_WP+$capacitacion_WP)) + $utility_WP;

        $proyeccion_min_WP = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','WP')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_WP = Team_Modulo::where('cliente','WP')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_WP == 0){
            $eficiencia_WP = 0;
        }else{
            $eficiencia_WP = ($proyeccion_min_WP / $min_pres_netos_WP)*100;
        }



        $op_presencia1_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_HOOEY = Team_modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('utility');
        $sam_HOOEY = Team_Modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
        $cantidad_1_HOOEY = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','HOOEY')->where('modulo','<>','830A')->get();

        $min_x_producir_1_HOOEY = $cantidad_1_HOOEY[0]->total;
        $min_x_producir_2_HOOEY = ($op_presencia2_HOOEY * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_HOOEY = $min_x_producir_1_HOOEY + $min_x_producir_2_HOOEY;
        $min_x_producir_HOOEY= $min_x_producir_HOOEY*1.02;

        $min_pres_HOOEY = ($op_presencia1_HOOEY+$op_presencia2_HOOEY)* $tiempo_desocupacion;
        $min_pres_netos_HOOEY = ($min_pres_HOOEY - ($pxhrs_HOOEY+$capacitacion_HOOEY)) + $utility_HOOEY;

        $proyeccion_min_HOOEY = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','HOOEY')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_HOOEY = Team_Modulo::where('cliente','HOOEY')->where('modulo','<>','830A')->sum('min_presencia_netos');

        if($min_pres_netos_HOOEY == 0){
            $eficiencia_HOOEY = 0;
        }else{
            $eficiencia_HOOEY = ($proyeccion_min_HOOEY / $min_pres_netos_HOOEY)*100;
        }

        $op_presencia1_Empaque = Team_modulo::where('cliente','Empaque')->sum('op_presencia');

        $pxhrs_Empaque = Team_modulo::where('cliente','Empaque')->sum('pxhrs');
        $capacitacion_Empaque = Team_modulo::where('cliente','Empaque')->sum('capacitacion');
        $utility_Empaque = Team_modulo::where('cliente','Empaque')->sum('utility');
        $sam_Empaque = Team_Modulo::where('cliente','Empaque')->sum('sam');
        //  $cantidad_1 = Team_Modulo::where('modulo','<>','830A')->sum('piezas_meta');
      //  $cantidad_1_Empaque = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','Empaque')->get();

        //$min_x_producir_1_Empaque = $cantidad_1_Empaque[0]->total;
       // $min_x_producir_2_WP = ($op_presencia2_WP * $tiempo_desocupacion)*$efic_dia_830A;

       // $min_x_producir_Empaque = $min_x_producir_1_Empaque;
       // $min_x_producir_Empaque= $min_x_producir_Empaque*1.02;

        $min_x_producir_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_x_producir');

        $min_pres_Empaque = ($op_presencia1_Empaque)* $tiempo_desocupacion;
        $min_pres_netos_Empaque = ($min_pres_Empaque - ($pxhrs_Empaque+$capacitacion_Empaque)) + $utility_Empaque;

        /*$proyeccion_min_Empaque = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','Empaque')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');*/

        $proyeccion_min_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
        ->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at','<=',$hora)->sum('cantidad');

        $proyeccion_min_EmpaqueII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','Empaque')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');

        $min_pres_netos_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_presencia_netos');

        if($min_pres_netos_Empaque == 0){
            $eficiencia_Empaque = 0;
        }else{
            $eficiencia_Empaque = (($proyeccion_min_EmpaqueI+$proyeccion_min_EmpaqueII) / $min_pres_netos_Empaque)*100;
        }
/**********************acumulado******************* */
        $cantidad_acum=0;
      /*  for($i=1;$i<=$dia_semana;$i++){
            $dia = Formato_P07::sum('cantidad_d'.$i);
            $cantidad_acum = $cantidad_acum +  $dia;
        }*/
        /*********** x modulos*********** */
        $modulos = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->select('planeacion_diaria.modulo','team_modulo.planta', 'planeacion_diaria.team_leader', 'planeacion_diaria.piezas', 'planeacion_diaria.min_producidos', 'planeacion_diaria.proyeccion_minutos','planeacion_diaria.efic','team_modulo.piezas_meta','team_modulo.eficiencia_dia')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',$hora)
        ->groupby('planeacion_diaria.modulo','planeacion_diaria.team_leader', 'planeacion_diaria.piezas', 'planeacion_diaria.min_producidos', 'planeacion_diaria.proyeccion_minutos','planeacion_diaria.efic','team_modulo.piezas_meta','team_modulo.eficiencia_dia','team_modulo.planta')
        ->get();

        $modulo_meta = Formato_P07::select("modulo", \DB::raw("SUM(cantidad_d".$dia_semana.") as cantidad") )
        ->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)
        ->groupBy("modulo")
        ->get();
        /*********************** x team leader ******************** */
/*
        $team_leader = Plan_diar2::select('team_leader', \DB::raw("SUM(piezas) as piezas"))
        ->whereDate('updated_at',$actual)
        ->whereTime('updated_at',$hora)
        ->groupby('team_leader')
        ->get(); */

        $team_leader = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
        ->select('planeacion_diaria.team_leader','team_modulo.planta', \DB::raw("SUM(planeacion_diaria.piezas) as piezas"), \DB::raw("SUM(planeacion_diaria.min_producidos) as min_producidos"),\DB::raw("SUM(planeacion_diaria.proyeccion_minutos) as proyeccion_minutos"), \DB::raw("AVG(planeacion_diaria.efic) as efic"), \DB::raw("SUM(team_modulo.piezas_meta) as piezas_meta"), \DB::raw("AVG(team_modulo.eficiencia_dia) as eficiencia_dia"))
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',$hora)
        ->groupby('planeacion_diaria.team_leader','team_modulo.planta')
        ->get();

        $plantas = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
        ->select('team_modulo.planta', \DB::raw("SUM(planeacion_diaria.piezas) as piezas"), \DB::raw("SUM(planeacion_diaria.min_producidos) as min_producidos"),\DB::raw("SUM(planeacion_diaria.proyeccion_minutos) as proyeccion_minutos"), \DB::raw("AVG(planeacion_diaria.efic) as efic"), \DB::raw("SUM(team_modulo.piezas_meta) as piezas_meta"), \DB::raw("AVG(team_modulo.eficiencia_dia) as eficiencia_dia",'team_modulo.planta'))
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',$hora)
        ->groupby('team_modulo.planta')
        ->get();


        /*$x_team = Team_Modulo::join('cat_team_leader','team_modulo.team_leader','cat_team_leader.id')
        ->join('cat_modulos','team_modulo.modulo','cat_modulos.id')
        ->join('formato_p07','formato_p07.modulo','cat_modulos.modulo')
        ->select('cat_team_leader.team_leader','cat_modulos.modulo', \DB::raw("SUM(formato_p07.cantidad_d1+formato_p07.cantidad_d2+formato_p07.cantidad_d3+formato_p07.cantidad_d4+formato_p07.cantidad_d5) as cantidad"), \DB::raw("(SUM(eficiencia_d1+eficiencia_d2+eficiencia_d3+eficiencia_d4+eficiencia_d5))/5 as eficiencia"))
        ->groupby('team_leader','cat_modulos.modulo')
        ->get();*/


        /******************planeacion ************* */

        $planeacion = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.team_leader','planeacion_diaria.Modulo','team_modulo.planta','team_modulo.cliente',\DB::raw("SUM(piezas) as piezas"))
        ->whereDate('planeacion_diaria.updated_at',$actual)
      /*  ->whereTime('planeacion_diaria.updated_at',$hora)*/
        ->groupby('planeacion_diaria.modulo','planeacion_diaria.team_leader','team_modulo.cliente','team_modulo.planta')
        ->orderby('planeacion_diaria.team_leader','asc')
        ->orderby('planeacion_diaria.modulo','asc')
        ->get();

        $planeacion_09 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"09:00")
        ->get();
//dd($planeacion_09);
        $planeacion_10 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"10:00")
        ->get();

        $planeacion_11 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"11:00")
        ->get();

        $planeacion_12 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"12:00")
        ->get();

        $planeacion_13 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"13:00")
        ->get();

        $planeacion_14 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"14:00")
        ->get();

        $planeacion_15 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"15:00")
        ->get();

        $planeacion_16 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"16:00")
        ->get();

        $planeacion_17 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"17:00")
        ->get();

        $planeacion_18 = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
        ->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',"18:00")
        ->get();

        /*$planeacion_meta = Formato_P07::select("modulo", "cantidad_d2","eficiencia_d2")
        ->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)
        ->groupBy("modulo",'cantidad_d2','eficiencia_d2')
        ->get();*/

        $planeacion_meta = Team_modulo::get();


       /**************planeacion semanal ********** */
       $cantidad_diaria = Plan_diar2::whereDate('updated_at',$actual)->whereTime('updated_at',$hora)->sum('piezas');
       $hora_actualiza = Plan_diar2::whereDate('updated_at',$actual)->select('updated_at')->orderby('updated_at','desc')->first();

       $ultima_actualizacionI = Plan_diar2::join('team_modulo','Team_modulo.Modulo','planeacion_diaria.Modulo')
       ->where('team_modulo.planta','IntimarkI')
       ->whereDate('planeacion_diaria.updated_at',$actual)
       ->select('planeacion_diaria.updated_at')->orderby('planeacion_diaria.updated_at','desc')->first();

       if($ultima_actualizacionI)
            $hora_actualizacionI =date("H:i", strtotime($ultima_actualizacionI->updated_at));
       else
            $hora_actualizacionI = "N/D";


       $ultima_actualizacionII = Plan_diar2::join('team_modulo','Team_modulo.Modulo','planeacion_diaria.Modulo')
       ->where('team_modulo.planta','IntimarkII')
       ->whereDate('planeacion_diaria.updated_at',$actual)
       ->select('planeacion_diaria.updated_at')->orderby('planeacion_diaria.updated_at','desc')->first();



       if($ultima_actualizacionII)
            $hora_actualizacionII =date("H:i", strtotime($ultima_actualizacionII->updated_at));
         else
            $hora_actualizacionII = "N/D";

       $proyeccion_min = Plan_diar2::whereDate('updated_at',$actual)->whereTime('updated_at',$hora)->sum('proyeccion_minutos');
       //$eficiencia_semanal = Plan_diar2::whereDate('updated_at',$actual)->where('modulo','=','830A')->whereTime('updated_at',$hora)->select('efic_total')->first();

       if($hora_actualiza){
            $hora_actualizacion =date("H:i", strtotime($hora_actualiza->updated_at));

            if($hora <> $hora_actualizacion){
                $cantidad_diaria = Plan_diar2::whereDate('updated_at',$actual)->whereTime('updated_at',$hora_actualizacion)->sum('piezas');
                $proyeccion_min = Plan_diar2::whereDate('updated_at',$actual)->whereTime('updated_at',$hora_actualizacion)->sum('proyeccion_minutos');
            }
       }else{
            $hora_actualizacion = "N/D";
       }

       $cantidad_semanal = 0;

       for ($i=1;$i<$dia_semana;$i++){
            $dia_anterior = strtotime('-'.$i.' day', strtotime($actual));
            $dia = date('Y-m-d', $dia_anterior);

            $cantidad = ProduccionDiaAnterior::where('fecha_dia',$dia)->sum('producidas');

            if($cantidad > 0){
                $cantidad_semanal = $cantidad_semanal + $cantidad;
            }else{
                $cantidad_semanal = $cantidad_semanal;
            }
       }
       //$cantidad_semanal = $cantidad_diaria;
       $efic_total = ($proyeccion_min / $min_pres_netos_dia)*100;

       $eficiencia_semanal = $efic_total;
      // $eficiencia_semanal = 49.13;
       /*if($eficiencia_diaria )
            $efic_total=$eficiencia_diaria->efic_total;
        else
            $efic_total=0;
*/
       /*************real x planta ************ */

           $real_x_plantaI = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.planta','IntimarkI')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           $real_x_plantaI830 = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.planta','IntimarkI')
           ->where('planeacion_diaria.Modulo','830A')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');


           if($hora <> $hora_actualizacionI){
                $real_x_plantaI = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
                ->where('cat_modulos.planta','IntimarkI')
                ->where('planeacion_diaria.Modulo','<>','830A')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacionI)
                ->sum('piezas');

                $proyeccion_min_plantaI = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                    ->where('planta','IntimarkI')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionI)->sum('proyeccion_minutos');

                if($min_pres_netos_plantaI == 0){
                    $eficiencia_plantaI = 0;
                }else{
                    $eficiencia_plantaI = ($proyeccion_min_plantaI / $min_pres_netos_plantaI)*100;
                }
            }

            $real_x_plantaII = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
            ->where('cat_modulos.planta','IntimarkII')
            ->whereDate('planeacion_diaria.updated_at',$actual)
            ->whereTime('planeacion_diaria.updated_at',$hora)
            ->sum('piezas');

            $real_x_plantaI831 = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
            ->where('cat_modulos.planta','IntimarkII')
            ->where('planeacion_diaria.Modulo','831A')
            ->whereDate('planeacion_diaria.updated_at',$actual)
            ->whereTime('planeacion_diaria.updated_at',$hora)
            ->sum('piezas');


            if($hora <> $hora_actualizacionII){
                $real_x_plantaII = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
                ->where('cat_modulos.planta','IntimarkII')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)
                ->sum('piezas');

                $real_x_plantaI831 = Plan_diar2::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
                ->where('cat_modulos.planta','IntimarkII')
                ->where('planeacion_diaria.Modulo','831A')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)
                ->sum('piezas');

                 $proyeccion_min_plantaII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                     ->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)->sum('proyeccion_minutos');

                 $min_pres_netos_plantaII= Team_Modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('min_presencia_netos');


                 if($min_pres_netos_plantaII == 0){
                     $eficiencia_plantaII = 0;
                 }else{
                     $eficiencia_plantaII = ($proyeccion_min_plantaII / $min_pres_netos_plantaII)*100;
                   //$eficiencia_plantaII = ($min_pres_netos_plantaII / $proyeccion_min_plantaII  )*100;
                 }
             }

             if (($min_pres_netos_plantaII + $min_pres_netos_plantaI) ==0)
                $eficiencia_planta = 0;
             else
                $eficiencia_planta = (($proyeccion_min_plantaII+$proyeccion_min_plantaI) / ($min_pres_netos_plantaII + $min_pres_netos_plantaI))*100;
//dd((($proyeccion_min_plantaII+$proyeccion_min_plantaI) / ($min_pres_netos_plantaII + $min_pres_netos_plantaI))*100);

           /*************real x cliente ************ */
           $real_VS = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','VS')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_VS = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','VS')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_VS = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                $min_pres_netos_VS = Team_Modulo::where('cliente','VS')->sum('min_presencia_netos');


                if($min_pres_netos_VS == 0){
                    $eficiencia_VS = 0;
                }else{
                    $eficiencia_VS = ($proyeccion_min_VS / $min_pres_netos_VS)*100;
                }
            }

           $real_CHICOS = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','CHICOS')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_CHICOS = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','CHICOS')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_CHICOS = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','CHICOS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_CHICOS == 0){
                    $eficiencia_CHICOS = 0;
                }else{
                    $eficiencia_CHICOS = ($proyeccion_min_CHICOS / $min_pres_netos_CHICOS)*100;
                }
           }

           $real_BN3 = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','BN3')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_BN3 = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','BN3')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_BN3 = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','BN3')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_BN3 == 0){
                    $eficiencia_BN3 = 0;
                }else{
                    $eficiencia_BN3 = ($proyeccion_min_BN3 / $min_pres_netos_BN3)*100;
                }
            }

           $real_NU = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','NUUDS')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_NU = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','NUUDS')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_NU = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','NUUDS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_NU == 0){
                    $eficiencia_NU = 0;
                }else{
                    $eficiencia_NU = ($proyeccion_min_NU / $min_pres_netos_NU)*100;
                }
            }

           $real_MARENA = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','MARENA')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_MARENA = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','MARENA')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_MARENA = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','MARENA')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_MARENA == 0){
                    $eficiencia_MARENA = 0;
                }else{
                    $eficiencia_MARENA = ($proyeccion_min_MARENA / $min_pres_netos_MARENA)*100;
                }
           }

           $real_LECOQ = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','LEQ')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_LECOQ = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','LEQ')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_PACIFIC = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','LEQ')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_PACIFIC == 0){
                    $eficiencia_PACIFIC = 0;
                }else{
                    $eficiencia_PACIFIC = ($proyeccion_min_PACIFIC / $min_pres_netos_PACIFIC)*100;
                }
           }

           $real_BELL = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','BELL')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_BELL = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','BELL')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_BELL = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','BELL')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_BELL == 0){
                    $eficiencia_BELL = 0;
                }else{
                    $eficiencia_BELL = ($proyeccion_min_BELL / $min_pres_netos_BELL)*100;
                }
           }

           $real_WP = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','WP')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           if($hora <> $hora_actualizacion){
                $real_WP = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                ->where('team_modulo.cliente','WP')
                ->whereDate('planeacion_diaria.updated_at',$actual)
                ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                ->sum('piezas');

                $proyeccion_min_WP = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','WP')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                if($min_pres_netos_WP == 0){
                    $eficiencia_WP = 0;
                }else{
                    $eficiencia_WP = ($proyeccion_min_WP / $min_pres_netos_WP)*100;
                }
            }


            $real_HOOEY = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
            ->where('team_modulo.cliente','HOOEY')
            ->whereDate('planeacion_diaria.updated_at',$actual)
            ->whereTime('planeacion_diaria.updated_at',$hora)
            ->sum('piezas');

            if($hora <> $hora_actualizacion){
                 $real_HOOEY = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                 ->where('team_modulo.cliente','HOOEY')
                 ->whereDate('planeacion_diaria.updated_at',$actual)
                 ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                 ->sum('piezas');

                 $proyeccion_min_HOOEY = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                 ->where('cliente','HOOEY')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                 if($min_pres_netos_HOOEY == 0){
                     $eficiencia_HOOEY = 0;
                 }else{
                     $eficiencia_HOOEY = ($proyeccion_min_HOOEY / $min_pres_netos_HOOEY)*100;
                 }
             }


          /*  $real_Empaque = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
            ->where('team_modulo.cliente','Empaque')
            ->whereDate('planeacion_diaria.updated_at',$actual)
            ->whereTime('planeacion_diaria.updated_at',$hora)
            //->sum('piezas');
            ->sum('min_producidos'); */

            $real_Empaque = Tickets_empaque::join('team_modulo','team_modulo.modulo','ticket_empaque.modulo') ///resultados por tickets
            ->where('team_modulo.cliente','Empaque')
            ->whereDate('ticket_empaque.updated_at',$actual)
            ->whereTime('ticket_empaque.updated_at','<=',$hora)
            //->sum('piezas');
            ->sum('cantidad');

            if($hora <> $hora_actualizacion){
           /*      $real_Empaque = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                 ->where('team_modulo.cliente','Empaque')
                 ->whereDate('planeacion_diaria.updated_at',$actual)
                 ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
                //->sum('piezas');
                ->sum('min_producidos'); */

           $real_Empaque = Tickets_empaque::join('team_modulo','team_modulo.modulo','ticket_empaque.modulo') ///resultados por tickets
            ->where('team_modulo.cliente','Empaque')
            ->whereDate('ticket_empaque.updated_at',$actual)
            ->whereTime('ticket_empaque.updated_at','<=',$hora_actualizacion)
            //->sum('piezas');
            ->sum('cantidad');


                 $proyeccion_min_EmpaqueII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                 ->where('cliente','Empaque')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->sum('proyeccion_minutos');

                 $proyeccion_min_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
                 ->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at','<=',$hora_actualizacion)->sum('cantidad');

//dd($proyeccion_min_EmpaqueI,$proyeccion_min_EmpaqueII,$min_pres_netos_Empaque);
                 if($min_pres_netos_Empaque == 0){
                     $eficiencia_Empaque = 0;
                 }else{
                     $eficiencia_Empaque = (($proyeccion_min_EmpaqueI+$proyeccion_min_EmpaqueII) / $min_pres_netos_Empaque)*100;
                 }
             }


    return view('avanceproduccion', compact('meta','tiempo_desocupacion','horas_laboradas', 'hoy','hora','cantidad_dia','eficiencia_dia','cantidad_acum','inicio','fin','cantidad_plantaI','cantidad_plantaII','eficiencia_plantaI','eficiencia_plantaII','cantidad_VS','cantidad_CHICOS','cantidad_NU','cantidad_BN3','cantidad_MARENA','cantidad_PACIFIC','cantidad_BELL','cantidad_WP','cantidad_HOOEY','cantidad_Empaque','eficiencia_dia_VS','eficiencia_dia_CHICOS','eficiencia_dia_NU','eficiencia_dia_BN3','eficiencia_dia_PACIFIC','eficiencia_dia_MARENA','eficiencia_dia_BELL','eficiencia_dia_WP','eficiencia_dia_HOOEY','eficiencia_dia_Empaque','modulos','team_leader','planeacion','cantidad_semanal','sam_empaque','efic_total','real_x_plantaI','real_x_plantaII','real_VS','real_CHICOS','real_BN3','real_NU','real_MARENA','real_LECOQ','real_BELL','real_WP','real_HOOEY','real_Empaque','cantidad_diaria','eficiencia_semanal','planeacion_meta','modulo_meta','horas_laboradas','valor_hora','eficiencia_total','cantidad_total','eficiencia_dia_plantaI','eficiencia_dia_plantaII','eficiencia_VS','eficiencia_CHICOS','eficiencia_BN3','eficiencia_NU','eficiencia_MARENA','eficiencia_PACIFIC','eficiencia_BELL','eficiencia_WP','eficiencia_HOOEY','eficiencia_Empaque','planeacion_09','planeacion_10','planeacion_11','planeacion_12','planeacion_13','planeacion_14','planeacion_15','planeacion_16','planeacion_17','planeacion_18','hora_actualizacion','plantas','hora_actualizacionII','hora_actualizacionI','real_x_plantaI830','real_x_plantaI831','eficiencia_planta','real_x_plantaI830','real_x_plantaI831','hora3','hoy_hora'));
    }

    public function detalleVS(request $request)
    {

         /**************indicadores *************** */
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

         $dia_semana = date("N");

         //$tiempo_desocupacion = 630*0.98;
         if($dia_semana != 5)
            $tiempo_desocupacion = 630;
        else
            $tiempo_desocupacion = 352.8;

         if($dia_semana != 5)
            $horas_laboradas = 10.5;
         else
             $horas_laboradas = 6;
         $sam_empaque= .910;
         $efic_dia_830A = .90;
         $efic_dia_831A = .90;

         $hoy =date('d/m');
         $dia_semana = date("N");
         $actual = date('Y-m-d');
         $hora_actualiza = Plan_diar2::whereDate('updated_at',$actual)->select('updated_at')->orderby('updated_at','desc')->first();

         if($hora_actualiza)
            $hora_actualizacion =date("H:i", strtotime($hora_actualiza->updated_at));
        else
            $hora_actualizacion = 0;


        if(date("G", strtotime($hora))=='09'){
            $valor_hora=1;
         }else{
               if(date("G", strtotime($hora))=='10'){
                   $valor_hora=2;
                }else{
                   if(date("G", strtotime($hora))=='11'){
                       $valor_hora=3;
                    }else{
                       if(date("G", strtotime($hora))=='12'){
                           $valor_hora=4;
                        }else{
                           if(date("G", strtotime($hora))=='13'){
                               $valor_hora=5;
                            }else{
                               if(date("G", strtotime($hora))=='14'){
                                   $valor_hora=5.5;
                                }else{
                                   if(date("G", strtotime($hora))=='15'){
                                       $valor_hora=6.5;
                                    }else{
                                       if(date("G", strtotime($hora))=='16'){
                                           $valor_hora=7.5;
                                        }else{
                                           if(date("G", strtotime($hora))=='17'){
                                               $valor_hora=8.5;
                                            }else{
                                               if(date("G", strtotime($hora))=='18'){
                                                   $valor_hora=9.5;
                                                }else{
                                                   $valor_hora=0;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            };


        $cantidad_planta_VSI =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
        $cantidad_planta_VSI = ($cantidad_planta_VSI/$horas_laboradas )*$valor_hora;

        $cantidad_planta_VSII =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia
        $cantidad_planta_VSII = ($cantidad_planta_VSII/$horas_laboradas )*$valor_hora;

        $op_presencia1_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_VSI = Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->sum('sam');
        $cantidad_1_dia_VSI = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_VSI = $cantidad_1_dia_VSI[0]->total;
        $min_x_producir_2_dia_VSI = ($op_presencia2_dia_VSI * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_VSI = $min_x_producir_1_dia_VSI + $min_x_producir_2_dia_VSI;
        $min_x_producir_dia_VSI =  $min_x_producir_dia_VSI*1.02;
        $min_x_producir_dia_VSI = Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->where('modulo','<>','830A')->sum('min_x_producir');


        $min_pres_dia_VSI = ($op_presencia1_dia_VSI+$op_presencia2_dia_VSI)* $tiempo_desocupacion;
        $min_pres_netos_dia_VSI = ($min_pres_dia_VSI - ($pxhrs_dia_VSI+$capacitacion_dia_VSI)) + $utility_dia_VSI;

        $min_pres_dia_VSI = Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->sum('min_presencia');
        $min_pres_netos_dia_VSI = Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->sum('min_presencia_netos');

        if($min_pres_netos_dia_VSI != 0)
            $eficiencia_dia_VSI = ($min_x_producir_dia_VSI/$min_pres_netos_dia_VSI)*100; //* promedio del dia
        else
            $eficiencia_dia_VSI = 0;

        $op_presencia1_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('modulo','<>','830A')->sum('op_presencia');
        $op_presencia2_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('modulo','830A')->sum('op_presencia');

        $pxhrs_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('modulo','<>','830A')->sum('pxhrs');
        $capacitacion_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('modulo','<>','830A')->sum('capacitacion');
        $utility_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('modulo','<>','830A')->sum('utility');
        $sam_dia_VSII = Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->where('modulo','<>','830A')->sum('sam');
        $cantidad_1_dia_VSII = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','VS')->where('planta','IntimarkII')->where('modulo','<>','830A')->get();

        $min_x_producir_1_dia_VSII = $cantidad_1_dia_VSII[0]->total;
        $min_x_producir_2_dia_VSII = ($op_presencia2_dia_VSII * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_dia_VSII = $min_x_producir_1_dia_VSII + $min_x_producir_2_dia_VSII;
        $min_x_producir_dia_VSII= $min_x_producir_dia_VSII*1.02;
      //  $min_x_producir_dia_VSII = Team_Modulo::where('cliente','VS')->where('IntimarkII')->where('modulo','<>','830A')->sum('min_x_producir');


        $min_pres_dia_VSII = ($op_presencia1_dia_VSII+$op_presencia2_dia_VSII)* $tiempo_desocupacion;
        $min_pres_netos_dia_VSII = ($min_pres_dia_VSII - ($pxhrs_dia_VSII+$capacitacion_dia_VSII)) + $utility_dia_VSII;

        $min_pres_dia_VSII = Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->sum('min_presencia');
        $min_pres_netos_dia_VSII = Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->sum('min_presencia_netos');

        if($min_pres_netos_dia_VSII != 0)
            $eficiencia_dia_VSII = ($min_x_producir_dia_VSII/$min_pres_netos_dia_VSII)*100; //* promedio del dia
        else
            $eficiencia_dia_VSII = 0;

        $op_presencia1_VSI = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkI')->sum('op_presencia');
        $op_presencia2_VSI = Team_modulo::where('cliente','VS')->where('modulo','830A')->where('planta','IntimarkI')->sum('op_presencia');

        $pxhrs_VSI = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkI')->sum('pxhrs');
        $capacitacion_VSI = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkI')->sum('capacitacion');
        $utility_VSI = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkI')->sum('utility');
        $sam_VSI = Team_Modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkI')->sum('sam');
        $cantidad_1_VSI = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkI')->get();

        $min_x_producir_1_VSI = $cantidad_1_VSI[0]->total;
        $min_x_producir_2_VSI = ($op_presencia2_VSI * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_VSI = $min_x_producir_1_VSI + $min_x_producir_2_VSI;
        $min_x_producir_VSI= $min_x_producir_VSI*1.02;


        $min_pres_VSI = ($op_presencia1_VSI+$op_presencia2_VSI)* $tiempo_desocupacion;
        $min_pres_netos_VSI = ($min_pres_VSI - ($pxhrs_VSI+$capacitacion_VSI)) + $utility_VSI;

        $proyeccion_min_VSI = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->where('planta','IntimarkI')->sum('proyeccion_minutos');

        $min_pres_netos_VSI = Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->sum('min_presencia_netos');


        if($min_pres_netos_VSI == 0){
            $eficiencia_VSI = 0;
        }else{
            $eficiencia_VSI = ($proyeccion_min_VSI / $min_pres_netos_VSI)*100;
        }

        $op_presencia1_VSII = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkII')->sum('op_presencia');
        $op_presencia2_VSII = Team_modulo::where('cliente','VS')->where('modulo','830A')->where('planta','IntimarkII')->sum('op_presencia');

        $pxhrs_VSII = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkII')->sum('pxhrs');
        $capacitacion_VSII = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkII')->sum('capacitacion');
        $utility_VSII = Team_modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkII')->sum('utility');
        $sam_VSII = Team_Modulo::where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkII')->sum('sam');
        $cantidad_1_VSII = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','VS')->where('modulo','<>','830A')->where('planta','IntimarkII')->get();

        $min_x_producir_1_VSII = $cantidad_1_VSII[0]->total;
        $min_x_producir_2_VSII = ($op_presencia2_VSII * $tiempo_desocupacion)*$efic_dia_830A;

        $min_x_producir_VSII = $min_x_producir_1_VSII + $min_x_producir_2_VSII;
        $min_x_producir_VSII = $min_x_producir_VSII *1.02;

        $min_pres_VSII = ($op_presencia1_VSII+$op_presencia2_VSII)* $tiempo_desocupacion;
        $min_pres_netos_VSII = ($min_pres_VSII - ($pxhrs_VSII+$capacitacion_VSII)) + $utility_VSII;

        $proyeccion_min_VSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->where('planta','IntimarkII')->sum('proyeccion_minutos');

        $min_pres_netos_VSII = Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->sum('min_presencia_netos');


        if($min_pres_netos_VSII == 0){
            $eficiencia_VSII = 0;
        }else{
            $eficiencia_VSII = ($proyeccion_min_VSII / $min_pres_netos_VSII)*100;
        }

        $real_VSI = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
        ->where('team_modulo.cliente','VS')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',$hora)
        ->where('planta','IntimarkI')
        ->sum('piezas');

        if($hora <> $hora_actualizacion){
             $real_VSI = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
             ->where('team_modulo.cliente','VS')
             ->whereDate('planeacion_diaria.updated_at',$actual)
             ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
             ->where('planta','IntimarkI')
             ->sum('piezas');

             $proyeccion_min_VSI = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
             ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->where('planta','IntimarkI')->sum('proyeccion_minutos');

             $min_pres_netos_VSI = Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->sum('min_presencia_netos');


             if($min_pres_netos_VSI == 0){
                 $eficiencia_VSI = 0;
             }else{
                 $eficiencia_VSI = ($proyeccion_min_VSI / $min_pres_netos_VSI)*100;
             }
         }


         $real_VSII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
         ->where('team_modulo.cliente','VS')
         ->whereDate('planeacion_diaria.updated_at',$actual)
         ->whereTime('planeacion_diaria.updated_at',$hora)
         ->where('planta','IntimarkII')
         ->sum('piezas');

         if($hora <> $hora_actualizacion){
              $real_VSII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
              ->where('team_modulo.cliente','VS')
              ->whereDate('planeacion_diaria.updated_at',$actual)
              ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
              ->where('planta','IntimarkII')
              ->sum('piezas');

              $proyeccion_min_VSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
              ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->where('planta','IntimarkII')->sum('proyeccion_minutos');

              $min_pres_netos_VSII = Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->sum('min_presencia_netos');

              if($min_pres_netos_VSII == 0){
                  $eficiencia_VSII = 0;
              }else{
                  $eficiencia_VSII = ($proyeccion_min_VSII / $min_pres_netos_VSII)*100;
              }
            }

            return view('detalleVS', compact('cantidad_planta_VSI','cantidad_planta_VSII','eficiencia_dia_VSI','eficiencia_dia_VSII','eficiencia_VSI','eficiencia_VSII','real_VSI','real_VSII'));

        }

        public function detalleEmpaque(request $request)
        {


             /**************indicadores *************** */
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



             $dia_semana = date("N");

             //$tiempo_desocupacion = 630*0.98;
             if($dia_semana != 5)
                $tiempo_desocupacion = 630;
            else
                $tiempo_desocupacion = 352.8;

             if($dia_semana != 5)
                $horas_laboradas = 10.5;
             else
                 $horas_laboradas = 6;
             $sam_empaque= .910;
             $efic_dia_830A = .90;
             $efic_dia_831A = .85;

             $hoy =date('d/m');
          //   $hora = date("G").":00";
             $dia_semana = date("N");
             $actual = date('Y-m-d');

             $ultima_actualizacionII = Plan_diar2::join('team_modulo','Team_modulo.Modulo','planeacion_diaria.Modulo')
             ->where('team_modulo.planta','IntimarkII')
             ->whereDate('planeacion_diaria.updated_at',$actual)
             ->select('planeacion_diaria.updated_at')->orderby('planeacion_diaria.updated_at','desc')->first();


             if($ultima_actualizacionII)
                  $hora_actualizacion =date("H:i", strtotime($ultima_actualizacionII->updated_at));
               else
                  $hora_actualizacionII = 0;

/*
             $hora_actualiza = Plan_diar2::whereDate('updated_at',$actual)->select('updated_at')->orderby('updated_at','desc')->first();

             if($hora_actualiza)
                $hora_actualizacion =date("H:i", strtotime($hora_actualiza->updated_at));
            else
                $hora_actualizacion = 0;
*/

            if(date("G", strtotime($hora))=='09'){
                $valor_hora=1;
             }else{
                   if(date("G", strtotime($hora))=='10'){
                       $valor_hora=2;
                    }else{
                       if(date("G", strtotime($hora))=='11'){
                           $valor_hora=3;
                        }else{
                           if(date("G", strtotime($hora))=='12'){
                               $valor_hora=4;
                            }else{
                               if(date("G", strtotime($hora))=='13'){
                                   $valor_hora=5;
                                }else{
                                   if(date("G", strtotime($hora))=='14'){
                                       $valor_hora=5.5;
                                    }else{
                                       if(date("G", strtotime($hora))=='15'){
                                           $valor_hora=6.5;
                                        }else{
                                           if(date("G", strtotime($hora))=='16'){
                                               $valor_hora=7.5;
                                            }else{
                                               if(date("G", strtotime($hora))=='17'){
                                                   $valor_hora=8.5;
                                                }else{
                                                   if(date("G", strtotime($hora))=='18'){
                                                       $valor_hora=9.5;
                                                    }else{
                                                       $valor_hora=0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                };

        $cantidad_planta_EmpaqueI =  Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('min_x_producir');  //suma del dia
        $cantidad_planta_EmpaqueI = ($cantidad_planta_EmpaqueI/$horas_laboradas )*$valor_hora;

        $cantidad_planta_EmpaqueII =  Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('min_x_producir');  //suma del dia
        $cantidad_planta_EmpaqueII = ($cantidad_planta_EmpaqueII/$horas_laboradas )*$valor_hora;

        $op_presencia1_dia_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('op_presencia');

        $pxhrs_dia_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('pxhrs');
        $capacitacion_dia_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('capacitacion');
        $utility_dia_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('utility');
        $sam_dia_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('sam');

        $min_x_producir_dia_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('min_x_producir');
        $min_pres_dia_EmpaqueI = ($op_presencia1_dia_EmpaqueI)* $tiempo_desocupacion;
        $min_pres_netos_dia_EmpaqueI = ($min_pres_dia_EmpaqueI - ($pxhrs_dia_EmpaqueI+$capacitacion_dia_EmpaqueI)) + $utility_dia_EmpaqueI;

        if($min_pres_netos_dia_EmpaqueI != 0)
            $eficiencia_dia_EmpaqueI = ($min_x_producir_dia_EmpaqueI/$min_pres_netos_dia_EmpaqueI)*100; //* promedio del dia
        else
            $eficiencia_dia_EmpaqueI = 0;

        $op_presencia1_dia_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('op_presencia');

        $pxhrs_dia_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('pxhrs');
        $capacitacion_dia_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('capacitacion');
        $utility_dia_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('utility');
        $sam_dia_EmpaqueII = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('sam');
        $cantidad_1_dia_EmpaqueII = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','Empaque')->where('planta','IntimarkII')->get();

        $min_x_producir_1_dia_EmpaqueII = $cantidad_1_dia_EmpaqueII[0]->total;

        $min_x_producir_dia_EmpaqueII = $min_x_producir_1_dia_EmpaqueII ;
        $min_x_producir_dia_EmpaqueII= $min_x_producir_dia_EmpaqueII*1.02;

        $min_pres_dia_EmpaqueII = ($op_presencia1_dia_EmpaqueII)* $tiempo_desocupacion;
        $min_pres_netos_dia_EmpaqueII = ($min_pres_dia_EmpaqueII - ($pxhrs_dia_EmpaqueII+$capacitacion_dia_EmpaqueII)) + $utility_dia_EmpaqueII;

        if($min_pres_netos_dia_EmpaqueII != 0)
            $eficiencia_dia_EmpaqueII = ($min_x_producir_dia_EmpaqueII/$min_pres_netos_dia_EmpaqueII)*100; //* promedio del dia
        else
            $eficiencia_dia_EmpaqueII = 0;

        $op_presencia1_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('op_presencia');

        $pxhrs_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('pxhrs');
        $capacitacion_EmpaqueI = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('capacitacion');
        $utility_EmpaqueI = Team_modulo::where('cliente','empaque')->where('planta','IntimarkI')->sum('utility');
        $sam_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('sam');
        $cantidad_1_EmpaqueI = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','Empaque')->where('planta','IntimarkI')->get();

        $min_x_producir_1_EmpaqueI = $cantidad_1_EmpaqueI[0]->total;

        $min_x_producir_EmpaqueI = $min_x_producir_1_EmpaqueI ;
        $min_x_producir_EmpaqueI= $min_x_producir_EmpaqueI*1.02;

        $min_pres_EmpaqueI = ($op_presencia1_EmpaqueI)* $tiempo_desocupacion;
        $min_pres_netos_EmpaqueI = ($min_pres_EmpaqueI - ($pxhrs_EmpaqueI+$capacitacion_EmpaqueI)) + $utility_EmpaqueI;

        $proyeccion_min_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
        ->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at',$hora)->where('planta','IntimarkI')->sum('cantidad');

        if($min_pres_netos_EmpaqueI == 0){
            $eficiencia_EmpaqueI = 0;
        }else{
            $eficiencia_EmpaqueI = ($proyeccion_min_EmpaqueI / $min_pres_netos_EmpaqueI)*100;
        }

        $op_presencia1_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('op_presencia');

        $pxhrs_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('pxhrs');
        $capacitacion_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('capacitacion');
        $utility_EmpaqueII = Team_modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('utility');
        $sam_EmpaqueII = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('sam');
        $cantidad_1_EmpaqueII = Team_Modulo::select(DB::raw('sum(piezas_meta * sam) as total'))->where('cliente','Empaque')->where('planta','IntimarkII')->get();

        $min_x_producir_1_EmpaqueII = $cantidad_1_EmpaqueII[0]->total;

        $min_x_producir_EmpaqueII = $min_x_producir_1_EmpaqueII ;
        $min_x_producir_EmpaqueII = $min_x_producir_EmpaqueII *1.02;

        $min_pres_EmpaqueII = ($op_presencia1_EmpaqueII)* $tiempo_desocupacion;
        $min_pres_netos_EmpaqueII = ($min_pres_EmpaqueII - ($pxhrs_EmpaqueII+$capacitacion_EmpaqueII)) + $utility_EmpaqueII;

        $proyeccion_min_EmpaqueII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.modulo')
        ->where('cliente','Empaque')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->where('planta','IntimarkII')->sum('piezas');

        if($min_pres_netos_EmpaqueII == 0){
            $eficiencia_EmpaqueII = 0;
        }else{
            $eficiencia_EmpaqueII = ($proyeccion_min_EmpaqueII / $min_pres_netos_EmpaqueII)*100;
        }

         $real_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.modulo','ticket_empaque.modulo')
        ->where('team_modulo.cliente','Empaque')
        ->whereDate('ticket_empaque.updated_at',$actual)
        ->whereTime('ticket_empaque.updated_at','<=',$hora)
        ->where('planta','IntimarkI')
        //->sum('piezas');
        ->sum('cantidad');

        if($hora3 <> $hora_actualizacion){

            $real_EmpaqueI = tickets_empaque::join('team_modulo','team_modulo.modulo','ticket_empaque.modulo')
            ->where('team_modulo.cliente','Empaque')
            ->whereDate('ticket_empaque.updated_at',$actual)
            ->whereTime('ticket_empaque.updated_at','<=',$hora_actualizacion)
            ->where('planta','IntimarkI')
            //->sum('piezas');
            ->sum('cantidad');

             $proyeccion_min_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
             ->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at','<=',$hora_actualizacion)->where('planta','IntimarkI')->sum('cantidad');

             if($min_pres_netos_EmpaqueI == 0){
                 $eficiencia_EmpaqueI = 0;
             }else{
                 $eficiencia_EmpaqueI = ($proyeccion_min_EmpaqueI / $min_pres_netos_EmpaqueI)*100;
             }
         }

           $real_EmpaqueII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
           ->where('team_modulo.cliente','Empaque')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->where('planta','IntimarkII')
             //->sum('piezas');
             ->sum('piezas');

         if($hora <> $hora_actualizacion){

              $real_EmpaqueII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
              ->where('team_modulo.cliente','Empaque')
              ->whereDate('planeacion_diaria.updated_at',$actual)
              ->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)
              ->where('planta','IntimarkII')
              //->sum('piezas');
              ->sum('piezas');

              $min_pres_netos_EmpaqueII= Team_Modulo::where('planta','IntimarkII')->where('piezas_meta','<>',0)->whereDate('updated_at',$actual)->sum('min_presencia_netos');

                $proyeccion_min_EmpaqueII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.modulo')
              ->where('cliente','Empaque')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacion)->where('planta','IntimarkII')->sum('proyeccion_minutos');


              if($min_pres_netos_EmpaqueII == 0){
                  $eficiencia_EmpaqueII = 0;
              }else{
                  $eficiencia_EmpaqueII = ($proyeccion_min_EmpaqueII / $min_pres_netos_EmpaqueII)*100;
              }
            }
        return view('detalleEmpaque', compact('cantidad_planta_EmpaqueI','cantidad_planta_EmpaqueII','eficiencia_dia_EmpaqueI','eficiencia_dia_EmpaqueII','eficiencia_EmpaqueI','eficiencia_EmpaqueII','real_EmpaqueI','real_EmpaqueII', 'efic_dia_830A', 'efic_dia_831A'));

    }


}

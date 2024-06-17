<?php

namespace App\Http\Controllers;

use App\Formato_P07;

use App\Team_Leader;
use App\Team_Modulo;
use App\Planeacion;
use App\Plan_diar;
use App\Plan_diar2;

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
        $hora3 = date("G", strtotime($hora_aux)).":30";
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
                                                $valor_hora=9.5;
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

        $inicio=date("Y-m-d",strtotime($actual."- ".$dia_semana." days"));
        $fin = date("Y-m-d",strtotime($actual."+ 6 days"));

        $ultima_actualizacionI = Plan_diar::join('team_modulo','Team_modulo.Modulo','ticket_offline.Modulo')
        ->where('team_modulo.planta','IntimarkI')
        ->whereDate('ticket_offline.fecha',$actual)
        ->select('ticket_offline.created_at')->orderby('ticket_offline.created_at','desc')->first();

        if($ultima_actualizacionI)
            $hora_actualizacionI =date("H:i:s", strtotime($ultima_actualizacionI->created_at));
        else
            $hora_actualizacionI = "N/D";

        $ultima_actualizacionII = Plan_diar::join('team_modulo','Team_modulo.Modulo','ticket_offline.Modulo')
        ->where('team_modulo.planta','IntimarkII')
        ->whereDate('ticket_offline.fecha',$actual)
        ->select('ticket_offline.created_at')->orderby('ticket_offline.created_at','desc')->first();

        if($ultima_actualizacionII)
            $hora_actualizacionII =date("H:i:s", strtotime($ultima_actualizacionII->created_at));
        else
            $hora_actualizacionII = "N/D";

            /* $ultima_actualizacionII = Plan_diar2::join('team_modulo','Team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('team_modulo.planta','IntimarkII')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->select('planeacion_diaria.updated_at')->orderby('planeacion_diaria.updated_at','desc')->first();


        if($ultima_actualizacionII)
            $hora_actualizacionII =date("H:i", strtotime($ultima_actualizacionII->updated_at));
        else
            $hora_actualizacionII = "N/D"; */
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


         $valor_hora2=1;

        /********** actualizacion ticket offline *******************/

        date_default_timezone_set('America/Mexico_City');

        //$formData = json_decode($request->input('formData'), true);
        $formData = Plan_diar::select('piezas','modulo','fecha')
        ->whereDate('fecha', $actual)
        ->where(\DB::raw('substr(modulo, 1, 1)'), '<>' , 8)
        ->where('efic',0)
        ->get();

        $valor = Plan_diar::select('fecha')->whereDate('ticket_offline.fecha',$actual)->where('efic','<>',0)->orderby('fecha','desc')->first();

        if($valor){

        if(date("G", strtotime($valor->fecha))=='09'){
            $valor_hora2=1;
        }else{
            if(date("G", strtotime($valor->fecha))=='10'){
                $valor_hora2=2;
            }else{
                if(date("G", strtotime($valor->fecha))=='11'){
                    $valor_hora2=3;
                }else{
                    if(date("G", strtotime($valor->fecha))=='12'){
                        $valor_hora2=4;
                    }else{
                        if(date("G", strtotime($valor->fecha))=='13'){
                            $valor_hora2=5;
                        }else{
                            if(date("G", strtotime($valor->fecha))=='14'){
                                $valor_hora2=5.5;
                            }else{
                                if(date("G", strtotime($valor->fecha))=='15'){
                                    $valor_hora2=6.5;
                                }else{
                                    if(date("G", strtotime($valor->fecha))=='16'){
                                        $valor_hora2=7.5;
                                    }else{
                                        if(date("G", strtotime($valor->fecha))=='17'){
                                            $valor_hora2=8.5;
                                        }else{
                                            if(date("G", strtotime($valor->fecha))=='18'){
                                                $valor_hora2=9.5;
                                            }else{
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
        }
        }else{
            $valor_hora2=1;
        }

        //dd($actual);
        foreach ($formData as $data) {
            $piezas = $data['piezas'];
            $modulo = $data['modulo'];
            $fecha =  $data['fecha'];

            $teams_leader = team_modulo::select('team_leader','min_presencia_netos')
            ->where('modulo', $modulo)
            ->first();

            $registrarData = new Plan_diar();

                /**************minutos producidos *********/

            $sam = Team_Modulo::where('modulo', $modulo)->select('sam')->first();

            $registrarData->min_producidos=$piezas*$sam->sam;


                 if($dia_semana  <>5)
                    $horas_laboradas = 10.5;
                else
                    $horas_laboradas = 6;

                /*    if(date("G", strtotime($fecha))=='09'){
                        $valor_hora2=1;
                     }else{
                        if(date("G", strtotime($fecha))=='10'){
                            $valor_hora2=2;
                         }else{
                            if(date("G", strtotime($fecha))=='11'){
                                $valor_hora2=3;
                             }else{
                                if(date("G", strtotime($fecha))=='12'){
                                    $valor_hora2=4;
                                 }else{
                                    if(date("G", strtotime($fecha))=='13'){
                                        $valor_hora2=5;
                                     }else{
                                        if(date("G", strtotime($fecha))=='14'){
                                            $valor_hora2=5.5;
                                         }else{
                                            if(date("G", strtotime($fecha))=='15'){
                                                $valor_hora2=6.5;
                                             }else{
                                                if(date("G", strtotime($fecha))=='16'){
                                                    $valor_hora2=7.5;
                                                 }else{
                                                    if(date("G", strtotime($fecha))=='17'){
                                                        $valor_hora2=8.5;
                                                     }else{
                                                        if(date("G", strtotime($fecha))=='18'){
                                                            $valor_hora2=9.5;
                                                         }else{
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
                     };*/

                 $registrarData->proyeccion_minutos=  ($registrarData->min_producidos/$valor_hora2)*$horas_laboradas;
                 /************eficiencia ************** */

                 if($teams_leader->min_presencia_netos == 0)
                    $registrarData->efic = 0;
                else
                    $registrarData->efic = ($registrarData->proyeccion_minutos/ $teams_leader->min_presencia_netos)*100;

              Plan_diar::where('modulo', $modulo)->where('fecha',$fecha)->where('efic',0)->update([
                'team_leader' =>  $teams_leader->team_leader,
                'min_producidos' => $registrarData->min_producidos,
                'proyeccion_minutos' => $registrarData->proyeccion_minutos,
                'efic' =>  $registrarData->efic
            ]);




            }
            $proyeccion = Formato_P07::select('cantidad_d'.$dia_semana.' as cantidad', 'minutos_prod_d'.$dia_semana.' as minutos_prod', 'minutos_100_d'.$dia_semana.' as minutos_100', 'modulo')
            ->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->get();
           /// dd($proyeccion[0]);
            //$eficiencia=($proyeccion->minutos_100/$proyeccion->minutos_prod)*100;
            foreach ($proyeccion as $proy) {
                $datos_team_modulo = Team_modulo::where('modulo',$proy->modulo)->whereDate('updated_at','<>',$actual)->first();
                if($datos_team_modulo){
                    $min_producir=($proy->cantidad*$datos_team_modulo->sam)*1.02;
                    $min_presencia=($datos_team_modulo->op_real)*630;
                    $min_presencia_netos = $min_presencia -(($datos_team_modulo->pxhrs+$datos_team_modulo->capacitacion)+$datos_team_modulo->utility);
                    if($min_presencia_netos==0)
                        $eficiencia=0;
                    else
                        $eficiencia = ($min_producir/$min_presencia_netos)*100;

                    Team_modulo::where('modulo', $proy->modulo)->update([
                            'piezas_meta' =>  $proy->cantidad,
                            'min_x_producir' =>  $min_producir,
                            'min_presencia' =>  $min_presencia,
                            'min_presencia_netos' =>  $min_presencia_netos,
                            'eficiencia_dia' =>  $eficiencia,
                            'utility' => 0,
                            'pxhrs' => 0,
                            'capacitacion' => 0,
                            'op_presencia' => $datos_team_modulo->op_real

                    ]);
                }else{
                    //$min_producir=0;
                    //$min_presencia=0;
                    //$min_presencia_netos = 0;
                    //$eficiencia = 0;

                }


        }

        /***************montos generales ******************/

        //$meta = Formato_P07::select(DB::raw('sum(cantidad_total) as cantidad_total'), DB::raw('AVG(eficiencia_total) as eficiencia_total'))->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->where('modulo','total')->get();
    //    $meta = Formato_P07::select(DB::raw('sum(cantidad_d1+cantidad_d2+cantidad_d3+cantidad_d4+cantidad_d5) as cantidad_total'), DB::raw('sum(minutos_prod_d1+minutos_prod_d2+minutos_prod_d3+minutos_prod_d4+minutos_prod_d5) as minutos_prod'), DB::raw('sum(minutos_100_d1+minutos_100_d2+minutos_100_d3+minutos_100_d4+minutos_100_d5) as minutos_100'))->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->get();
        $metas = Formato_P07::where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->get();

       $cantidad_total = 0;
       $minutos_prod = 0;
       $minutos_100 = 0;

       foreach ($metas as $meta) {
            if(!$meta){
                $eficiencia_total = 0;
                $cantidad_total = 0;
            }else{
                if($meta->cantidad_d1 <> NULL)
                    $cantidad_total = $cantidad_total + $meta->cantidad_d1;
                if($meta->cantidad_d2 <> NULL)
                    $cantidad_total = $cantidad_total + $meta->cantidad_d2;
                if($meta->cantidad_d3 <> NULL)
                    $cantidad_total = $cantidad_total + $meta->cantidad_d3;
                if($meta->cantidad_d4 <> NULL)
                    $cantidad_total = $cantidad_total + $meta->cantidad_d4;
                if($meta->cantidad_d5 <> NULL)
                    $cantidad_total = $cantidad_total + $meta->cantidad_d5;

                if($meta->minutos_prod_d1 <> NULL)
                    $minutos_prod = $minutos_prod + $meta->minutos_prod_d1;
                if($meta->minutos_prod_d2 <> NULL)
                    $minutos_prod = $minutos_prod + $meta->minutos_prod_d2;
                if($meta->minutos_prod_d3 <> NULL)
                    $minutos_prod = $minutos_prod + $meta->minutos_prod_d3;
                if($meta->minutos_prod_d4 <> NULL)
                    $minutos_prod = $minutos_prod + $meta->minutos_prod_d4;
                if($meta->minutos_prod_d5 <> NULL)
                    $minutos_prod = $minutos_prod + $meta->minutos_prod_d5;

                if($meta->minutos_100_d1 <> NULL)
                    $minutos_100 = $minutos_100 + $meta->minutos_100_d1;
                if($meta->minutos_100_d2 <> NULL)
                    $minutos_100 = $minutos_100 + $meta->minutos_100_d2;
                if($meta->minutos_100_d3 <> NULL)
                    $minutos_100 = $minutos_100 + $meta->minutos_100_d3;
                if($meta->minutos_100_d4 <> NULL)
                    $minutos_100 = $minutos_100 + $meta->minutos_100_d4;
                if($meta->minutos_100_d5 <> NULL)
                    $minutos_100 = $minutos_100 + $meta->minutos_100_d5;
//dd($minutos_100);
                if($minutos_100==0){
                    $eficiencia_total=0;
                }else{
                    $eficiencia_total = ($minutos_prod/$minutos_100)*100;
                }

    //            $cantidad_total = $meta[0]->cantidad_total;
            }
       }

        $cantidad_dia =  Team_Modulo::whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
        $cantidad_dia = ($cantidad_dia/$horas_laboradas )*$valor_hora;

        if($dia_semana != 5)
            $tiempo_desocupacion = 630;
        else
            $tiempo_desocupacion = 352.8;

        $efic_dia_830A = .90;
/****************eficiencia de la empresa ************* */
        $min_x_producir_dia = Team_modulo::where('piezas_meta','<>',0)->sum('min_x_producir');
        $min_pres_netos_dia = Team_modulo::where('piezas_meta','<>',0)->sum('min_presencia_netos');

        if($min_pres_netos_dia == 0){
            $min_pres_netos_dia =1;
            //$cantidad_dia = Formato_P07::where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
            //$eficiencia_dia = Formato_P07::where('modulo','total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('eficiencia_d'.$dia_semana);
            //$eficiencia_dia = $eficiencia_dia/2;

            $cantidad_dia = Formato_P07::where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
            $min_prod_plantaI = Formato_P07::where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_prod_d'.$dia_semana);
            $min_100_plantaI = Formato_P07::where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_100_d'.$dia_semana);

            $eficiencia_plantaI = $min_prod_plantaI/$min_100_plantaI;
        }else{
            $eficiencia_dia = ($min_x_producir_dia/$min_pres_netos_dia)*100; //* promedio del dia
        }

/************************** por planta ************* */
        $plantas = Formato_P07::select('planta')->groupby('Planta')->get();

        $cantidad_plantaI =  Team_Modulo::where('planta','IntimarkI')->where('Modulo','<>','830A')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
        $cantidad_plantaI = ($cantidad_plantaI/$horas_laboradas )*$valor_hora;

        $min_producidos_plantaI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
            ->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');


            $proyeccion_min_plantaI = ($min_producidos_plantaI/$valor_hora)*$horas_laboradas;

            $min_pres_netos_plantaI = Team_modulo::where('planta', 'IntimarkI')->where('piezas_meta','<>',0)->sum('min_presencia_netos');

            $proyeccion_min_EmpaqueI = Tickets_empaque::where('modulo','<>','831A')
            ->whereDate('ticket_empaque.fecha',$actual)
            ->whereTime('ticket_empaque.updated_at','=',$hora3)
            ->sum('cantidad');
            $proyeccion_min_EmpaqueI = ($proyeccion_min_EmpaqueI/$valor_hora)*$horas_laboradas;

            $min_pres_netos_EmpaqueI = Team_modulo::where('modulo','<>','831A')->where('cliente','Empaque')->sum('min_presencia_netos');

//dd($min_producidos_plantaI, $valor_hora, $horas_laboradas);

            if($min_pres_netos_plantaI == 0){
                $eficiencia_plantaI = 0;
            }else{
                $eficiencia_plantaI = (($proyeccion_min_plantaI + $proyeccion_min_EmpaqueI) / ($min_pres_netos_plantaI + $min_pres_netos_EmpaqueI))*100;
            }

            if($cantidad_plantaI == 0){
                $cantidad_plantaI = Formato_P07::where('planta','Intimark1')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
                $min_prod_plantaI = Formato_P07::where('planta','Intimark1')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_prod_d'.$dia_semana);
                $min_100_plantaI = Formato_P07::where('planta','Intimark1')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_100_d'.$dia_semana);

                if($min_100_plantaI==0)
                    $eficiencia_plantaI =0;
                else
                    $eficiencia_plantaI = $min_prod_plantaI/$min_100_plantaI;
            }

           //******calculo de eficiencia para planta I ********/
            $min_x_producir_dia_plantaI = Team_modulo::where('planta', 'IntimarkI')->where('piezas_meta','<>',0)->sum('min_x_producir');
            $min_pres_netos_dia_plantaI = Team_modulo::where('planta', 'IntimarkI')->where('piezas_meta','<>',0)->sum('min_presencia_netos');

            if($min_pres_netos_dia_plantaI==0){
                $eficiencia_dia_plantaI =  $eficiencia_plantaI;
            }else{
                $eficiencia_dia_plantaI = ($min_x_producir_dia_plantaI/$min_pres_netos_dia_plantaI)*100; //* promedio del dia
            }

            $cantidad_plantaII =  Team_Modulo::where('planta','IntimarkII')->where('Modulo','<>','831A')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
            $cantidad_plantaII = ($cantidad_plantaII/$horas_laboradas )*$valor_hora;


            $min_producidos_plantaII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
            ->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

            $proyeccion_min_plantaII = ($min_producidos_plantaII/$valor_hora)*$horas_laboradas;

            $min_pres_netos_plantaII = Team_modulo::where('planta', 'IntimarkII')->where('piezas_meta','<>',0)->sum('min_presencia_netos');

            $proyeccion_min_EmpaqueII = Tickets_empaque::where('modulo','<>','831A')
            ->whereDate('ticket_empaque.fecha',$actual)
            ->whereTime('ticket_empaque.updated_at','=',$hora3)
            ->sum('cantidad');
            $proyeccion_min_EmpaqueII = ($proyeccion_min_EmpaqueII/$valor_hora)*$horas_laboradas;

            $min_pres_netos_EmpaqueII = Team_modulo::where('modulo','<>','831A')->where('cliente','Empaque')->sum('min_presencia_netos');

//dd($min_producidos_plantaI, $valor_hora, $horas_laboradas);

            if($min_pres_netos_plantaII == 0){
                $eficiencia_plantaII = 0;
            }else{
                $eficiencia_plantaII = (($proyeccion_min_plantaII + $proyeccion_min_EmpaqueII) / ($min_pres_netos_plantaII + $min_pres_netos_EmpaqueII))*100;
            }

            if($cantidad_plantaII == 0){
                $cantidad_plantaII = Formato_P07::where('planta','Intimark2')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
                $min_prod_plantaII = Formato_P07::where('planta','Intimark2')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_prod_d'.$dia_semana);
                $min_100_plantaII = Formato_P07::where('planta','Intimark2')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_100_d'.$dia_semana);

                if ($min_100_plantaII==0)
                    $eficiencia_plantaII = 0;
                else
                    $eficiencia_plantaII = $min_prod_plantaII/$min_100_plantaII;
            }

           //******calculo de eficiencia para planta I ********/
            $min_x_producir_dia_plantaII = Team_modulo::where('planta', 'IntimarkII')->where('piezas_meta','<>',0)->sum('min_x_producir');
            $min_pres_netos_dia_plantaII = Team_modulo::where('planta', 'IntimarkII')->where('piezas_meta','<>',0)->sum('min_presencia_netos');

            if($min_pres_netos_dia_plantaII==0){
                $eficiencia_dia_plantaII =  $eficiencia_plantaII;
            }else{
                $eficiencia_dia_plantaII = ($min_x_producir_dia_plantaII/$min_pres_netos_dia_plantaII)*100; //* promedio del dia
            }

         /*   $proyeccion_min_plantaII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
            ->where('planta','IntimarkII')->where('planeacion_diaria.modulo','<>','831A')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at','=',$hora)
            ->sum('proyeccion_minutos');


            $min_pres_netos_plantaII = Team_modulo::where('planta','Intimark2')->whereDate('updated_at',$actual)->sum('min_presencia_netos');

            $proyeccion_min_EmpaqueII = Tickets_empaque::where('modulo','=','831A')
            ->whereDate('ticket_empaque.fecha',$actual)
            ->whereTime('ticket_empaque.updated_at','=',$hora3)
            ->sum('cantidad');
            $proyeccion_min_EmpaqueII = ($proyeccion_min_EmpaqueII/$valor_hora)*$horas_laboradas;

            $min_pres_netos_EmpaqueII = Team_modulo::where('modulo','=','831A')->where('cliente','Empaque')->sum('min_presencia_netos');
            //dd($proyeccion_min_EmpaqueII, $proyeccion_min_plantaII );

            if($min_pres_netos_plantaII == 0 ||  $proyeccion_min_plantaII == 0){
                $eficiencia_plantaII = 0;
            }else{
               $eficiencia_plantaII = (($proyeccion_min_plantaII + $proyeccion_min_EmpaqueII) / ($min_pres_netos_plantaII) )*100;
            }

            if($cantidad_plantaII == 0 ){
                $cantidad_plantaII = Formato_P07::where('planta','Intimark2')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
                $min_prod_plantaII = Formato_P07::where('planta','Intimark2')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_prod_d'.$dia_semana);
                $min_100_plantaII = Formato_P07::where('planta','Intimark2')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('minutos_100_d'.$dia_semana);

                $eficiencia_plantaII = $min_prod_plantaII/$min_100_plantaII;
            }
            //******calculo de eficiencia para planta II ********/
        /*    $min_x_producir_dia_plantaII = Team_modulo::where('planta', 'IntimarkII')->where('piezas_meta','<>',0)->sum('min_x_producir');

            $min_pres_netos_dia_plantaII = Team_modulo::where('planta', 'IntimarkII')->where('piezas_meta','<>',0)->sum('min_presencia_netos');

            if($min_pres_netos_dia_plantaII==0){
                $eficiencia_dia_plantaII =  $eficiencia_plantaII;
            }else{
                $eficiencia_dia_plantaII = ($min_x_producir_dia_plantaII/$min_pres_netos_dia_plantaII)*100; //* promedio del dia
            } */

/************************** por cliente ************* */
$clientes = Formato_P07::select('cliente')->groupby('cliente')->get();

$cantidad_VS =  Team_Modulo::where('cliente','VS')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
$cantidad_VS = ($cantidad_VS/$horas_laboradas )*$valor_hora;

$cantidad_CHICOS = Team_Modulo::where('cliente','CHICOS')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_CHICOS = ($cantidad_CHICOS/$horas_laboradas )*$valor_hora;

$cantidad_BN3 = Team_Modulo::where('cliente','BN3')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_BN3 = ($cantidad_BN3/$horas_laboradas )*$valor_hora;

$cantidad_NU = Team_Modulo::where('cliente','NUUDS')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_NU = ($cantidad_NU/$horas_laboradas )*$valor_hora;

$cantidad_MARENA = Team_Modulo::where('cliente','MARENA')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_MARENA = ($cantidad_MARENA/$horas_laboradas )*$valor_hora;

$cantidad_PACIFIC = Team_Modulo::where('cliente','PACIFIC')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_PACIFIC = ($cantidad_PACIFIC/$horas_laboradas )*$valor_hora;

$cantidad_BELL = Team_Modulo::where('cliente','BELL')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_BELL = ($cantidad_BELL/$horas_laboradas )*$valor_hora;

$cantidad_WP = Team_Modulo::where('cliente','WP')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_WP = ($cantidad_WP/$horas_laboradas )*$valor_hora;

$cantidad_HOOEY = Team_Modulo::where('cliente','HOOEY')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_HOOEY = ($cantidad_HOOEY/$horas_laboradas )*$valor_hora;

$cantidad_RV = Team_Modulo::where('cliente','RV')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_RV = ($cantidad_RV/$horas_laboradas )*$valor_hora;

$cantidad_HANES = Team_Modulo::where('cliente','HANES')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_HANES = ($cantidad_HANES/$horas_laboradas )*$valor_hora;

$cantidad_STARBOARD = Team_Modulo::where('cliente','STARBOARD')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_STARBOARD = ($cantidad_STARBOARD/$horas_laboradas )*$valor_hora;

$cantidad_MAREL = Team_Modulo::where('cliente','MAREL')->whereDate('updated_at',$actual)->sum('piezas_meta');
$cantidad_MAREL = ($cantidad_MAREL/$horas_laboradas )*$valor_hora;

$cantidad_Empaque = Team_Modulo::where('cliente','Empaque')->sum('min_x_producir');

//$cantidad_Empaque = ($cantidad_Empaque/$horas_laboradas )*$valor_hora;

$min_x_producir_dia_VS = Team_modulo::where('cliente','VS')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_VS = Team_modulo::where('cliente','VS')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_VS==0)
    $eficiencia_dia_VS = 0;
else
    $eficiencia_dia_VS = ($min_x_producir_dia_VS/$min_pres_netos_dia_VS)*100; //* promedio del dia

$min_x_producir_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_CHICOS = Team_modulo::where('cliente','CHICOS')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_CHICOS==0)
$eficiencia_dia_CHICOS =0;
else
$eficiencia_dia_CHICOS = ($min_x_producir_dia_CHICOS/$min_pres_netos_dia_CHICOS)*100; //* promedio del dia

$min_x_producir_dia_BN3 = Team_modulo::where('cliente','BN3')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_BN3 = Team_modulo::where('cliente','BN3')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_BN3 == 0)
    $eficiencia_dia_BN3 = 0; //* promedio del dia
else
    $eficiencia_dia_BN3 = ($min_x_producir_dia_BN3/$min_pres_netos_dia_BN3)*100; //* promedio del dia

$min_x_producir_dia_NU = Team_modulo::where('cliente','NU')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_NU = Team_modulo::where('cliente','NU')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_NU == 0)
    $eficiencia_dia_NU = 0;
else
    $eficiencia_dia_NU = ($min_x_producir_dia_NU/$min_pres_netos_dia_NU)*100; //* promedio del dia

$min_x_producir_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_MARENA = Team_modulo::where('cliente','MARENA')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if ($min_pres_netos_dia_MARENA==0)
$eficiencia_dia_MARENA = 0;
else
$eficiencia_dia_MARENA = ($min_x_producir_dia_MARENA/$min_pres_netos_dia_MARENA)*100; //* promedio del dia

$min_x_producir_dia_PACIFIC = Team_modulo::where('cliente','PACIFIC')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_PACIFIC = Team_modulo::where('cliente','PACIFIC')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_PACIFIC == 0)
    $eficiencia_dia_PACIFIC = 0;
else
    $eficiencia_dia_PACIFIC = ($min_x_producir_dia_PACIFIC/$min_pres_netos_dia_PACIFIC)*100; //* promedio del dia

$min_x_producir_dia_BELL = Team_modulo::where('cliente','BELL')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_BELL = Team_modulo::where('cliente','BELL')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_BELL == 0)
    $eficiencia_dia_BELL = 0;
else
    $eficiencia_dia_BELL = ($min_x_producir_dia_BELL/$min_pres_netos_dia_BELL)*100; //* promedio del dia

$min_x_producir_dia_WP = Team_modulo::where('cliente','WP')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_WP = Team_modulo::where('cliente','WP')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_WP == 0)
    $eficiencia_dia_WP = 0;
else
    $eficiencia_dia_WP = ($min_x_producir_dia_WP/$min_pres_netos_dia_WP)*100; //* promedio del dia

$min_x_producir_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_HOOEY = Team_modulo::where('cliente','HOOEY')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_HOOEY == 0)
    $eficiencia_dia_HOOEY = 0;
else
    $eficiencia_dia_HOOEY = ($min_x_producir_dia_HOOEY/$min_pres_netos_dia_HOOEY)*100; //* promedio del dia

$min_x_producir_dia_RV = Team_modulo::where('cliente','RV')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_RV = Team_modulo::where('cliente','RV')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_RV == 0)
    $eficiencia_dia_RV = 0;
else
    $eficiencia_dia_RV = ($min_x_producir_dia_RV/$min_pres_netos_dia_RV)*100; //* promedio del dia


$min_x_producir_dia_HANES = Team_modulo::where('cliente','HANES')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_HANES = Team_modulo::where('cliente','HANES')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_HANES == 0)
    $eficiencia_dia_HANES = 0;
else
    $eficiencia_dia_HANES = ($min_x_producir_dia_HANES/$min_pres_netos_dia_HANES)*100; //* promedio del dia

$min_x_producir_dia_STARBOARD = Team_modulo::where('cliente','STARBOARD')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_STARBOARD = Team_modulo::where('cliente','STARBOARD')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_STARBOARD == 0)
    $eficiencia_dia_STARBOARD = 0;
else
    $eficiencia_dia_STARBOARD = ($min_x_producir_dia_STARBOARD/$min_pres_netos_dia_STARBOARD)*100; //* promedio del dia

$min_x_producir_dia_MAREL = Team_modulo::where('cliente','MAREL')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_MAREL = Team_modulo::where('cliente','MAREL')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_MAREL == 0)
    $eficiencia_dia_MAREL = 0;
else
    $eficiencia_dia_MAREL = ($min_x_producir_dia_MAREL/$min_pres_netos_dia_MAREL)*100; //* promedio del dia

$min_x_producir_dia_Empaque = Team_modulo::where('cliente','Empaque')->where('piezas_meta','<>','0')->sum('min_x_producir');
$min_pres_netos_dia_Empaque = Team_modulo::where('cliente','Empaque')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_dia_Empaque == 0)
    $eficiencia_dia_Empaque = 0;
else
    $eficiencia_dia_Empaque = ($min_x_producir_dia_Empaque/$min_pres_netos_dia_Empaque)*100; //* promedio del dia


$min_producidos_VSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
    ->where('cliente','VS')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_VSI = ($min_producidos_VSI/$valor_hora)*$horas_laboradas;

if($hora3 <> $hora_actualizacionI){
    $proyeccion_min_VSI = ($min_producidos_VSI/$valor_hora2)*$horas_laboradas;
}

$min_producidos_VSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
    ->where('cliente','VS')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_VSII = ($min_producidos_VSII/$valor_hora)*$horas_laboradas;

/*$proyeccion_min_VSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
    ->where('cliente','VS')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');*/

if($hora3 <> $hora_actualizacionI){
    $proyeccion_min_VSII = ($min_producidos_VSII/$valor_hora2)*$horas_laboradas;

/*    $proyeccion_min_VSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
    ->where('cliente','VS')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)->sum('proyeccion_minutos');*/
}

$min_pres_netos_VS = Team_modulo::where('cliente','VS')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_VS == 0){
    $eficiencia_VS = 0;
}else{
    $eficiencia_VS = (($proyeccion_min_VSI+$proyeccion_min_VSII) / $min_pres_netos_VS)*100;
}

$min_producidos_CHICOSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
    ->where('cliente','CHICOS')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

    $proyeccion_min_CHICOSI = ($min_producidos_CHICOSI/$valor_hora)*$horas_laboradas;

if($hora3 <> $hora_actualizacionI){
    $proyeccion_min_CHICOSI = ($min_producidos_CHICOSI/$valor_hora2)*$horas_laboradas;
}

$min_producidos_CHICOSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
    ->where('cliente','CHICOS')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

    $proyeccion_min_CHICOSII = ($min_producidos_CHICOSII/$valor_hora)*$horas_laboradas;
/*
$proyeccion_min_CHICOSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
    ->where('cliente','CHICOS')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
if($hora3 <> $hora_actualizacionI){
    $proyeccion_min_CHICOSII = ($min_producidos_CHICOSII/$valor_hora2)*$horas_laboradas;
/*
    $proyeccion_min_CHICOSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
    ->where('cliente','CHICOS')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)->sum('proyeccion_minutos');*/
}

$min_pres_netos_CHICOS = Team_modulo::where('cliente','CHICOS')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_CHICOS == 0){
    $eficiencia_CHICOS = 0;
}else{
    $eficiencia_CHICOS = (($proyeccion_min_CHICOSI+$proyeccion_min_CHICOSII) / $min_pres_netos_CHICOS)*100;
}

$min_producidos_BN3I = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','BN3')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_BN3I = ($min_producidos_BN3I/$valor_hora)*$horas_laboradas;

$min_producidos_BN3II = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','BN3')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_BN3II = ($min_producidos_BN3II/$valor_hora)*$horas_laboradas;

/*$proyeccion_min_BN3II = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','BN3')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');*/

$min_pres_netos_BN3 = Team_modulo::where('cliente','BN3')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_BN3 == 0){
    $eficiencia_BN3 = 0;
}else{
    $eficiencia_BN3 = (($proyeccion_min_BN3I+$proyeccion_min_BN3II) / $min_pres_netos_BN3)*100;
}

$min_producidos_NUI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','NU')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_NUI = ($min_producidos_NUI/$valor_hora)*$horas_laboradas;
/*
$proyeccion_min_NUII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','NU')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');*/

$min_producidos_NUII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','NU')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_NUII = ($min_producidos_NUII/$valor_hora)*$horas_laboradas;

$min_pres_netos_NU = Team_modulo::where('cliente','NU')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_NU == 0){
    $eficiencia_NU = 0;
}else{
    $eficiencia_NU = (($proyeccion_min_NUI+$proyeccion_min_NUII) / $min_pres_netos_NU)*100;
}

$min_producidos_MARENAI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','MARENA')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_MARENAI = ($min_producidos_MARENAI/$valor_hora)*$horas_laboradas;

$min_producidos_MARENAII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','MARENA')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_MARENAII = ($min_producidos_MARENAII/$valor_hora)*$horas_laboradas;

/*$proyeccion_min_MARENAII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','MARENA')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_MARENA = Team_modulo::where('cliente','MARENA')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_MARENA == 0){
    $eficiencia_MARENA = 0;
}else{
    $eficiencia_MARENA = (($proyeccion_min_MARENAI+$proyeccion_min_MARENAII) / $min_pres_netos_MARENA)*100;
}


$min_producidos_PACIFICI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','PACIFIC')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_PACIFICI = ($min_producidos_PACIFICI/$valor_hora)*$horas_laboradas;

$min_producidos_PACIFICII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','PACIFIC')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_PACIFICII = ($min_producidos_PACIFICII/$valor_hora)*$horas_laboradas;

$min_pres_netos_PACIFIC = Team_modulo::where('cliente','PACIFIC')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_PACIFIC == 0){
    $eficiencia_PACIFIC = 0;
}else{
    $eficiencia_PACIFIC = (($proyeccion_min_PACIFICI+$proyeccion_min_PACIFICII) / $min_pres_netos_PACIFIC)*100;
}

$min_producidos_BELLI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','BELL')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_BELLI = ($min_producidos_BELLI/$valor_hora)*$horas_laboradas;

$min_producidos_BELLII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','BELL')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_BELLII = ($min_producidos_BELLII/$valor_hora)*$horas_laboradas;
/*
$proyeccion_min_BELLII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','BELL')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_BELL = Team_modulo::where('cliente','BELL')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_BELL == 0){
    $eficiencia_BELL = 0;
}else{
    $eficiencia_BELL = (($proyeccion_min_BELLI+$proyeccion_min_BELLII) / $min_pres_netos_BELL)*100;
}

$min_producidos_WPI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','WP')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_WPI = ($min_producidos_WPI/$valor_hora)*$horas_laboradas;

$min_producidos_WPII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','WP')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_WPII = ($min_producidos_WPII/$valor_hora)*$horas_laboradas;
/*
$proyeccion_min_WPII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','WP')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_WP = Team_modulo::where('cliente','WP')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_WP == 0){
    $eficiencia_WP = 0;
}else{
    $eficiencia_WP = (($proyeccion_min_WPI+$proyeccion_min_WPII) / $min_pres_netos_WP)*100;
}

$min_producidos_HOOEYI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','HOOEY')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_HOOEYI = ($min_producidos_HOOEYI/$valor_hora)*$horas_laboradas;

$min_producidos_HOOEYII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','HOOEY')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_HOOEYII = ($min_producidos_HOOEYII/$valor_hora)*$horas_laboradas;

/*
$proyeccion_min_HOOEYII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','HOOEY')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_HOOEY = Team_modulo::where('cliente','HOOEY')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

//dd($min_pres_netos_HOOEY);

if($min_pres_netos_HOOEY == 0){
    $eficiencia_HOOEY = 0;
}else{
    $eficiencia_HOOEY = (($proyeccion_min_HOOEYI+$proyeccion_min_HOOEYII) / $min_pres_netos_HOOEY)*100;
}

$min_producidos_RVI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','RV')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_RVI = ($min_producidos_RVI/$valor_hora)*$horas_laboradas;


$min_producidos_RVII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','RV')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_RVII = ($min_producidos_RVII/$valor_hora)*$horas_laboradas;

/*
$proyeccion_min_RVII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','RV')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_RV = Team_modulo::where('cliente','RV')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_RV == 0){
    $eficiencia_RV = 0;
}else{
    $eficiencia_RV = (($proyeccion_min_RVI+$proyeccion_min_RVII) / $min_pres_netos_RV)*100;
}

$min_producidos_HANESI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','HANES')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_HANESI = ($min_producidos_HANESI/$valor_hora)*$horas_laboradas;


$min_producidos_HANESII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','HANES')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_HANESII = ($min_producidos_HANESII/$valor_hora)*$horas_laboradas;

/*
$proyeccion_min_RVII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','RV')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_HANES = Team_modulo::where('cliente','HANES')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_HANES == 0){
    $eficiencia_HANES = 0;
}else{
    $eficiencia_HANES = (($proyeccion_min_HANESI+$proyeccion_min_HANESII) / $min_pres_netos_HANES)*100;
}

$min_producidos_STARBOARDI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','STARBOARD')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_STARBOARDI = ($min_producidos_STARBOARDI/$valor_hora)*$horas_laboradas;


$min_producidos_STARBOARDII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','STARBOARD')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_STARBOARDII = ($min_producidos_STARBOARDII/$valor_hora)*$horas_laboradas;

/*
$proyeccion_min_RVII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
->where('cliente','RV')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
$min_pres_netos_STARBOARD = Team_modulo::where('cliente','STARBOARD')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_STARBOARD == 0){
    $eficiencia_STARBOARD = 0;
}else{
    $eficiencia_STARBOARD = (($proyeccion_min_STARBOARDI+$proyeccion_min_STARBOARDII) / $min_pres_netos_STARBOARD)*100;
}

$min_producidos_MARELI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','MAREL')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_MARELI = ($min_producidos_MARELI/$valor_hora)*$horas_laboradas;


$min_producidos_MARELII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
->where('cliente','MAREL')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

$proyeccion_min_MARELII = ($min_producidos_MARELII/$valor_hora)*$horas_laboradas;

$min_pres_netos_MAREL = Team_modulo::where('cliente','MAREL')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_MAREL == 0){
    $eficiencia_MAREL = 0;
}else{
    $eficiencia_MAREL = (($proyeccion_min_MARELI+$proyeccion_min_MARELII) / $min_pres_netos_MAREL)*100;
}



$min_producidos_Empaque = Tickets_empaque::whereDate('ticket_empaque.fecha',$actual)
->whereTime('ticket_empaque.updated_at','=',$hora3)
->sum('cantidad');

$proyeccion_min_Empaque = ($min_producidos_Empaque/$valor_hora)*$horas_laboradas;
$min_pres_netos_Empaque = Team_modulo::where('cliente','Empaque')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

if($min_pres_netos_Empaque == 0){
    $eficiencia_Empaque = 0;
}else{
    $eficiencia_Empaque = ($proyeccion_min_Empaque / $min_pres_netos_Empaque)*100;
}


/**********************acumulado******************* */
$cantidad_acum=0;
/*      for($i=1;$i<=$dia_semana;$i++){
    $dia = Formato_P07::sum('cantidad_d'.$i);
    $cantidad_acum = $cantidad_acum +  $dia;
}*/
/*********** x modulos*********** */
$moduloI = Team_modulo::select('modulo','team_leader',  DB::raw("SUM(piezas_meta) as piezas_meta"),  DB::raw("SUM(min_presencia_netos) as min_presencia_netos"), DB::raw("SUM(min_x_producir) as min_x_producir")  )
->where('planta','IntimarkI')
->where('piezas_meta','<>',0)
->groupby('modulo','team_leader')
->get();

$modulosI = Plan_diar::select('modulo', 'team_leader',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->groupby('modulo','team_leader')
->get();

$moduloII = Team_modulo::select('modulo','team_leader',  DB::raw("SUM(piezas_meta) as piezas_meta"),  DB::raw("SUM(min_presencia_netos) as min_presencia_netos"), DB::raw("SUM(min_x_producir) as min_x_producir")  )
->where('planta','IntimarkII')
->where('piezas_meta','<>',0)
->groupby('modulo','team_leader')
->get();

$modulosII = Plan_diar::select('modulo', 'team_leader',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->groupby('modulo','team_leader')
->get();


/*$modulosII = Plan_diar2::select('modulo', 'team_leader',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('updated_at',$actual)
->whereTime('updated_at',$hora)
->where('piezas','<>',0)
->groupby('modulo','team_leader')
->get();

if($hora <> $hora_actualizacionII){
    $modulosII = Plan_diar2::select('modulo', 'team_leader',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo','team_leader')
    ->get();
}*/


/*********************** x team leader ******************** */

$team_leaderI = Team_modulo::select('team_leader',   DB::raw("SUM(piezas_meta) as piezas_meta"),  DB::raw("SUM(min_presencia_netos) as min_presencia_netos"), DB::raw("SUM(min_x_producir) as min_x_producir")  )
->where('planta','IntimarkI')
->where('piezas_meta','<>',0)
->groupby('team_leader')
->get();

$teams_leaderI = Plan_diar::select('team_leader',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->groupby('team_leader')
->get();

$team_leaderII = Team_modulo::select('team_leader',  DB::raw("SUM(piezas_meta) as piezas_meta"),  DB::raw("SUM(min_presencia_netos) as min_presencia_netos"), DB::raw("SUM(min_x_producir) as min_x_producir")  )
->where('planta','IntimarkII')
->where('piezas_meta','<>',0)
->groupby('team_leader')
->get();

$teams_leaderII = Plan_diar::select('team_leader',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->groupby('team_leader')
->get();

/******************planeacion ************* */
$planeacionI = Team_modulo::select('team_leader', 'cliente', 'modulo','piezas_meta',  DB::raw("SUM(piezas_meta) as piezas_meta"),  DB::raw("SUM(min_presencia_netos) as min_presencia_netos"), DB::raw("SUM(min_x_producir) as min_x_producir")  )
->where('planta','IntimarkI')
->where('piezas_meta','<>',0)
->groupby('cliente','team_leader', 'modulo', 'piezas_meta')
->orderby('cliente', 'desc')
->get();

$planeacionII = Team_modulo::select('team_leader', 'cliente', 'modulo','piezas_meta',  DB::raw("SUM(piezas_meta) as piezas_meta"),  DB::raw("SUM(min_presencia_netos) as min_presencia_netos"), DB::raw("SUM(min_x_producir) as min_x_producir")  )
->where('planta','IntimarkII')
->where('piezas_meta','<>',0)
->groupby('cliente','team_leader', 'modulo' , 'piezas_meta')
->orderby('cliente', 'desc')
->get();

$planeacion_09 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"09:35")
->groupby('modulo')
->get();

$planeacion_09II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"09:35")
->groupby('modulo')
->get();
/*
$planeacion_09II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('updated_at',$actual)
->whereTime('updated_at','09:00')
->where('piezas','<>',0)
->groupby('modulo')
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_09II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_10 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"10:35")
->groupby('modulo')
->get();

$planeacion_10II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"10:35")
->groupby('modulo')
->get();
/*
$planeacion_10II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"10:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_10II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_11 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"11:35")
->groupby('modulo')
->get();

$planeacion_11II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"11:35")
->groupby('modulo')
->get();
/*
$planeacion_11II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"11:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_11II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_12 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"12:35")
->groupby('modulo')
->get();

$planeacion_12II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"12:35")
->groupby('modulo')
->get();
/*
$planeacion_12II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"12:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_12II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_13 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"13:35")
->groupby('modulo')
->get();

$planeacion_13II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"13:35")
->groupby('modulo')
->get();
/*
$planeacion_13II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"13:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_13II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_14 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"14:35")
->groupby('modulo')
->get();

$planeacion_14II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"14:35")
->groupby('modulo')
->get();
/*
$planeacion_14II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"14:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_14II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_15 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"15:35")
->groupby('modulo')
->get();

$planeacion_15II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"15:35")
->groupby('modulo')
->get();
/*
$planeacion_15II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"15:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_15II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_16 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"16:35")
->groupby('modulo')
->get();

$planeacion_16II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"16:35")
->groupby('modulo')
->get();
/*
$planeacion_16II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"16:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_16II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_17= Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"17:35")
->groupby('modulo')
->get();

$planeacion_17II= Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"17:35")
->groupby('modulo')
->get();

/*
$planeacion_17II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"17:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_17II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/

$planeacion_18 = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"18:35")
->groupby('modulo')
->get();

$planeacion_18II = Plan_diar::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
->whereDate('fecha',$actual)
->whereTime('ticket_offline.created_at','<=',"18:35")
->groupby('modulo')
->get();
/*

$planeacion_18II = Plan_diar2::join('team_modulo','planeacion_diaria.Modulo','team_modulo.Modulo')
->select('planeacion_diaria.piezas','planeacion_diaria.min_producidos','planeacion_diaria.proyeccion_minutos', 'planeacion_diaria.efic','planeacion_diaria.Modulo')
->whereDate('planeacion_diaria.updated_at',$actual)
->whereTime('planeacion_diaria.updated_at',"18:00")
->get();

if($hora <> $hora_actualizacionII){
    $planeacion_18II = Plan_diar2::select('modulo',DB::raw("SUM(piezas) as piezas"),DB::raw("SUM(min_producidos) as min_producidos"),DB::raw("SUM(proyeccion_minutos) as proyeccion_minutos"))
    ->whereDate('updated_at',$actual)
    ->whereTime('updated_at',$hora_actualizacionII)
    ->where('piezas','<>',0)
    ->groupby('modulo')
    ->get();
}*/


$planeacion_meta = Team_modulo::get();

/**************planeacion semanal ********** */
$cantidad_diaria = Plan_diar::whereDate('updated_at',$actual)->whereTime('updated_at',$hora)->sum('piezas');
$hora_actualiza = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

$ultima_actualizacionI = Plan_diar::join('team_modulo','Team_modulo.Modulo','ticket_offline.Modulo')
->where('team_modulo.planta','IntimarkI')
->whereDate('ticket_offline.fecha',$actual)
->select('ticket_offline.created_at')->orderby('ticket_offline.created_at','desc')->first();

if($ultima_actualizacionI)
    $hora_actualizacionI =date("H:i:s", strtotime($ultima_actualizacionI->created_at));
else
    $hora_actualizacionI = "N/D";

$ultima_actualizacionII = Plan_diar::join('team_modulo','Team_modulo.Modulo','ticket_offline.Modulo')
->where('team_modulo.planta','IntimarkII')
->whereDate('ticket_offline.fecha',$actual)
->select('ticket_offline.created_at')->orderby('ticket_offline.created_at','desc')->first();

if($ultima_actualizacionII)
    $hora_actualizacionII =date("H:i:s", strtotime($ultima_actualizacionII->created_at));
else
    $hora_actualizacionII = "N/D";

/*
$ultima_actualizacionII = Plan_diar2::join('team_modulo','Team_modulo.Modulo','planeacion_diaria.Modulo')
->where('team_modulo.planta','IntimarkII')
->whereDate('planeacion_diaria.updated_at',$actual)
->select('planeacion_diaria.updated_at')->orderby('planeacion_diaria.updated_at','desc')->first();


if($ultima_actualizacionII)
    $hora_actualizacionII =date("H:i", strtotime($ultima_actualizacionII->updated_at));
 else
    $hora_actualizacionII = "N/D";
*/
$proyeccion_min = Plan_diar::whereDate('fecha',$actual)->whereTime('fecha',$hora)->sum('proyeccion_minutos');

if($hora_actualiza){
    $hora_actualizacion =date("H:i", strtotime($hora_actualiza->created_at));

    if($hora3 <> $hora_actualizacion){
        $cantidad_diaria = Plan_diar::whereDate('fecha',$actual)->whereTime('fecha',$hora_actualizacion)->sum('piezas');
        $proyeccion_min = Plan_diar::whereDate('fecha',$actual)->whereTime('fecha',$hora_actualizacion)->sum('proyeccion_minutos');
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
$efic_total = ($proyeccion_min / $min_pres_netos_dia)*100;

$eficiencia_semanal = $efic_total;

/*************real x planta ************ */

   $real_x_plantaI = Plan_diar::join('cat_modulos','cat_modulos.modulo','ticket_offline.modulo')
   ->where('cat_modulos.planta','IntimarkI')
   ->whereDate('ticket_offline.fecha',$actual)
 //  ->whereTime('ticket_offline.fecha','>',$hora)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   $real_x_plantaI830 = Plan_diar::join('cat_modulos','cat_modulos.modulo','ticket_offline.modulo')
   ->where('cat_modulos.planta','IntimarkI')
   ->where('ticket_offline.Modulo','830A')
   ->whereDate('ticket_offline.updated_at',$actual)
   ->whereTime('ticket_offline.updated_at',$hora)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacionI){
        $real_x_plantaI = Plan_diar::join('cat_modulos','cat_modulos.modulo','ticket_offline.modulo')
        ->where('cat_modulos.planta','IntimarkI')
        ->where('ticket_offline.Modulo','<>','830A')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacionI)
        ->sum('piezas');

        $min_producidos_plantaI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

        $proyeccion_min_plantaI = ($min_producidos_plantaI/$valor_hora2)*$horas_laboradas;

        $proyeccion_min_EmpaqueI = Tickets_empaque::where('modulo','<>','831A')
            ->whereDate('ticket_empaque.fecha',$actual)
            ->whereTime('ticket_empaque.updated_at','=',$hora_actualizacionI)
            ->sum('cantidad');
        $proyeccion_min_EmpaqueI = ($proyeccion_min_EmpaqueI/$valor_hora2)*$horas_laboradas;

        $min_pres_netos_EmpaqueI = Team_modulo::where('modulo','<>','831A')->where('cliente','Empaque')->sum('min_presencia_netos');
       // dd($proyeccion_min_EmpaqueI,$valor_hora2,$horas_laboradas);
        if($min_pres_netos_plantaI == 0){
            $eficiencia_plantaI = 0;
        }else{
            $eficiencia_plantaI = (($proyeccion_min_plantaI +  $proyeccion_min_EmpaqueI) / ($min_pres_netos_plantaI + $min_pres_netos_EmpaqueI) )*100;
        }
    }


/*
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
     //   ->where('planeacion_diaria.Modulo','<>','831A')
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

            $min_pres_netos_plantaII = Team_modulo::where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('min_presencia_netos');

        if($min_pres_netos_plantaII == 0 || $proyeccion_min_plantaII == 0){
            $eficiencia_plantaII = 0;
        }else{
           $eficiencia_plantaII = ($proyeccion_min_plantaII / $min_pres_netos_plantaII)*100;
        }*/

      /*//  $proyeccion_min_plantaI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora3)->sum('ticket_offline.proyeccion_minutos');

        $proyeccion_min_plantaII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)->sum('planeacion_diaria.proyeccion_minutos');*/

  /*  }else{
     /*   $proyeccion_min_plantaI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora)->sum('ticket_offline.proyeccion_minutos');

        $proyeccion_min_plantaII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('planeacion_diaria.proyeccion_minutos');*/
   /* } */


   $real_x_plantaII = Plan_diar::join('cat_modulos','cat_modulos.modulo','ticket_offline.modulo')
   ->where('cat_modulos.planta','IntimarkII')
   ->whereDate('ticket_offline.fecha',$actual)
 //  ->whereTime('ticket_offline.fecha','>',$hora)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   $real_x_plantaI831 = Plan_diar::join('cat_modulos','cat_modulos.modulo','ticket_offline.modulo')
   ->where('cat_modulos.planta','IntimarkII')
   ->where('ticket_offline.Modulo','831A')
   ->whereDate('ticket_offline.updated_at',$actual)
   ->whereTime('ticket_offline.updated_at',$hora)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacionI){
        $real_x_plantaII = Plan_diar::join('cat_modulos','cat_modulos.modulo','ticket_offline.modulo')
        ->where('cat_modulos.planta','IntimarkII')
        ->where('ticket_offline.Modulo','<>','831A')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacionI)
        ->sum('piezas');

        $min_producidos_plantaII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

        $proyeccion_min_plantaII = ($min_producidos_plantaII/$valor_hora2)*$horas_laboradas;

        $proyeccion_min_EmpaqueII = Tickets_empaque::where('modulo','<>','830A')
            ->whereDate('ticket_empaque.fecha',$actual)
            ->whereTime('ticket_empaque.updated_at','=',$hora_actualizacionI)
            ->sum('cantidad');
        $proyeccion_min_EmpaqueII = ($proyeccion_min_EmpaqueII/$valor_hora2)*$horas_laboradas;

        $min_pres_netos_EmpaqueII = Team_modulo::where('modulo','<>','830A')->where('cliente','Empaque')->sum('min_presencia_netos');
       // dd($proyeccion_min_EmpaqueI,$valor_hora2,$horas_laboradas);
        if($min_pres_netos_plantaII == 0){
            $eficiencia_plantaII = 0;
        }else{
            $eficiencia_plantaII = (($proyeccion_min_plantaII +  $proyeccion_min_EmpaqueII) / ($min_pres_netos_plantaII + $min_pres_netos_EmpaqueII) )*100;
        }
    }

     $eficiencia_planta = (($proyeccion_min_plantaI +  $proyeccion_min_EmpaqueI + $proyeccion_min_plantaII  ) / ($min_pres_netos_plantaI + $min_pres_netos_EmpaqueI + $min_pres_netos_plantaI ))*100;

   /*************real x cliente ************ */
            $proyeccion_min_VSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
           ->where('cliente','VS')->whereDate('ticket_offline.fecha',$actual)->sum('proyeccion_minutos');

           $min_pres_netos_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

           if($min_pres_netos_VSI == 0){
               $eficiencia_VSI = 0;
           }else{
               $eficiencia_VSI = (($proyeccion_min_VSI) / $min_pres_netos_VSI)*100;
           }

           $proyeccion_min_VSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
           ->where('cliente','VS')->whereDate('ticket_offline.fecha',$actual)->sum('proyeccion_minutos');

      /*     $proyeccion_min_VSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
           ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->sum('proyeccion_minutos');*/

           $min_pres_netos_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

           if($min_pres_netos_VSII == 0){
               $eficiencia_VSII = 0;
           }else{
               $eficiencia_VSII = (($proyeccion_min_VSII) / $min_pres_netos_VSII)*100;
           }

           $real_VSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
           ->where('team_modulo.cliente','VS')
           ->whereDate('ticket_offline.fecha',$actual)
           ->whereTime('ticket_offline.fecha','<=',$hora3)
           ->where('planta','IntimarkI')
           ->sum('piezas');

           if($hora3 <> $hora_actualizacion){
                $real_VSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                ->where('team_modulo.cliente','VS')
                ->whereDate('ticket_offline.fecha',$actual)
                ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                ->where('planta','IntimarkI')
                ->sum('piezas');

                $proyeccion_min_VSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                ->where('cliente','VS')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->where('planta','IntimarkI')->sum('proyeccion_minutos');

                if($min_pres_netos_VSI == 0){
                    $eficiencia_VSI = 0;
                }else{
                    $eficiencia_VSI = ($proyeccion_min_VSI / $min_pres_netos_VSI)*100;
                }
            }

            $real_VSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
            ->where('team_modulo.cliente','VS')
            ->whereDate('ticket_offline.fecha',$actual)
            ->whereTime('ticket_offline.fecha','<=',$hora3)
            ->where('planta','IntimarkII')
            ->sum('piezas');

            if($hora3 <> $hora_actualizacion){
                 $real_VSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                 ->where('team_modulo.cliente','VS')
                 ->whereDate('ticket_offline.fecha',$actual)
                 ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                 ->where('planta','IntimarkII')
                 ->sum('piezas');

                 $proyeccion_min_VSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','VS')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->where('planta','IntimarkI')->sum('proyeccion_minutos');

                 if($min_pres_netos_VSII == 0){
                     $eficiencia_VSII = 0;
                 }else{
                     $eficiencia_VSII = ($proyeccion_min_VSII / $min_pres_netos_VSII)*100;
                 }
             }
/*

            $real_VSII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
            ->where('team_modulo.cliente','VS')
            ->whereDate('planeacion_diaria.updated_at',$actual)
            ->whereTime('planeacion_diaria.updated_at',$hora)
            ->where('planta','IntimarkII')
            ->sum('piezas');

            if($hora3 <> $hora_actualizacionII){
                 $real_VSII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                 ->where('team_modulo.cliente','VS')
                 ->whereDate('planeacion_diaria.updated_at',$actual)
                 ->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)
                 ->where('planta','IntimarkII')
                 ->sum('piezas');

                 $proyeccion_min_VSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                 ->where('cliente','VS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)->where('planta','IntimarkII')->sum('proyeccion_minutos');

                 if($min_pres_netos_VSII == 0){
                     $eficiencia_VSII = 0;
                 }else{
                     $eficiencia_VSII = ($proyeccion_min_VSII / $min_pres_netos_VSII)*100;
                 }
               }*/

                $real_VS = $real_VSI+$real_VSII;

                $proyeccion_min_CHICOS = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                ->where('cliente','CHICOS')->whereDate('ticket_offline.fecha',$actual)->sum('proyeccion_minutos');

                $min_pres_netos_CHICOSI = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                if($min_pres_netos_CHICOSI == 0){
                    $eficiencia_CHICOSI = 0;
                }else{
                    $eficiencia_CHICOSI = (($proyeccion_min_CHICOSI) / $min_pres_netos_CHICOSI)*100;
                }

                $proyeccion_min_CHICOSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                ->where('cliente','CHICOS')->whereDate('ticket_offline.fecha',$actual)->sum('proyeccion_minutos');

               /*
                $proyeccion_min_CHICOSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                ->where('cliente','CHICOS')->whereDate('planeacion_diaria.updated_at',$actual)->sum('proyeccion_minutos');
*/
                $min_pres_netos_CHICOSII = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                if($min_pres_netos_CHICOSII == 0){
                    $eficiencia_CHICOSII = 0;
                }else{
                    $eficiencia_CHICOSII = (($proyeccion_min_CHICOSII) / $min_pres_netos_CHICOSII)*100;
                }

                $real_CHICOSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                ->where('team_modulo.cliente','CHICOS')
                ->whereDate('ticket_offline.fecha',$actual)
                ->whereTime('ticket_offline.fecha','<=',$hora3)
                ->where('planta','IntimarkI')
                ->sum('piezas');

                if($hora3 <> $hora_actualizacion){
                     $real_CHICOSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                     ->where('team_modulo.cliente','CHICOS')
                     ->whereDate('ticket_offline.fecha',$actual)
                     ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                     ->where('planta','IntimarkI')
                     ->sum('piezas');

                     $proyeccion_min_CHICOSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                     ->where('cliente','CHICOS')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->where('planta','IntimarkI')->sum('proyeccion_minutos');

                     if($min_pres_netos_CHICOSI == 0){
                         $eficiencia_CHICOSI = 0;
                     }else{
                         $eficiencia_CHICOSI = ($proyeccion_min_CHICOSI / $min_pres_netos_CHICOSI)*100;
                     }
                 }
                 $real_CHICOSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                 ->where('team_modulo.cliente','CHICOS')
                 ->whereDate('ticket_offline.fecha',$actual)
                 ->whereTime('ticket_offline.fecha','<=',$hora3)
                 ->where('planta','IntimarkII')
                 ->sum('piezas');

                 if($hora3 <> $hora_actualizacion){
                      $real_CHICOSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                      ->where('team_modulo.cliente','CHICOS')
                      ->whereDate('ticket_offline.fecha',$actual)
                      ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                      ->where('planta','IntimarkII')
                      ->sum('piezas');

                      $proyeccion_min_CHICOSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                      ->where('cliente','CHICOS')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->where('planta','IntimarkII')->sum('proyeccion_minutos');

                      if($min_pres_netos_CHICOSII == 0){
                          $eficiencia_CHICOSII = 0;
                      }else{
                          $eficiencia_CHICOSII = ($proyeccion_min_CHICOSII / $min_pres_netos_CHICOSII)*100;
                      }
                  }


                 /*
                 $real_CHICOSII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                 ->where('team_modulo.cliente','CHICOS')
                 ->whereDate('planeacion_diaria.updated_at',$actual)
                 ->whereTime('planeacion_diaria.updated_at',$hora)
                 ->where('planta','IntimarkII')
                 ->sum('piezas');

                 if($hora3 <> $hora_actualizacion){
                      $real_CHICOSII = Plan_diar2::join('team_modulo','team_modulo.modulo','planeacion_diaria.modulo')
                      ->where('team_modulo.cliente','CHICOS')
                      ->whereDate('planeacion_diaria.updated_at',$actual)
                      ->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)
                      ->where('planta','IntimarkII')
                      ->sum('piezas');

                      $proyeccion_min_CHICOSII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
                      ->where('cliente','CHICOS')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora_actualizacionII)->where('planta','IntimarkII')->sum('proyeccion_minutos');

                      if($min_pres_netos_CHICOSII == 0){
                          $eficiencia_CHICOSII = 0;
                      }else{
                          $eficiencia_CHICOSII = ($proyeccion_min_CHICOSII / $min_pres_netos_CHICOSII)*100;
                      }
                    }*/

                     $real_CHICOS = $real_CHICOSI+$real_CHICOSII;

   $real_BN3 = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
   ->where('team_modulo.cliente','BN3')
   ->whereDate('ticket_offline.fecha',$actual)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacion){
        $real_BN3 = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
        ->where('team_modulo.cliente','BN3')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
        ->sum('piezas');

        $min_producidos_BN3I = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','BN3')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_BN3I = ($min_producidos_BN3I/$valor_hora2)*$horas_laboradas;

        $min_producidos_BN3II = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','BN3')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_BN3II = ($min_producidos_BN3II/$valor_hora2)*$horas_laboradas;
/*

        $proyeccion_min_BN3II = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','BN3')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');*/

        $min_pres_netos_BN3 = Team_modulo::where('cliente','BN3')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

        if($min_pres_netos_BN3 == 0){
            $eficiencia_BN3 = 0;
        }else{
            $eficiencia_BN3 = (($proyeccion_min_BN3I+$proyeccion_min_BN3II) / $min_pres_netos_BN3)*100;
        }
    }

   $real_NU = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
   ->where('team_modulo.cliente','NUUDS')
   ->whereDate('ticket_offline.fecha',$actual)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacion){
        $real_NU = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
        ->where('team_modulo.cliente','NUUDS')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
        ->sum('piezas');

        $min_producidos_NUI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','NU')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_NUI = ($min_producidos_NUI/$valor_hora2)*$horas_laboradas;

        $min_producidos_NUII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','NU')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_NUII = ($min_producidos_NUII/$valor_hora2)*$horas_laboradas;
/*
        $proyeccion_min_NUII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','NU')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
        $min_pres_netos_NU = Team_modulo::where('cliente','NU')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

        if($min_pres_netos_NU == 0){
            $eficiencia_NU = 0;
        }else{
            $eficiencia_NU = (($proyeccion_min_NUI+$proyeccion_min_NUII) / $min_pres_netos_NU)*100;
        }
    }

   $real_MARENA = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
   ->where('team_modulo.cliente','MARENA')
   ->whereDate('ticket_offline.fecha',$actual)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacion){
        $real_MARENA = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
        ->where('team_modulo.cliente','MARENA')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
        ->sum('piezas');

        $min_producidos_MARENAI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','MARENA')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_MARENAI = ($min_producidos_MARENAI/$valor_hora2)*$horas_laboradas;

        $min_producidos_MARENAII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','MARENA')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_MARENAII = ($min_producidos_MARENAII/$valor_hora2)*$horas_laboradas;
/*
        $proyeccion_min_MARENAII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','MARENA')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
        $min_pres_netos_MARENA = Team_modulo::where('cliente','MARENA')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

        if($min_pres_netos_MARENA == 0){
            $eficiencia_MARENA = 0;
        }else{
            $eficiencia_MARENA = (($proyeccion_min_MARENAI+$proyeccion_min_MARENAII) / $min_pres_netos_MARENA)*100;
        }
   }

   $real_LECOQ = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
   ->where('team_modulo.cliente','PACIFIC')
   ->whereDate('ticket_offline.fecha',$actual)
   ->whereTime('ticket_offline.fecha',$hora3)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacion){
        $real_LECOQ = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
        ->where('team_modulo.cliente','PACIFIC')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
        ->sum('piezas');

        $min_producidos_PACIFICI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','PACIFIC')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_PACIFICI = ($min_producidos_PACIFICI/$valor_hora2)*$horas_laboradas;

        $min_producidos_PACIFICII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','PACIFIC')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_PACIFICII = ($min_producidos_PACIFICII/$valor_hora2)*$horas_laboradas;

        $min_pres_netos_PACIFIC = Team_modulo::where('cliente','PACIFIC')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

        if($min_pres_netos_PACIFIC == 0){
            $eficiencia_PACIFIC = 0;
        }else{
            $eficiencia_PACIFIC = (($proyeccion_min_PACIFICI+$proyeccion_min_PACIFICII) / $min_pres_netos_PACIFIC)*100;
        }
   }

   $real_BELL = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
   ->where('team_modulo.cliente','BELL')
   ->whereDate('ticket_offline.fecha',$actual)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   if($hora3 <> $hora_actualizacion){
        $real_BELL = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
        ->where('team_modulo.cliente','BELL')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
        ->sum('piezas');

        $min_producidos_BELLI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','BELL')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_BELLI = ($min_producidos_BELLI/$valor_hora2)*$horas_laboradas;

        $min_producidos_BELLII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','BELL')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_BELLII = ($min_producidos_BELLII/$valor_hora2)*$horas_laboradas;
/*

        $proyeccion_min_BELLII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','BELL')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
        $min_pres_netos_BELL = Team_modulo::where('cliente','BELL')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

        if($min_pres_netos_BELL == 0){
            $eficiencia_BELL = 0;
        }else{
            $eficiencia_BELL = (($proyeccion_min_BELLI+$proyeccion_min_BELLII) / $min_pres_netos_BELL)*100;
        }

   }

   $real_WP = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
   ->where('team_modulo.cliente','WP')
   ->whereDate('ticket_offline.fecha',$actual)
   ->whereTime('ticket_offline.fecha','<=',$hora3)
   ->sum('piezas');

   if($hora <> $hora_actualizacion){
        $real_WP = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
        ->where('team_modulo.cliente','WP')
        ->whereDate('ticket_offline.fecha',$actual)
        ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
        ->sum('piezas');

        $min_producidos_WPI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','WP')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_WPI = ($min_producidos_WPI/$valor_hora2)*$horas_laboradas;


        $min_producidos_WPII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
        ->where('cliente','WP')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

        $proyeccion_min_WPII = ($min_producidos_WPII/$valor_hora2)*$horas_laboradas;
/*
        $proyeccion_min_WPII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
        ->where('cliente','WP')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
        $min_pres_netos_WP = Team_modulo::where('cliente','WP')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

        if($min_pres_netos_WP == 0){
            $eficiencia_WP = 0;
        }else{
            $eficiencia_WP = (($proyeccion_min_WPI+$proyeccion_min_WPII) / $min_pres_netos_WP)*100;
        }
    }


    $real_HOOEY = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
    ->where('team_modulo.cliente','HOOEY')
    ->whereDate('ticket_offline.fecha',$actual)
    ->whereTime('ticket_offline.fecha','<=',$hora3)
    ->sum('piezas');

    if($hora3 <> $hora_actualizacion){
         $real_HOOEY = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
         ->where('team_modulo.cliente','HOOEY')
         ->whereDate('ticket_offline.fecha',$actual)
         ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
         ->sum('piezas');

         $min_producidos_HOOEYI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
         ->where('cliente','HOOEY')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

         $proyeccion_min_HOOEYI = ($min_producidos_HOOEYI/$valor_hora2)*$horas_laboradas;

         $min_producidos_HOOEYII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
         ->where('cliente','HOOEY')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

         $proyeccion_min_HOOEYII = ($min_producidos_HOOEYII/$valor_hora2)*$horas_laboradas;
/*
         $proyeccion_min_HOOEYII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
         ->where('cliente','HOOEY')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
         $min_pres_netos_HOOEY = Team_modulo::where('cliente','HOOEY')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

         if($min_pres_netos_HOOEY == 0){
             $eficiencia_HOOEY = 0;
         }else{
             $eficiencia_HOOEY = (($proyeccion_min_HOOEYI+$proyeccion_min_HOOEYII) / $min_pres_netos_HOOEY)*100;
         }
     }

     $real_RV = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
     ->where('team_modulo.cliente','RV')
     ->whereDate('ticket_offline.fecha',$actual)
     ->whereTime('ticket_offline.fecha','<=',$hora3)
     ->sum('piezas');

     if($hora3 <> $hora_actualizacion){
          $real_RV = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
          ->where('team_modulo.cliente','RV')
          ->whereDate('ticket_offline.fecha',$actual)
          ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
          ->sum('piezas');

          $min_producidos_RVI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
          ->where('cliente','RV')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

          $proyeccion_min_RVI = ($min_producidos_RVI/$valor_hora2)*$horas_laboradas;

          $min_producidos_RVII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
          ->where('cliente','RV')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

          $proyeccion_min_RVII = ($min_producidos_RVII/$valor_hora2)*$horas_laboradas;
/*
          $proyeccion_min_RVII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
          ->where('cliente','RV')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
          $min_pres_netos_RV = Team_modulo::where('cliente','RV')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

          if($min_pres_netos_RV == 0){
              $eficiencia_RV = 0;
          }else{
              $eficiencia_RV = (($proyeccion_min_RVI+$proyeccion_min_RVII) / $min_pres_netos_RV)*100;
          }
      }

      $real_HANES = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
     ->where('team_modulo.cliente','HANES')
     ->whereDate('ticket_offline.fecha',$actual)
     ->whereTime('ticket_offline.fecha','<=',$hora3)
     ->sum('piezas');

     if($hora3 <> $hora_actualizacion){
          $real_HANES = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
          ->where('team_modulo.cliente','HANES')
          ->whereDate('ticket_offline.fecha',$actual)
          ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
          ->sum('piezas');

          $min_producidos_HANESI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
          ->where('cliente','HANES')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

          $proyeccion_min_HANESI = ($min_producidos_HANESI/$valor_hora2)*$horas_laboradas;

          $min_producidos_HANESII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
          ->where('cliente','HANES')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

          $proyeccion_min_HANESII = ($min_producidos_HANESII/$valor_hora2)*$horas_laboradas;
/*
          $proyeccion_min_RVII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
          ->where('cliente','RV')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
*/
          $min_pres_netos_HANES = Team_modulo::where('cliente','HANES')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

          if($min_pres_netos_HANES == 0){
              $eficiencia_HANES = 0;
          }else{
              $eficiencia_HANES = (($proyeccion_min_HANESI+$proyeccion_min_HANESII) / $min_pres_netos_HANES)*100;
          }
      }
      $real_STARBOARD = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
      ->where('team_modulo.cliente','STARBOARD')
      ->whereDate('ticket_offline.fecha',$actual)
      ->whereTime('ticket_offline.fecha','<=',$hora3)
      ->sum('piezas');

      if($hora3 <> $hora_actualizacion){
           $real_STARBOARD = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
           ->where('team_modulo.cliente','STARBOARD')
           ->whereDate('ticket_offline.fecha',$actual)
           ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
           ->sum('piezas');

           $min_producidos_STARBOARDVI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
           ->where('cliente','STARBOARD')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

           $proyeccion_min_STARBOARDI = ($min_producidos_STARBOARDI/$valor_hora2)*$horas_laboradas;

           $min_producidos_STARBOARDII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
           ->where('cliente','STARBOARD')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

           $proyeccion_min_STARBOARDII = ($min_producidos_STARBOARDII/$valor_hora2)*$horas_laboradas;
 /*
           $proyeccion_min_RVII = Plan_diar2::join('team_modulo','team_modulo.Modulo','planeacion_diaria.Modulo')
           ->where('cliente','RV')->where('planta','IntimarkII')->whereDate('planeacion_diaria.updated_at',$actual)->whereTime('planeacion_diaria.updated_at',$hora)->sum('proyeccion_minutos');
 */
           $min_pres_netos_STARBOARD = Team_modulo::where('cliente','STARBOARD')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

           if($min_pres_netos_STARBOARD == 0){
               $eficiencia_STARBOARD = 0;
           }else{
               $eficiencia_STARBOARD = (($proyeccion_min_STARBOARDI+$proyeccion_min_STARBOARDII) / $min_pres_netos_STARBOARD)*100;
           }
       }

       $real_MAREL = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
       ->where('team_modulo.cliente','MAREL')
       ->whereDate('ticket_offline.fecha',$actual)
       ->whereTime('ticket_offline.fecha','<=',$hora3)
       ->sum('piezas');

       if($hora3 <> $hora_actualizacion){
            $real_MAREL = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
            ->where('team_modulo.cliente','MAREL')
            ->whereDate('ticket_offline.fecha',$actual)
            ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
            ->sum('piezas');

            $min_producidos_MARELI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
            ->where('cliente','MAREL')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

            $proyeccion_min_MARELI = ($min_producidos_MARELI/$valor_hora2)*$horas_laboradas;

            $min_producidos_MARELII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
            ->where('cliente','MAREL')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)->sum('min_producidos');

            $proyeccion_min_MARELII = ($min_producidos_MARELII/$valor_hora2)*$horas_laboradas;
            $min_pres_netos_MAREL = Team_modulo::where('cliente','MAREL')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

            if($min_pres_netos_MAREL == 0){
                $eficiencia_MAREL = 0;
            }else{
                $eficiencia_MAREL = (($proyeccion_min_MARELI+$proyeccion_min_MARELII) / $min_pres_netos_MAREL)*100;
            }
        }

     $real_Empaque = Tickets_empaque::whereDate('ticket_empaque.fecha',$actual)
     ->whereTime('ticket_empaque.updated_at','=',$hora3)
     ->sum('cantidad');

     if($hora3 <> $hora_actualizacionI){
        $real_Empaque = Tickets_empaque::whereDate('ticket_empaque.fecha',$actual)
         ->whereTime('ticket_empaque.updated_at','=',$hora_actualizacionI)
         //->sum('piezas');
         ->sum('cantidad');

         $min_producidos_Empaque = Tickets_empaque::whereDate('ticket_empaque.fecha',$actual)
        ->whereTime('ticket_empaque.updated_at','=',$hora_actualizacionI)
        ->sum('cantidad');

        $proyeccion_min_Empaque = ($min_producidos_Empaque/$valor_hora)*$horas_laboradas;
        //dd($min_producidos_Empaque ,$valor_hora, $horas_laboradas);
        $min_pres_netos_Empaque = Team_modulo::where('cliente','Empaque')->where('piezas_meta','<>','0')->sum('min_presencia_netos');
        //dd($min_pres_netos_Empaque,  $proyeccion_min_Empaque);

        if($min_pres_netos_Empaque == 0){
            $eficiencia_Empaque = 0;
        }else{
            $eficiencia_Empaque = ($proyeccion_min_Empaque / $min_pres_netos_Empaque)*100;
        }

     }
     /************** verificar si existe en ambas plantas ********** */
     $VS_plantaI =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $VS_plantaII =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $CHICOS_plantaI =  Team_Modulo::where('cliente','CHICOS')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $CHICOS_plantaII =  Team_Modulo::where('cliente','CHICOS')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $BN3_plantaI =  Team_Modulo::where('cliente','BN3')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $BN3_plantaII =  Team_Modulo::where('cliente','BN3')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $NU_plantaI =  Team_Modulo::where('cliente','NU')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $NU_plantaII =  Team_Modulo::where('cliente','NU')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $MARENA_plantaI =  Team_Modulo::where('cliente','MARENA')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $MARENA_plantaII =  Team_Modulo::where('cliente','MARENA')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $PACIFIC_plantaI =  Team_Modulo::where('cliente','PACIFIC')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $PACIFIC_plantaII =  Team_Modulo::where('cliente','PACIFIC')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $BELL_plantaI =  Team_Modulo::where('cliente','BELL')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $BELL_plantaII =  Team_Modulo::where('cliente','BELL')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $WP_plantaI =  Team_Modulo::where('cliente','WP')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $WP_plantaII =  Team_Modulo::where('cliente','WP')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $HOOEY_plantaI =  Team_Modulo::where('cliente','HOOEY')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $HOOEY_plantaII =  Team_Modulo::where('cliente','HOOEY')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $RV_plantaI =  Team_Modulo::where('cliente','RV')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $RV_plantaII =  Team_Modulo::where('cliente','RV')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $HANES_plantaI =  Team_Modulo::where('cliente','HANES')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $HANES_plantaII =  Team_Modulo::where('cliente','HANES')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $STARBOARD_plantaI =  Team_Modulo::where('cliente','STARBOARD')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $STARBOARD_plantaII =  Team_Modulo::where('cliente','STARBOARD')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $MAREL_plantaI =  Team_Modulo::where('cliente','MAREL')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $MAREL_plantaII =  Team_Modulo::where('cliente','MAREL')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

     $Empaque_plantaI =  Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('piezas_meta');  //suma del dia
     $Empaque_plantaII =  Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('piezas_meta');  //suma del dia

return view('avanceproduccion', compact('meta','tiempo_desocupacion','horas_laboradas', 'hoy','hora','hora3','cantidad_dia','eficiencia_dia','cantidad_acum','inicio','fin','cantidad_plantaI','cantidad_plantaII','eficiencia_plantaI','eficiencia_plantaII','cantidad_VS','cantidad_CHICOS','cantidad_NU','cantidad_BN3','cantidad_MARENA','cantidad_PACIFIC','cantidad_BELL','cantidad_WP','cantidad_HOOEY','cantidad_RV','cantidad_HANES','cantidad_STARBOARD','cantidad_MAREL','cantidad_Empaque','eficiencia_dia_VS','eficiencia_dia_CHICOS','eficiencia_dia_NU','eficiencia_dia_BN3','eficiencia_dia_PACIFIC','eficiencia_dia_MARENA','eficiencia_dia_BELL','eficiencia_dia_WP','eficiencia_dia_HOOEY','eficiencia_dia_RV','eficiencia_dia_HANES','eficiencia_dia_STARBOARD','eficiencia_dia_MAREL','eficiencia_dia_Empaque','planeacionI','planeacionII','cantidad_semanal','sam_empaque','efic_total','real_x_plantaI','real_x_plantaII','real_VS','real_CHICOS','real_BN3','real_NU','real_MARENA','real_LECOQ','real_BELL','real_WP','real_HOOEY','real_RV','real_HANES','real_STARBOARD','real_MAREL','real_Empaque','cantidad_diaria','eficiencia_semanal','planeacion_meta','horas_laboradas','valor_hora','eficiencia_total','cantidad_total','eficiencia_dia_plantaI','eficiencia_dia_plantaII','eficiencia_VS','eficiencia_CHICOS','eficiencia_BN3','eficiencia_NU','eficiencia_MARENA','eficiencia_PACIFIC','eficiencia_BELL','eficiencia_WP','eficiencia_HOOEY','eficiencia_RV','eficiencia_HANES','eficiencia_STARBOARD','eficiencia_MAREL','eficiencia_Empaque','planeacion_09','planeacion_10','planeacion_11','planeacion_12','planeacion_13','planeacion_14','planeacion_15','planeacion_16','planeacion_17','planeacion_18','hora_actualizacion','plantas','hora_actualizacionII','hora_actualizacionI','real_x_plantaI830','real_x_plantaI831','eficiencia_planta','real_x_plantaI830','real_x_plantaI831','team_leaderII','planeacionII','modulosII','planeacion_09II','planeacion_10II','planeacion_11II','planeacion_12II','planeacion_13II','planeacion_14II','planeacion_15II','planeacion_16II','planeacion_17II','planeacion_18II','hoy_hora','VS_plantaI','VS_plantaII' ,'CHICOS_plantaI','CHICOS_plantaII','BN3_plantaI','BN3_plantaII','NU_plantaI','NU_plantaII','MARENA_plantaI','MARENA_plantaII','PACIFIC_plantaI','PACIFIC_plantaII','BELL_plantaI','BELL_plantaII','WP_plantaI','WP_plantaII','HOOEY_plantaI','HOOEY_plantaII','RV_plantaI','RV_plantaII','HANES_plantaI','HANES_plantaII','STARBOARD_plantaI','MAREL_plantaI','STARBOARD_plantaII','MAREL_plantaII','Empaque_plantaI','Empaque_plantaII','team_leaderI','teams_leaderI','team_leaderII','teams_leaderII','valor_hora2','moduloI','modulosI','moduloII','modulosII'));
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
      $hora3 = date("G", strtotime($hora_aux)).":30";
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
      $hora_actualiza = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

      if($hora_actualiza)
         $hora_actualizacion =date("H:i", strtotime($hora_actualiza->created_at));
     else
         $hora_actualizacion = 0;

    $hora_actualizaII = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

         if($hora_actualizaII)
            $hora_actualizacionII =date("H:i", strtotime($hora_actualizaII->created_at));
        else
            $hora_actualizacionII = 0;

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

         $cantidad_planta_VSI =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
         $cantidad_planta_VSI = ($cantidad_planta_VSI/$horas_laboradas )*$valor_hora;

         $cantidad_planta_VSII =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
         $cantidad_planta_VSII = ($cantidad_planta_VSII/$horas_laboradas )*$valor_hora;

         $min_x_producir_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_x_producir');
         $min_pres_netos_dia_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

         $eficiencia_dia_VSI = ($min_x_producir_dia_VSI/$min_pres_netos_dia_VSI)*100; //* promedio del dia

         $min_x_producir_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_x_producir');
         $min_pres_netos_dia_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

         $eficiencia_dia_VSII = ($min_x_producir_dia_VSII/$min_pres_netos_dia_VSII)*100; //* promedio del dia


         $min_producidos_VSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
         ->where('cliente','VS')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

         $proyeccion_min_VSI = ($min_producidos_VSI/$valor_hora)*$horas_laboradas;

         $min_pres_netos_VSI = Team_modulo::where('cliente','VS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

         if($min_pres_netos_VSI == 0){
             $eficiencia__VSI = 0;
         }else{
             $eficiencia__VSI = (($proyeccion_min_VSI) / $min_pres_netos_VSI)*100;
         }

         $min_producidos_VSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
         ->where('cliente','VS')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

         $proyeccion_min_VSII = ($min_producidos_VSII/$valor_hora)*$horas_laboradas;

         $min_pres_netos_VSII = Team_modulo::where('cliente','VS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

         if($min_pres_netos_VSII == 0){
             $eficiencia__VSII = 0;
         }else{
             $eficiencia__VSII = (($proyeccion_min_VSII) / $min_pres_netos_VSII)*100;
         }

     $real_VSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
     ->where('team_modulo.cliente','VS')
     ->whereDate('ticket_offline.fecha',$actual)
     ->whereTime('ticket_offline.fecha','<=',$hora3)
     ->where('planta','IntimarkI')
     ->sum('piezas');



     if($hora3 <> $hora_actualizacion){
          $real_VSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
          ->where('team_modulo.cliente','VS')
          ->whereDate('ticket_offline.fecha',$actual)
          ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
          ->where('planta','IntimarkI')
          ->sum('piezas');

          $min_producidos_VSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
          ->where('cliente','VS')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

          $proyeccion_min_VSI = ($min_producidos_VSI/$valor_hora)*$horas_laboradas;

          if($min_pres_netos_VSI == 0){
              $eficiencia__VSI = 0;
          }else{
              $eficienci__VSI = ($proyeccion_min_VSI / $min_pres_netos_VSI)*100;
          }
      }


      $real_VSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
     ->where('team_modulo.cliente','VS')
     ->whereDate('ticket_offline.fecha',$actual)
     ->whereTime('ticket_offline.fecha','<=',$hora3)
     ->where('planta','IntimarkII')
     ->sum('piezas');



     if($hora3 <> $hora_actualizacion){
          $real_VSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
          ->where('team_modulo.cliente','VS')
          ->whereDate('ticket_offline.fecha',$actual)
          ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
          ->where('planta','IntimarkII')
          ->sum('piezas');

          $min_producidos_VSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
          ->where('cliente','VS')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

          $proyeccion_min_VSII = ($min_producidos_VSI/$valor_hora)*$horas_laboradas;

          if($min_pres_netos_VSII == 0){
              $eficiencia__VSII = 0;
          }else{
              $eficiencia__VSII = ($proyeccion_min_VSII / $min_pres_netos_VSII)*100;
          }
      }


         return view('detalleVS', compact('cantidad_planta_VSI','cantidad_planta_VSII','eficiencia_dia_VSI','eficiencia_dia_VSII','eficiencia__VSI','eficiencia__VSII','real_VSI','real_VSII'));

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
     $hora3 = date("G", strtotime($hora_aux)).":30";
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

     $ultima_actualizacionII = Tickets_empaque::whereDate('fecha',$actual)
     ->select('updated_at')->orderby('updated_at','desc')->first();


     if($ultima_actualizacionII)
          $hora_actualizacionII =date("H:i:s", strtotime($ultima_actualizacionII->updated_at));
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
//$cantidad_planta_EmpaqueI = ($cantidad_planta_EmpaqueI/$horas_laboradas )*$valor_hora;

$cantidad_planta_EmpaqueII =  Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('min_x_producir');  //suma del dia
//$cantidad_planta_EmpaqueII = ($cantidad_planta_EmpaqueII/$horas_laboradas )*$valor_hora;

$min_x_producir_dia_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('min_x_producir');

$min_pres_netos_dia_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('min_presencia_netos');

if($min_pres_netos_dia_EmpaqueI != 0)
    $eficiencia_dia_EmpaqueI = ($min_x_producir_dia_EmpaqueI/$min_pres_netos_dia_EmpaqueI)*100; //* promedio del dia
else
    $eficiencia_dia_EmpaqueI = 0;


$min_x_producir_dia_EmpaqueII = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('min_x_producir');

$min_pres_netos_dia_EmpaqueII = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('min_presencia_netos');

if($min_pres_netos_dia_EmpaqueII != 0)
    $eficiencia_dia_EmpaqueII = ($min_x_producir_dia_EmpaqueII/$min_pres_netos_dia_EmpaqueII)*100; //* promedio del dia
else
    $eficiencia_dia_EmpaqueII = 0;

$eficiencia_dia_Empaque = (($min_x_producir_dia_EmpaqueII+$min_x_producir_dia_EmpaqueI)/($min_pres_netos_dia_EmpaqueII+$min_pres_netos_dia_EmpaqueI))*100; //* promedio del dia

$min_producidos_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at',$hora)->where('planta','IntimarkI')->sum('cantidad');

$proyeccion_min_EmpaqueI = ($min_producidos_EmpaqueI/$horas_laboradas)*$valor_hora;

$min_pres_netos_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('min_presencia_netos');

if($min_pres_netos_EmpaqueI == 0){
    $eficiencia_EmpaqueI = 0;
}else{
    $eficiencia_EmpaqueI = ($proyeccion_min_EmpaqueI / $min_pres_netos_EmpaqueI)*100;
}

$min_producidos_EmpaqueII = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at',$hora)->where('planta','IntimarkII')->sum('cantidad');

$proyeccion_min_EmpaqueII = ($min_producidos_EmpaqueII/$horas_laboradas)*$valor_hora;

$min_pres_netos_EmpaqueII = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('min_presencia_netos');

if($min_pres_netos_EmpaqueII == 0){
    $eficiencia_EmpaqueII = 0;
}else{
    $eficiencia_EmpaqueII = ($proyeccion_min_EmpaqueII / $min_pres_netos_EmpaqueII)*100;
}

$eficiencia_Empaque =  (($proyeccion_min_EmpaqueII+$proyeccion_min_EmpaqueI) / ($min_pres_netos_EmpaqueII+$min_pres_netos_EmpaqueI))*100;


$real_EmpaqueI = tickets_empaque::where('modulo','<>','831A')->whereDate('ticket_empaque.fecha',$actual)
->whereTime('ticket_empaque.updated_at','=',$hora3)
->sum('cantidad');

if($hora3 <> $hora_actualizacionII){

    $real_EmpaqueI = tickets_empaque::where('modulo','<>','831A')->whereDate('ticket_empaque.fecha',$actual)
    ->whereTime('ticket_empaque.updated_at','=',$hora_actualizacionII)
    ->sum('cantidad');

    $min_producidos_EmpaqueI = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
    ->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at',$hora_actualizacionII)->where('planta','IntimarkI')->sum('cantidad');

    $proyeccion_min_EmpaqueI = ($min_producidos_EmpaqueI/$valor_hora)*$horas_laboradas;

    $min_presencia_netos_EmpaqueI = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkI')->sum('min_presencia_netos');

    if($min_pres_netos_EmpaqueI == 0){
        $eficiencia_EmpaqueI = 0;
    }else{
        $eficiencia_EmpaqueI = ($proyeccion_min_EmpaqueI / $min_pres_netos_EmpaqueI)*100;
    }

 }

 $real_EmpaqueII = tickets_empaque::where('modulo','831A')->whereDate('ticket_empaque.fecha',$actual)
    ->whereTime('ticket_empaque.updated_at','=',$hora3)
    ->sum('cantidad');

 if($hora3 <> $hora_actualizacionII){

    $real_EmpaqueII = tickets_empaque::where('modulo','831A')->whereDate('ticket_empaque.fecha',$actual)
    ->whereTime('ticket_empaque.updated_at','=',$hora_actualizacionII)
    ->sum('cantidad');

    $min_producidos_EmpaqueII = Tickets_empaque::join('team_modulo','team_modulo.Modulo','ticket_empaque.modulo')
    ->where('cliente','Empaque')->whereDate('ticket_empaque.updated_at',$actual)->whereTime('ticket_empaque.updated_at',$hora_actualizacionII)->where('planta','IntimarkII')->sum('cantidad');

    $proyeccion_min_EmpaqueII = ($min_producidos_EmpaqueII/$valor_hora)*$horas_laboradas;

    $min_presencia_netos_EmpaqueII = Team_Modulo::where('cliente','Empaque')->where('planta','IntimarkII')->sum('min_presencia_netos');

    if($min_pres_netos_EmpaqueII == 0){
        $eficiencia_EmpaqueII = 0;
    }else{
        $eficiencia_EmpaqueII = ($proyeccion_min_EmpaqueII / $min_pres_netos_EmpaqueII)*100;
    }
 }
    $eficiencia_Empaque =  (($proyeccion_min_EmpaqueII+$proyeccion_min_EmpaqueI) / ($min_pres_netos_EmpaqueII+$min_pres_netos_EmpaqueI))*100;

return view('detalleEmpaque', compact('cantidad_planta_EmpaqueI','cantidad_planta_EmpaqueII','eficiencia_dia_EmpaqueI','eficiencia_dia_EmpaqueII','eficiencia_EmpaqueI','eficiencia_EmpaqueII','real_EmpaqueI','real_EmpaqueII', 'efic_dia_830A', 'efic_dia_831A'));

}

public function detalleCHICOS(request $request)
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
                  $hora3 = date("G", strtotime($hora_aux)).":30";
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
                  $hora_actualiza = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

                  if($hora_actualiza)
                     $hora_actualizacion =date("H:i", strtotime($hora_actualiza->created_at));
                 else
                     $hora_actualizacion = 0;

                $hora_actualizaII = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();


                     if($hora_actualizaII)
                        $hora_actualizacionII =date("H:i", strtotime($hora_actualizaII->updated_at));
                    else
                        $hora_actualizacionII = 0;


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


                     $cantidad_planta_CHICOSI =  Team_Modulo::where('cliente','CHICOS')->where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                     $cantidad_planta_CHICOSI = ($cantidad_planta_CHICOSI/$horas_laboradas )*$valor_hora;


                     $cantidad_planta_CHICOSII =  Team_Modulo::where('cliente','CHICOS')->where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                     $cantidad_planta_CHICOSII = ($cantidad_planta_CHICOSII/$horas_laboradas )*$valor_hora;


                     $min_x_producir_dia_CHICOSI = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_x_producir');
                     $min_pres_netos_dia_CHICOSI = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                     $eficiencia_dia_CHICOSI = ($min_x_producir_dia_CHICOSI/$min_pres_netos_dia_CHICOSI)*100; //* promedio del dia

                     $min_x_producir_dia_CHICOSII = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_x_producir');
                     $min_pres_netos_dia_CHICOSII = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                     $eficiencia_dia_CHICOSII = ($min_x_producir_dia_CHICOSII/$min_pres_netos_dia_CHICOSII)*100; //* promedio del dia


                     $min_producidos_CHICOSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                     ->where('cliente','CHICOS')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                     $proyeccion_min_CHICOSI = ($min_producidos_CHICOSI/$valor_hora)*$horas_laboradas;

                     $min_pres_netos_CHICOSI = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                     if($min_pres_netos_CHICOSI == 0){
                         $eficiencia_CHICOSI = 0;
                     }else{
                         $eficiencia_CHICOSI = (($proyeccion_min_CHICOSI) / $min_pres_netos_CHICOSI)*100;
                     }

                     $min_producidos_CHICOSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                     ->where('cliente','CHICOS')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                     $proyeccion_min_CHICOSII = ($min_producidos_CHICOSII/$valor_hora)*$horas_laboradas;

                     $min_pres_netos_CHICOSII = Team_modulo::where('cliente','CHICOS')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                     if($min_pres_netos_CHICOSII == 0){
                         $eficiencia_CHICOSII = 0;
                     }else{
                         $eficiencia_CHICOSII = (($proyeccion_min_CHICOSII) / $min_pres_netos_CHICOSII)*100;
                     }

                 $real_CHICOSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                 ->where('team_modulo.cliente','CHICOS')
                 ->whereDate('ticket_offline.fecha',$actual)
                 ->whereTime('ticket_offline.fecha','<=',$hora3)
                 ->where('planta','IntimarkI')
                 ->sum('piezas');

                 if($hora3 <> $hora_actualizacion){
                      $real_CHICOSI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                      ->where('team_modulo.cliente','CHICOS')
                      ->whereDate('ticket_offline.fecha',$actual)
                      ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                      ->where('planta','IntimarkI')
                      ->sum('piezas');

                      $min_producidos_CHICOSI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                      ->where('cliente','CHICOS')->where('planta','IntimarkI')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                      $proyeccion_min_CHICOSI = ($min_producidos_CHICOSI/$valor_hora)*$horas_laboradas;

                      if($min_pres_netos_CHICOSI == 0){
                          $eficiencia_CHICOSI = 0;
                      }else{
                          $eficiencia_CHICOSI = ($proyeccion_min_CHICOSI / $min_pres_netos_CHICOSI)*100;
                      }
                  }


                  $real_CHICOSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','CHICOS')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora3)
                  ->where('planta','IntimarkII')
                  ->sum('piezas');

                  if($hora3 <> $hora_actualizacion){
                       $real_CHICOSII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                       ->where('team_modulo.cliente','CHICOS')
                       ->whereDate('ticket_offline.fecha',$actual)
                       ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                       ->where('planta','IntimarkII')
                       ->sum('piezas');

                       $min_producidos_CHICOSII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                       ->where('cliente','CHICOS')->where('planta','IntimarkII')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                       $proyeccion_min_CHICOSII = ($min_producidos_CHICOSII/$valor_hora)*$horas_laboradas;

                       if($min_pres_netos_CHICOSII == 0){
                           $eficiencia_CHICOSII = 0;
                       }else{
                           $eficiencia_CHICOSII = ($proyeccion_min_CHICOSII / $min_pres_netos_CHICOSII)*100;
                       }
                   }


            return view('detalleCHICOS', compact('cantidad_planta_CHICOSI','cantidad_planta_CHICOSII','eficiencia_dia_CHICOSI','eficiencia_dia_CHICOSII','eficiencia_CHICOSI','eficiencia_CHICOSII','real_CHICOSI','real_CHICOSII'));

        }

        public function detalleBELL(request $request)
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
              $hora3 = date("G", strtotime($hora_aux)).":30";
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
              $hora_actualiza = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

              if($hora_actualiza)
                 $hora_actualizacion =date("H:i", strtotime($hora_actualiza->created_at));
             else
                 $hora_actualizacion = 0;

            $hora_actualizaII = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

                 if($hora_actualizaII)
                    $hora_actualizacionII =date("H:i", strtotime($hora_actualizaII->created_at));
                else
                    $hora_actualizacionII = 0;


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

                 $cantidad_planta_BELLI =  Team_Modulo::where('cliente','VS')->where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                 $cantidad_planta_BELLI = ($cantidad_planta_BELLI/$horas_laboradas )*$valor_hora;

                 $cantidad_planta_BELLII =  Team_Modulo::where('cliente','BELL')->where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                 $cantidad_planta_BELLII = ($cantidad_planta_BELLII/$horas_laboradas )*$valor_hora;

                 $min_x_producir_dia_BELLI = Team_modulo::where('cliente','BELL')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_x_producir');
                 $min_pres_netos_dia_BELLI = Team_modulo::where('cliente','BELL')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 $eficiencia_dia_BELLI = ($min_x_producir_dia_BELLI/$min_pres_netos_dia_BELLI)*100; //* promedio del dia

                 $min_x_producir_dia_BELLII = Team_modulo::where('cliente','BELL')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_x_producir');
                 $min_pres_netos_dia_BELLII = Team_modulo::where('cliente','BELL')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 $eficiencia_dia_BELLII = ($min_x_producir_dia_BELLII/$min_pres_netos_dia_BELLII)*100; //* promedio del dia


                 $min_producidos_BELLI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','BELL')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                 $proyeccion_min_BELLI = ($min_producidos_BELLI/$valor_hora)*$horas_laboradas;

                 $min_pres_netos_BELLI = Team_modulo::where('cliente','BELL')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 if($min_pres_netos_BELLI == 0){
                     $eficiencia_BELLI = 0;
                 }else{
                     $eficiencia_BELLI = (($proyeccion_min_BELLI) / $min_pres_netos_BELLI)*100;
                 }

                 $min_producidos_BELLII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','BELL')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                 $proyeccion_min_BELLII = ($min_producidos_BELLII/$valor_hora)*$horas_laboradas;

                 $min_pres_netos_BELLII = Team_modulo::where('cliente','BELL')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 if($min_pres_netos_BELLII == 0){
                     $eficiencia_BELLII = 0;
                 }else{
                     $eficiencia_BELLII = (($proyeccion_min_BELLII) / $min_pres_netos_BELLII)*100;
                 }

             $real_BELLI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
             ->where('team_modulo.cliente','BELL')
             ->whereDate('ticket_offline.fecha',$actual)
             ->whereTime('ticket_offline.fecha','<=',$hora3)
             ->where('planta','IntimarkI')
             ->sum('piezas');



             if($hora3 <> $hora_actualizacion){
                  $real_BELLI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','BELL')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                  ->where('planta','IntimarkI')
                  ->sum('piezas');

                  $min_producidos_BELLI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                  ->where('cliente','BELL') ->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                  $proyeccion_min_BELLI = ($min_producidos_BELLI/$valor_hora)*$horas_laboradas;

                  if($min_pres_netos_BELLI == 0){
                      $eficiencia_BELLI = 0;
                  }else{
                      $eficiencia_BELLI = ($proyeccion_min_BELLI / $min_pres_netos_BELLI)*100;
                  }
              }


              $real_BELLII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
             ->where('team_modulo.cliente','BELL')
             ->whereDate('ticket_offline.fecha',$actual)
             ->whereTime('ticket_offline.fecha','<=',$hora3)
             ->where('planta','IntimarkII')
             ->sum('piezas');



             if($hora3 <> $hora_actualizacion){
                  $real_BELLII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','BELL')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                  ->where('planta','IntimarkII')
                  ->sum('piezas');

                  $min_producidos_BELLII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                  ->where('cliente','BELL')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                  $proyeccion_min_BELLII = ($min_producidos_BELLI/$valor_hora)*$horas_laboradas;

                  if($min_pres_netos_BELLII == 0){
                      $eficiencia_BELLII = 0;
                  }else{
                      $eficiencia_BELLII = ($proyeccion_min_BELLII / $min_pres_netos_BELLII)*100;
                  }
              }


                 return view('detalleBELL', compact('cantidad_planta_BELLI','cantidad_planta_BELLII','eficiencia_dia_BELLI','eficiencia_dia_BELLII','eficiencia_BELLI','eficiencia_BELLII','real_BELLI','real_BELLII'));

        }

        public function detalleHANES(request $request)
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
              $hora3 = date("G", strtotime($hora_aux)).":30";
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
              $hora_actualiza = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

              if($hora_actualiza)
                 $hora_actualizacion =date("H:i", strtotime($hora_actualiza->created_at));
             else
                 $hora_actualizacion = 0;

            $hora_actualizaII = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

                 if($hora_actualizaII)
                    $hora_actualizacionII =date("H:i", strtotime($hora_actualizaII->created_at));
                else
                    $hora_actualizacionII = 0;


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

                 $cantidad_planta_HANESI =  Team_Modulo::where('cliente','HANES')->where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                 $cantidad_planta_HANESI = ($cantidad_planta_HANESI/$horas_laboradas )*$valor_hora;

                 $cantidad_planta_HANESII =  Team_Modulo::where('cliente','HANES')->where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                 $cantidad_planta_HANESII = ($cantidad_planta_HANESII/$horas_laboradas )*$valor_hora;

                 $min_x_producir_dia_HANESI = Team_modulo::where('cliente','HANES')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_x_producir');
                 $min_pres_netos_dia_HANESI = Team_modulo::where('cliente','HANES')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 $eficiencia_dia_HANESI = ($min_x_producir_dia_HANESI/$min_pres_netos_dia_HANESI)*100; //* promedio del dia

                 $min_x_producir_dia_HANESII = Team_modulo::where('cliente','HANES')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_x_producir');
                 $min_pres_netos_dia_HANESII = Team_modulo::where('cliente','HANES')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 $eficiencia_dia_HANESII = ($min_x_producir_dia_HANESII/$min_pres_netos_dia_HANESII)*100; //* promedio del dia


                 $min_producidos_HANESI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','HANES')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                 $proyeccion_min_HANESI = ($min_producidos_HANESI/$valor_hora)*$horas_laboradas;

                 $min_pres_netos_HANESI = Team_modulo::where('cliente','HANES')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 if($min_pres_netos_HANESI == 0){
                     $eficiencia_HANESI = 0;
                 }else{
                     $eficiencia_HANESI = (($proyeccion_min_HANESI) / $min_pres_netos_HANESI)*100;
                 }

                 $min_producidos_HANESII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','HANES')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                 $proyeccion_min_HANESII = ($min_producidos_HANESII/$valor_hora)*$horas_laboradas;

                 $min_pres_netos_HANESII = Team_modulo::where('cliente','HANES')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 if($min_pres_netos_HANESII == 0 ){
                     $eficiencia_HANESII = 0;
                 }else{
                     $eficiencia_HANESII = (($proyeccion_min_HANESII) / $min_pres_netos_HANESII)*100;
                 }

             $real_HANESI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
             ->where('team_modulo.cliente','HANES')
             ->whereDate('ticket_offline.fecha',$actual)
             ->whereTime('ticket_offline.fecha','<=',$hora3)
             ->where('planta','IntimarkI')
             ->sum('piezas');



             if($hora3 <> $hora_actualizacion){
                  $real_HANESI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','HANES')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                  ->where('planta','IntimarkI')
                  ->sum('piezas');

                  $min_producidos_HANESI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                  ->where('cliente','HANES')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                  $proyeccion_min_HANESI = ($min_producidos_HANESI/$valor_hora)*$horas_laboradas;

                  if($min_pres_netos_HANESI == 0){
                      $eficiencia_HANESI = 0;
                  }else{
                      $eficiencia_HANESI = ($proyeccion_min_HANESI / $min_pres_netos_HANESI)*100;
                  }
              }


              $real_HANESII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
             ->where('team_modulo.cliente','HANES')
             ->whereDate('ticket_offline.fecha',$actual)
             ->whereTime('ticket_offline.fecha','<=',$hora3)
             ->where('planta','IntimarkII')
             ->sum('piezas');



             if($hora3 <> $hora_actualizacion){
                  $real_HANESII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','HANES')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                  ->where('planta','IntimarkII')
                  ->sum('piezas');

                  $min_producidos_HANESII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                  ->where('cliente','HANES')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                  $proyeccion_min_HANESII = ($min_producidos_HANESII/$valor_hora)*$horas_laboradas;

                  if($min_pres_netos_HANESII == 0){
                      $eficiencia_HANESII = 0;
                  }else{
                      $eficiencia_HANESII = ($proyeccion_min_HANESII / $min_pres_netos_HANESII)*100;
                  }
              }
             // dd($eficiencia_HANESII);
              if($real_HANESII == 0)
              {
                 $eficiencia_HANESII = 0;
              }

                 return view('detalleHANES', compact('cantidad_planta_HANESI','cantidad_planta_HANESII','eficiencia_dia_HANESI','eficiencia_dia_HANESII','eficiencia_HANESI','eficiencia_HANESII','real_HANESI','real_HANESII'));

        }

        public function detalleRV(request $request)
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
              $hora3 = date("G", strtotime($hora_aux)).":30";
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
              $hora_actualiza = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

              if($hora_actualiza)
                 $hora_actualizacion =date("H:i", strtotime($hora_actualiza->created_at));
             else
                 $hora_actualizacion = 0;

            $hora_actualizaII = Plan_diar::whereDate('created_at',$actual)->select('created_at')->orderby('created_at','desc')->first();

                 if($hora_actualizaII)
                    $hora_actualizacionII =date("H:i", strtotime($hora_actualizaII->created_at));
                else
                    $hora_actualizacionII = 0;


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

                 $cantidad_planta_RVI =  Team_Modulo::where('cliente','RV')->where('planta','IntimarkI')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                 $cantidad_planta_RVI = ($cantidad_planta_RVI/$horas_laboradas )*$valor_hora;

                 $cantidad_planta_RVII =  Team_Modulo::where('cliente','RV')->where('planta','IntimarkII')->whereDate('updated_at',$actual)->sum('piezas_meta');  //suma del dia
                 $cantidad_planta_RVII = ($cantidad_planta_RVII/$horas_laboradas )*$valor_hora;

                 $min_x_producir_dia_RVI = Team_modulo::where('cliente','RV')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_x_producir');
                 $min_pres_netos_dia_RVI = Team_modulo::where('cliente','RV')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 $eficiencia_dia_RVI = ($min_x_producir_dia_RVI/$min_pres_netos_dia_RVI)*100; //* promedio del dia

                 $min_x_producir_dia_RVII = Team_modulo::where('cliente','RV')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_x_producir');
                 $min_pres_netos_dia_RVII = Team_modulo::where('cliente','RV')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 $eficiencia_dia_RVII = ($min_x_producir_dia_RVII/$min_pres_netos_dia_RVII)*100; //* promedio del dia


                 $min_producidos_RVI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','RV')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                 $proyeccion_min_RVI = ($min_producidos_RVI/$valor_hora)*$horas_laboradas;

                 $min_pres_netos_RVI = Team_modulo::where('cliente','RV')->where('planta','IntimarkI')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 if($min_pres_netos_RVI == 0){
                     $eficiencia_RVI = 0;
                 }else{
                     $eficiencia_RVI = (($proyeccion_min_RVI) / $min_pres_netos_RVI)*100;
                 }

                 $min_producidos_RVII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                 ->where('cliente','RV')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                 $proyeccion_min_RVII = ($min_producidos_RVII/$valor_hora)*$horas_laboradas;

                 $min_pres_netos_RVII = Team_modulo::where('cliente','RV')->where('planta','IntimarkII')->where('piezas_meta','<>','0')->sum('min_presencia_netos');

                 if($min_pres_netos_RVII == 0){
                     $eficiencia_RVII = 0;
                 }else{
                     $eficiencia_RVII = (($proyeccion_min_RVII) / $min_pres_netos_RVII)*100;
                 }

             $real_RVI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
             ->where('team_modulo.cliente','RV')
             ->whereDate('ticket_offline.fecha',$actual)
             ->whereTime('ticket_offline.fecha','<=',$hora3)
             ->where('planta','IntimarkI')
             ->sum('piezas');



             if($hora3 <> $hora_actualizacion){
                  $real_RVI = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','RV')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                  ->where('planta','IntimarkI')
                  ->sum('piezas');

                  $min_producidos_RVI = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                  ->where('cliente','RV')->where('planta','IntimarkI')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                  $proyeccion_min_RVI = ($min_producidos_RVI/$valor_hora)*$horas_laboradas;

                  if($min_pres_netos_RVI == 0){
                      $eficiencia_RVI = 0;
                  }else{
                      $eficiencia_RVI = ($proyeccion_min_RVI / $min_pres_netos_RVI)*100;
                  }
              }


              $real_RVII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
             ->where('team_modulo.cliente','RV')
             ->whereDate('ticket_offline.fecha',$actual)
             ->whereTime('ticket_offline.fecha','<=',$hora3)
             ->where('planta','IntimarkII')
             ->sum('piezas');



             if($hora3 <> $hora_actualizacion){
                  $real_RVII = Plan_diar::join('team_modulo','team_modulo.modulo','ticket_offline.modulo')
                  ->where('team_modulo.cliente','RV')
                  ->whereDate('ticket_offline.fecha',$actual)
                  ->whereTime('ticket_offline.fecha','<=',$hora_actualizacion)
                  ->where('planta','IntimarkII')
                  ->sum('piezas');

                  $min_producidos_RVII = Plan_diar::join('team_modulo','team_modulo.Modulo','ticket_offline.Modulo')
                  ->where('cliente','RV')->where('planta','IntimarkII')->whereDate('ticket_offline.fecha',$actual)->sum('min_producidos');

                  $proyeccion_min_RVII = ($min_producidos_RVI/$valor_hora)*$horas_laboradas;

                  if($min_pres_netos_RVII == 0){
                      $eficiencia_RVII = 0;
                  }else{
                      $eficiencia_RVII = ($proyeccion_min_RVII / $min_pres_netos_RVII)*100;
                  }
              }

                 return view('detalleRV', compact('cantidad_planta_RVI','cantidad_planta_RVII','eficiencia_dia_RVI','eficiencia_dia_RVII','eficiencia_RVI','eficiencia_RVII','real_RVI','real_RVII'));

        }


}

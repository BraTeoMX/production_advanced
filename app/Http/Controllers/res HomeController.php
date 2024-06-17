<?php

namespace App\Http\Controllers;

use App\Formato_P07;
use App\Team_Leader;
use App\Team_Modulo;
use App\Planeacion;
use App\Plan_diar;
use App\Modulos;

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
        $inicio='';
        $fin ='';
        $hoy =date('d/m');
        $hora = date("G").":00"; 
        $dia_semana = date("N");
        $actual = date('Y-m-d');

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
                                                $valor_hora=1;
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
              
        /***************montos generales ******************/
      
        $meta = Formato_P07::select('cantidad_total','eficiencia_total')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->where('modulo','total')->first();
        
        if(!$meta){
            $eficiencia_total = 0;
            $cantidad_total = 0;
        }else{
            $eficiencia_total = $meta->eficiencia_total;
            $cantidad_total = $meta->cantidad_total;
        }
        
        
        $cantidad_dia =  Team_Modulo::where('updated_at',$actual)->where('modulo','<>','151A')->where('modulo','<>','125A')->sum('piezas_meta');  //suma del dia
        
        $eficiencia_dia = Formato_P07::where('fecha_inicial','>=',$actual)->avg('eficiencia_d'.$dia_semana); //* promedio del dia
/************************** por planta ************* */
        $plantas = Formato_P07::select('planta')->groupby('Planta')->get();
        
            $cantidad_plantaI = Formato_P07::where('planta','Intimark I')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);
            $cantidad_plantaII = Formato_P07::where('planta','Intimark II')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->sum('cantidad_d'.$dia_semana);

            $eficiencia_plantaI = Formato_P07::where('planta','Intimark I')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
            $eficiencia_plantaII = Formato_P07::where('planta','Intimark II')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
       
/************************** por cliente ************* */
        $clientes = Formato_P07::select('cliente')->groupby('cliente')->get();

        $cantidad_VS = Team_Modulo::where('updated_at',$actual)->where('cliente','VS')->where('modulo','<>','151A')->where('modulo','<>','125A')->sum('piezas_meta');
        $cantidad_CHICOS = Team_Modulo::where('updated_at',$actual)->where('cliente','CHICOS')->sum('piezas_meta');
        $cantidad_BN3 = Team_Modulo::where('updated_at',$actual)->where('cliente','BN3')->sum('piezas_meta');
        $cantidad_NU = Team_Modulo::where('updated_at',$actual)->where('cliente','NUUDS')->sum('piezas_meta');
        $cantidad_MARENA = Team_Modulo::where('updated_at',$actual)->where('cliente','MARENA')->sum('piezas_meta');
        $cantidad_PACIFIC = Team_Modulo::where('updated_at',$actual)->where('cliente','LEQ')->where('modulo','<>','151A')->sum('piezas_meta');
        $cantidad_BELL = Team_Modulo::where('updated_at',$actual)->where('cliente','BELL')->sum('piezas_meta');

        $min_x_prod_VS = Team_Modulo::where('cliente','VS')->where('modulo','<>','151A')->where('modulo','<>','125A')->sum('piezas_meta','sam');
        $min_x_prod_VS = Team_Modulo::select(\DB::raw("SUM(piezas_meta*sam) as min_x_prod"))
        ->get();

      // dd($min_x_prod_VS);

        $eficiencia_VS = Formato_P07::where('cliente','VS  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $eficiencia_CHICOS = Formato_P07::where('cliente','Chicos  PI  1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $eficiencia_BN3 = Formato_P07::where('cliente','BN3  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $eficiencia_NU = Formato_P07::where('cliente','NU PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $eficiencia_MARENA = Formato_P07::where('cliente','Marena 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $eficiencia_PACIFIC = Formato_P07::where('cliente','Pacific  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);
        $eficiencia_BELL = Formato_P07::where('cliente','Pacific  PI 1T')->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)->avg('eficiencia_d'.$dia_semana);

/**********************acumulado******************* */
        $cantidad_acum=0;
        for($i=1;$i<=$dia_semana;$i++){
            $dia = Formato_P07::sum('cantidad_d'.$i); 
            $cantidad_acum = $cantidad_acum +  $dia;
        }
        /*********** x modulos*********** */
        $modulos = Plan_diar::join('cat_modulos','cat_modulos.Modulo','planeacion_diaria.Modulo')
        ->select('planeacion_diaria.modulo', 'planeacion_diaria.team_leader', 'planeacion_diaria.piezas', 'planeacion_diaria.min_producidos', 'planeacion_diaria.proyeccion_minutos','planeacion_diaria.efic','cat_modulos.piezas_meta')
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',$hora)
        ->groupby('planeacion_diaria.modulo','planeacion_diaria.team_leader', 'planeacion_diaria.piezas', 'planeacion_diaria.min_producidos', 'planeacion_diaria.proyeccion_minutos','planeacion_diaria.efic','cat_modulos.piezas_meta')
        ->get();   

        $modulo_meta = Formato_P07::select("modulo", \DB::raw("SUM(cantidad_d".$dia_semana.") as cantidad") )
        ->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)
        ->groupBy("modulo")
        ->get();
        /*********************** x team leader ******************** */

        $team_leader = Plan_diar::select('team_leader', \DB::raw("SUM(piezas) as piezas"))
        ->whereDate('updated_at',$actual)
        ->whereTime('updated_at',$hora)
        ->groupby('team_leader')
        ->get();    
        
                     
        $x_team = Team_Modulo::join('cat_team_leader','team_modulo.team_leader','cat_team_leader.id')
        ->join('cat_modulos','team_modulo.modulo','cat_modulos.id')
        ->join('formato_p07','formato_p07.modulo','cat_modulos.modulo')
        ->select('cat_team_leader.team_leader','cat_modulos.modulo', \DB::raw("SUM(formato_p07.cantidad_d1+formato_p07.cantidad_d2+formato_p07.cantidad_d3+formato_p07.cantidad_d4+formato_p07.cantidad_d5) as cantidad"), \DB::raw("(SUM(eficiencia_d1+eficiencia_d2+eficiencia_d3+eficiencia_d4+eficiencia_d5))/5 as eficiencia"))
        ->groupby('team_leader','cat_modulos.modulo')
        ->get();

      /**************indicadores *************** */
        $tiempo_desocupacion = 630*0.98;
        $horas_laboradas = 10.5;
        $horas_laboradas2 = 5.5;
        $sam_empaque= .910;

        /******************planeacion ************* */   
       
        $planeacion = Plan_diar::join('cat_modulos','planeacion_diaria.Modulo','cat_modulos.Modulo')
        ->select('planeacion_diaria.team_leader','planeacion_diaria.Modulo','cat_modulos.cliente',\DB::raw("SUM(piezas) as piezas"))
        ->whereDate('planeacion_diaria.updated_at',$actual)
        ->whereTime('planeacion_diaria.updated_at',$hora)
        ->groupby('planeacion_diaria.modulo','planeacion_diaria.team_leader','cat_modulos.cliente')
        ->orderby('planeacion_diaria.team_leader','asc')
        ->orderby('planeacion_diaria.modulo','asc')
        ->get();

        $planeacion_meta = Formato_P07::select("modulo", "cantidad_d2","eficiencia_d2")
        ->where('fecha_inicial','<=',$actual)->where('fecha_final','>=',$actual)
        ->groupBy("modulo",'cantidad_d2','eficiencia_d2')
        ->get();

  
       /**************planeacion semanal ********** */
       $cantidad_diaria = Plan_diar::whereDate('updated_at',$actual)->where('modulo','<>','151A')->where('modulo','<>','125A')->whereTime('updated_at',$hora)->sum('piezas');
       //$cantidad_semanal = Plan_diar::whereDate('updated_at','>',$inicio)->where('modulo','<>','151A')->where('modulo','<>','125A')->whereTime('updated_at','18:00')->sum('piezas');
       
       $cantidad_semanal = $cantidad_diaria;


       $eficiencia_diaria = Plan_diar::whereDate('updated_at',$actual)->where('modulo','=','830A')->whereTime('updated_at',$hora)->select('efic_total')->first();
       //$eficiencia_semanal = Plan_diar::whereDate('updated_at',$actual)->where('modulo','=','830A')->whereTime('updated_at',$hora)->select('efic_total')->first();
      $eficiencia_semanal = 49.13;
       if($eficiencia_diaria )
            $efic_total=$eficiencia_diaria->efic_total;
        else
            $efic_total=0;

       /*************real x planta ************ */

           $real_x_plantaI = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.planta','IntimarkI')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');
        
       
           $real_x_plantaII = Plan_diar::leftjoin('formato_p07','formato_p07.modulo','planeacion_diaria.Modulo')
           ->where('planta','Intimark II')
           ->where('planeacion_diaria.Modulo','<>','151A')
           ->where('planeacion_diaria.Modulo','<>','125A')
           ->where('planeacion_diaria.Modulo','<>','830')
           ->where('fecha_inicial',$actual)
           ->whereTime('updated_at',$hora)->sum('piezas');

           /*************real x cliente ************ */
           $real_VS = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','VS')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           $real_CHICOS = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','CHICOS')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           $real_BN3 = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','BN3')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           $real_NU = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','NUUDS')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');
           
           $real_MARENA = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','MARENA')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           $real_LECOQ = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','LEQ')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

           $real_BELL = Plan_diar::join('cat_modulos','cat_modulos.modulo','planeacion_diaria.modulo')
           ->where('cat_modulos.cliente','BELL')
           ->whereDate('planeacion_diaria.updated_at',$actual)
           ->where('planeacion_diaria.modulo','<>','151A')
           ->where('planeacion_diaria.modulo','<>','125A')
           ->whereTime('planeacion_diaria.updated_at',$hora)
           ->sum('piezas');

    return view('avanceproduccion', compact('meta','tiempo_desocupacion','horas_laboradas','horas_laboradas2', 'hoy','hora','cantidad_dia','eficiencia_dia','cantidad_acum','inicio','fin','cantidad_plantaI','cantidad_plantaII','eficiencia_plantaI','eficiencia_plantaII','cantidad_VS','cantidad_CHICOS','cantidad_NU','cantidad_BN3','cantidad_MARENA','cantidad_PACIFIC','cantidad_BELL','eficiencia_VS','eficiencia_CHICOS','eficiencia_NU','eficiencia_BN3','eficiencia_PACIFIC','eficiencia_MARENA','eficiencia_BELL','modulos','team_leader','x_team','planeacion','cantidad_semanal','sam_empaque','efic_total','real_x_plantaI','real_x_plantaII','real_VS','real_CHICOS','real_BN3','real_NU','real_MARENA','real_LECOQ','real_BELL','cantidad_diaria','eficiencia_semanal','planeacion_meta','modulo_meta','horas_laboradas','valor_hora','eficiencia_total','cantidad_total'));
    }

  
}

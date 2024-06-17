<?php
//Esta es la funcion principal, de inicio lo que mostrara  cuando se accede ini
namespace App\Http\Controllers;
use App\Produccion;
use App\ColoresMetas; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProduccionesMultiExport;


class ProduccionController extends Controller
{

    public function SemanaActual(Request $request)
    {
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        Carbon::setLocale('es');
        $now = Carbon::now();
        //$datosProduccion = Produccion::all();
        // Obtener registros donde la columna 'planta' es igual a 'Intimark1'
        $datosProduccionIntimark1 = Produccion::where('planta', 'Intimark1')
            ->where('estatus', 'A')
            ->orderBy('modulo', 'asc')
            ->get();

        // Obtener registros donde la columna 'planta' es igual a 'Intimark2'
        $datosProduccionIntimark2 = Produccion::where('planta', 'Intimark2')
            ->where('estatus', 'A')
            ->orderBy('modulo', 'asc')
            ->get();

        $numSemanas = 10;
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        //$current_week = 52;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');
        $current_month = ucfirst($current_month);
        $currentYear = $now->format('Y'); // 'Y' es el formato para el año completo, como "2023"
        // O simplemente

        return view('metas.SemanaActual', compact(
            'numSemanas','currentYear','current_week','current_month',
            'colores','titulos',
            'datosProduccionIntimark1', 'datosProduccionIntimark2'));
    }
    //Esta funcion permite actualizar los datos de la vista llamada SemanaActual y lo hace exclusivamente de la semana actual
    public function actualizarTabla(Request $request)
    {
         // $request->semanas contendrá todos los datos de los checkboxes
    foreach ($request->semanas as $idProduccion => $semanas) {
        foreach ($semanas as $semana => $valor) {
            // Encuentra la producción por ID
            $produccion = Produccion::find($idProduccion);
            // Aquí asumo que tienes columnas como 'semana1', 'semana2', etc.
            if ($produccion) {
                // Actualiza la semana correspondiente
                $produccion->{$semana} = $valor;
                $produccion->save();
            }
        }
    }

    // Redirecciona de vuelta a la página con un mensaje de éxito o lo que consideres necesario
    return back()->with('success', 'Selecciones actualizadas correctamente.');
    }

    public function ReporteGeneral(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        //$datosProduccion = Produccion::all();
        // Obtener registros donde la columna 'planta' es igual a 'Intimark1'
        $datosProduccionIntimark1 = Produccion::where('planta', 'Intimark1')->get();

        // Obtener registros donde la columna 'planta' es igual a 'Intimark2'
        $datosProduccionIntimark2 = Produccion::where('planta', 'Intimark2')->get();

        //dd($datosProduccionIntimark1, $datosProduccionIntimark2);

        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        $contadorTS = [];
        $contadoresSemana = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTS[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])
            ->where('planta', 'Intimark1')
            ->count();
            $contadoresSemana[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemana[$semana][$i] = Produccion::where("semana$semana", $i)
                ->where('planta', 'Intimark1')
                ->count();
            }
        }
        $contadorTSplanta2 = [];
        $contadoresSemanaPlanta2 = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTSplanta2[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])
            ->where('planta', 'Intimark2')
            ->count();
            $contadoresSemanaPlanta2[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemanaPlanta2[$semana][$i] = Produccion::where("semana$semana", $i)
                ->where('planta', 'Intimark2')
                ->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
        //prueba 
        
        $semanaInicio = $request->query('semana_inicio', $current_week);
        $semanaFin = $request->query('semana_fin', $current_week+1);

        //dd($semanaInicio, $current_week, $request);
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
        

        return view('metas.ReporteGeneral', compact('mensaje','numSemanas','mesesConSemanas', 'current_week', 'current_month',
                'contadorTS','contadoresSemana','contadorTSplanta2','contadoresSemanaPlanta2',
                'titulos', 'colores','colorClasses',
                'semanaInicio', 'semanaFin','mesesAMostrar',
                'datosProduccionIntimark1', 'datosProduccionIntimark2'));
    }
    

    public function tablaEXCEL(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        $datosProduccion = Produccion::all();
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        $contadorTS = [];
        $contadoresSemana = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTS[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])->count();
            $contadoresSemana[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemana[$semana][$i] = Produccion::where("semana$semana", $i)->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
    
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
    
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
        
 
    }

    public function tabla2PDF(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        $datosProduccion = Produccion::all();
        // Obtener registros donde la columna 'planta' es igual a 'Intimark1'
        $datosProduccionIntimark1 = Produccion::where('planta', 'Intimark1')->get();
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        $contadorTS = [];
        $contadoresSemana = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTS[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])->count();
            $contadoresSemana[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemana[$semana][$i] = Produccion::where("semana$semana", $i)->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
    
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
    
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
        
        // Redireccionamos con un mensaje de éxito
        //dd($semanaInicio, $semanaFin);

        $totalreg = 20;
            $largo=279.4;
            if($totalreg>10){
                $largo=(600*$totalreg)+30.2;
            }
            $customPaper = array(10,10,$largo,214.4);
            //dd($mytime)
            $cadena=md5('2023'.$request);


            $pdf = PDF::loadView(
                    'metas/tabla2PDF', compact('mensaje','datosProduccion', 'datosProduccionIntimark1',
                    'numSemanas','mesesConSemanas', 'current_week', 'current_month',
                    'contadorTS','contadoresSemana','titulos', 'colores','colorClasses',
                    'semanaInicio', 'semanaFin','mesesAMostrar'))
                    ->setPaper('letter', 'landscape',  array('UTF-8','UTF8'));
            $nombre='Reporte-General-Ixtlahuaca'.'.pdf';


        // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
        return $pdf->download($nombre);
    }

    public function Planta2tabla2PDF(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        $datosProduccion = Produccion::all();
        // Obtener registros donde la columna 'planta' es igual a 'Intimark2'
        $datosProduccionIntimark2 = Produccion::where('planta', 'Intimark2')->get();
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        $contadorTSplanta2 = [];
        $contadoresSemanaPlanta2 = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTSplanta2[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])
            ->where('planta', 'Intimark2')
            ->count();
            //dd($contadorTSplanta2);            
            $contadoresSemanaPlanta2[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemanaPlanta2[$semana][$i] = Produccion::where("semana$semana", $i)
                ->where('planta', 'Intimark2')
                ->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
    
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
    
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
        
        // Redireccionamos con un mensaje de éxito
        //dd($semanaInicio, $semanaFin);

        $totalreg = 20;
            $largo=279.4;
            if($totalreg>10){
                $largo=(600*$totalreg)+30.2;
            }
            $customPaper = array(10,10,$largo,214.4);
            //dd($mytime)
            $cadena=md5('2023'.$request);


            $pdf = PDF::loadView(
                    'metas/Planta2tabla2PDF', compact('mensaje','datosProduccion', 'datosProduccionIntimark2',
                    'numSemanas','mesesConSemanas', 'current_week', 'current_month',
                    'contadorTSplanta2','contadoresSemanaPlanta2',
                    'titulos', 'colores','colorClasses',
                    'semanaInicio', 'semanaFin','mesesAMostrar'))
                    ->setPaper('letter', 'landscape',  array('UTF-8','UTF8'));
            $nombre='Reporte-General-San-Bartolo'.'.pdf';


        // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
        return $pdf->download($nombre);
    }

    public function tablaPDF(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        $datosProduccion = Produccion::all();
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        $contadorTS = [];
        $contadoresSemana = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTS[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])
            ->where('planta', 'Intimark1')
            ->count();
            //dd($contadorTS);
            $contadoresSemana[$semana] = [];
            
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemana[$semana][$i] = Produccion::where("semana$semana", $i)
                ->where('planta', 'Intimark1')
                ->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
    
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
    
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
        
        // Redireccionamos con un mensaje de éxito
        //dd($semanaInicio, $semanaFin);

        $totalreg = 20;
            $largo=279.4;
            if($totalreg>10){
                $largo=(600*$totalreg)+30.2;
            }
            $customPaper = array(10,10,$largo,214.4);
            //dd($mytime)
            $cadena=md5('2023'.$request);


            $pdf = PDF::loadView(
                    'metas/tablaPDF', compact('mensaje','datosProduccion',
                    'numSemanas','mesesConSemanas', 'current_week', 'current_month',
                    'contadorTS','contadoresSemana','titulos', 'colores','colorClasses',
                    'semanaInicio', 'semanaFin','mesesAMostrar'))
                    ->setPaper('letter', 'landscape',  array('UTF-8','UTF8'));
            $nombre='Tabla-Ixtlahuaca'.'.pdf';


        // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
        return $pdf->download($nombre);
    }

    // apartado para Tabla San Bartolo Planta 2 PDF 
    public function Planta2tablaPDF(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        $datosProduccion = Produccion::all();
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        
        $contadorTSplanta2 = [];
        $contadoresSemanaPlanta2 = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTSplanta2[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])
            ->where('planta', 'Intimark2')
            ->count();
            //dd($contadorTSplanta2);            
            $contadoresSemanaPlanta2[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemanaPlanta2[$semana][$i] = Produccion::where("semana$semana", $i)
                ->where('planta', 'Intimark2')
                ->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
    
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
        //dd($request->all());
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
        
        // Redireccionamos con un mensaje de éxito
        //dd($semanaInicio, $semanaFin);

        $totalreg = 20;
            $largo=279.4;
            if($totalreg>10){
                $largo=(600*$totalreg)+30.2;
            }
            $customPaper = array(10,10,$largo,214.4);
            //dd($mytime)
            $cadena=md5('2023'.$request);


            $pdf = PDF::loadView(
                    'metas/Planta2tablaPDF', compact('mensaje','datosProduccion',
                    'numSemanas','mesesConSemanas', 'current_week', 'current_month',
                    'contadorTSplanta2','contadoresSemanaPlanta2',
                    'titulos', 'colores','colorClasses',
                    'semanaInicio', 'semanaFin','mesesAMostrar'))
                    ->setPaper('letter', 'landscape',  array('UTF-8','UTF8'));
            $nombre='Tabla-San-Bartolo'.'.pdf';


        // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
        return $pdf->download($nombre);
    }

    public function tabla2EXCEL(Request $request)
    {
        $titulos = [
            'CUMPLIMIENTO DE META JUEVES 7:00 P.M.',
            'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M',
            'CUMPLIMIENTO META VIERNES 2:00 P.M.',
            'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M.',
            'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE',
            'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE',
            'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA',
            '** CUMPLEN Y APOYAN TE'
        ];
        $colores = [
            '#548235',
            '#00B050',
            '#FFFF00',
            '#C65911',
            '#FF0000',
            '#FF9966',
            '#CFC7C5',
        ];
        $mensaje = "Hola Mundo";
        $numSemanas = 52;
        Carbon::setLocale('es');
        $now = Carbon::now();
        $datosProduccion = Produccion::all();
        // Obtener el número de la semana actual
        $current_week = $now->weekOfYear;
        // Obtener el nombre del mes actual
        $current_month = $now->translatedFormat('F');

        $mesesConSemanas = [];
        $totalSemanasAsignadas = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $date = Carbon::createFromDate(2023, $mes, 1);

            // Asignar 4 semanas a cada mes por defecto
            $semanasEnMes = 4;
            $totalSemanasAsignadas += $semanasEnMes;

            $mesesConSemanas[$date->translatedFormat('F')] = $semanasEnMes;
        }

        // Distribuir las semanas restantes (52 - totalSemanasAsignadas) entre algunos meses
        $semanasRestantes = 52 - $totalSemanasAsignadas;
        $mesesCon31Dias = [1, 3, 5, 7, 8, 10, 12]; // Meses con 31 días
        foreach ($mesesCon31Dias as $mes) {
            if ($semanasRestantes > 0) {
                $nombreMes = Carbon::createFromDate(2023, $mes, 1)->translatedFormat('F');
                $mesesConSemanas[$nombreMes]++;
                $semanasRestantes--;
            }
        }
        //dd($numSemanas);
        $contadorTS = [];
        $contadoresSemana = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTS[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])->count();
            $contadoresSemana[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemana[$semana][$i] = Produccion::where("semana$semana", $i)->count("semana$semana");
            }
        }
        // $colorClasses es un arreglo que nos da las propiedades de los colores y que manda a llamar de forma dinamica a la tabla de la vista 
        // es importante ya que se optimiza la vista del codigo y posteriomente se manda a llamar dependiendo de la opcion
        $colorClasses = [
            1 => 'green',
            2 => 'light-green',
            3 => 'yellow',
            4 => 'SaddleBrown',
            5 => 'red',
            6 => 'peach',
            7 => 'grey'
        ];
        //variables para los rangos de seleccion 
        $semanaInicio = $request->query('semana_inicio', 1);
        $semanaFin = $request->query('semana_fin', $numSemanas);

        // Asegurarte de que el rango es válido
        $semanaInicio = max($semanaInicio, 1);
        $semanaFin = min($semanaFin, $numSemanas);

        $semanasDelMes = [
            'Enero' => range(1, 4),
            'Febrero' => range(5, 8),
            'Marzo' => range(9, 12), // 4 semanas
            'Abril' => range(13, 16), // 4 semanas
            'Mayo' => range(17, 21), // 5 semanas para compensar
            'Junio' => range(22, 25), // 4 semanas
            'Julio' => range(26, 29), // 4 semanas
            'Agosto' => range(30, 34), // 5 semanas para compensar
            'Septiembre' => range(35, 38), // 4 semanas
            'Octubre' => range(39, 42), // 4 semanas
            'Noviembre' => range(43, 47), // 5 semanas para compensar
            'Diciembre' => range(48, 52) // 5 semanas para terminar el año
        ];
        
    
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
    
        // Filtrar los meses para obtener solo aquellos que tienen al menos una semana dentro del rango seleccionado
        $mesesAMostrar = array_filter($semanasDelMes, function($semanas) use ($semanaInicio, $semanaFin) {
            return max($semanas) >= $semanaInicio && min($semanas) <= $semanaFin;
        });
    }

    public function exportExcel(Request $request)
    {
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
        
        //dd($request->all());    
        // Asegúrate de que el rango de semanas es válido y está presente
        if (!$semanaInicio || !$semanaFin) {
            // Redirige de vuelta con un error si no hay rango de semanas definido
            //dd($semanaInicio, $semanaFin);
            return redirect()->back()->withErrors('Por favor, define un rango de semanas válido.');
        }
        //dd($semanaInicio, $semanaFin);
        // Exporta ambas tablas en un solo archivo Excel.
        return (new ProduccionesMultiExport($semanaInicio, $semanaFin))->download('producciones.xlsx');
    }
    
    public function TeamLeaderModulo(Request $request)
    {
        $mensaje = "Hola Mundo"; 
        $datosProduccionIntimark1 = Produccion::where('planta', 'Intimark1')
        ->get();

        // Obtener registros donde la columna 'planta' es igual a 'Intimark2'
        $datosProduccionIntimark2 = Produccion::where('planta', 'Intimark2')
            ->get();

        
        return view('metas.TeamLeaderModulo', compact('mensaje', 'datosProduccionIntimark1', 'datosProduccionIntimark2'));
    }
    
    public function agregarTeamLeader(Request $request)
    {
        // Primero, verifica si ya existe un registro con el mismo 'nombre' y 'modulo' para la 'planta' dada
        $existe = Produccion::where('nombre', $request->input('nombre'))
            ->where('modulo', $request->input('modulo'))
            ->exists();
        // Si ya existe un registro, regresa con un mensaje de error
        if ($existe) {
        return back()->with('error', 'El Team Leader con ese nombre y módulo ya existe en la planta especificada.');
        }

        $teamLeader = new Produccion;
        $teamLeader->nombre = strtoupper($request->nombre); // Convertir a mayúsculas
        $teamLeader->modulo = strtoupper($request->modulo);
        $teamLeader->planta = $request->planta; // 'Intimark1' o 'Intimark2'
        $teamLeader->estatus = 'A';
        $teamLeader->save();
        // Redirección en el controlador
        return redirect()->route('TeamLeaderModulo')->with('success', 'Team Leader y modulo agregado correctamente');
    }

    public function ActualizarEstatusP1(Request $request, $id) {
        $teamLeader = Produccion::where('planta', 'Intimark1')->findOrFail($id);
        $teamLeader->estatus = $request->input('estatus', 'A'); // Asumiendo 'A' como valor por defecto para "Dar de Alta"
        $teamLeader->save();
    
        $mensaje = $teamLeader->estatus == 'A' ? 'El Team Leader ha sido dado de alta.' : 'El Team Leader ha sido dado de baja.';
        
        return back()->with('status', $mensaje);
    }
    
    public function ActualizarEstatusP2(Request $request, $id) {
        $teamLeader = Produccion::where('planta', 'Intimark2')->findOrFail($id);
        $teamLeader->estatus = $request->input('estatus', 'A'); // Asumiendo 'A' como valor por defecto para "Dar de Alta"
        $teamLeader->save();
    
        $mensaje = $teamLeader->estatus == 'A' ? 'El Team Leader ha sido dado de alta.' : 'El Team Leader ha sido dado de baja.';
        
        return back()->with('status', $mensaje);
    }










    public function ProduccionTabla(Request $request)
    {
        
        $mensaje = "0"; 
        $datosProduccion = Produccion::all();
        $ColoresMetas = ColoresMetas::all();
        $numSemanas = 10;
        $contadorTS = [];
        $contadoresSemana = [];
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $contadorTS[$semana] = Produccion::whereIn("semana$semana", [1, 2, 3, 4, 5, 6, 7])->count();
            $contadoresSemana[$semana] = [];
            for ($i = 1; $i <= 7; $i++) {
                $contadoresSemana[$semana][$i] = Produccion::where("semana$semana", $i)->count("semana$semana");
            }
        }

        //fin de contradores por bucles for
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            foreach ($datosProduccion as $produccion) {
                $produccion->{"semana$semana"} = 'dato' . $produccion->{"semana$semana"};
            }
        }
        // Guarda los valores de los filtros de semanas en la sesión
        if ($request->has('semana_inicio') && $request->has('semana_fin')) {
            $request->session()->put('semana_inicio', $request->input('semana_inicio'));
            $request->session()->put('semana_fin', $request->input('semana_fin'));
        }
        
        return view('metas.ProduccionTabla', compact('mensaje','datosProduccion',
            'contadorTS', 'contadoresSemana','numSemanas'
        ));
    }

    public function actualizarSeleccion(Request $request)
    {
        $numSemanas = 10;
        for ($semana = 1; $semana <= $numSemanas; $semana++) {
            $semanaValues = $request->input("semana$semana");

            foreach ($semanaValues as $id => $semanaValue) {
                // Quitamos el prefijo "dato" para obtener solo el número
                $valorNumerico = intval(substr($semanaValue, 4));

                $produccion = Produccion::find($id);
                // Verificamos si encontramos la producción
                if ($produccion) {
                    // Actualizamos el valor de la semana correspondiente con el número
                    $produccion->{"semana$semana"} = $valorNumerico;
                    $produccion->save();
                } else {
                    // Agregar manejo de error si la producción no se encuentra
                    // Esto es opcional, pero recomendable
                }
            }
        }
        $request->session()->put('semana_inicio', $request->input('semana_inicio'));
        $request->session()->put('semana_fin', $request->input('semana_fin'));
        // Redireccionamos con un mensaje de éxito
        return redirect()->route('metas.ProduccionTabla')
        ->with('success', 'Datos actualizados correctamente')->withInput()
        ->with('semana_inicio', $request->input('semana_inicio'))
        ->with('semana_fin', $request->input('semana_fin'));
}


    public function filtrarSemanas(Request $request)
    {
        $numSemanas = 10;
        $numSemanas = 52;
        $request->session()->put('semana_inicio', $request->input('semana_inicio'));
        $request->session()->put('semana_fin', $request->input('semana_fin'));
        $semanaInicio = $request->input('semana_inicio');
        $semanaFin = $request->input('semana_fin');
        $contadorTS = [];
        $contadoresSemana = [];
    
        // ... Tu lógica para filtrar los datos basados en las semanas seleccionadas ...

        // Guarda las selecciones en la sesión para mantener el estado tras la recarga
        session([
            'semana_inicio' => $semanaInicio,
            'semana_fin' => $semanaFin,
        ]);

        // Retorna a la vista con los datos filtrados y los valores de semana almacenados en la sesión
        return view('metas.ProduccionTabla', compact('semanaInicio', 'semanaFin','numSemanas',
        'contadorTS','contadoresSemana','datosProduccion'));
    }


    
}

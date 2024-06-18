<?php
//Esta es la funcion principal, de inicio lo que mostrara  cuando se accede ini
namespace App\Http\Controllers;
use App\Produccion1;
use App\Supervisor;
use App\ColoresMetas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProduccionesMultiExport;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use DateTime; // Asegúrate de importar esta clase



class ProduccionMetasController extends Controller
{

    public function supervisorModulo()
    {
        $supervisoresPlanta1 = Supervisor::where('planta', 'Intimark1')->get();
        $supervisoresPlanta2 = Supervisor::where('planta', 'Intimark2')->get();
        return view('metas.supervisorModulo', compact('supervisoresPlanta1', 'supervisoresPlanta2'));
    }

    public function storeSupervisor(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'modulo' => 'required|string|max:255',
            'planta' => 'required|string',
        ]);

        // Verificar si ya existe un registro con el mismo nombre y módulo
        $existingSupervisor = Supervisor::where('nombre', $request->nombre)
                                        ->where('modulo', $request->modulo)
                                        ->first();

        if ($existingSupervisor) {
            return redirect()->route('metas.supervisorModulo')->with('error', 'Ya existe un supervisor con ese nombre y módulo.');
        }

        // Asignar el valor por defecto para 'estatus'
        $data = $request->all();
        $data['estatus'] = 'A';

        Supervisor::create($data);

        return redirect()->route('metas.supervisorModulo')->with('success', 'Supervisor agregado exitosamente.');
    }

    public function updateStatusSupervisor(Request $request, $id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->estatus = $request->estatus;
        $supervisor->save();

        return redirect()->route('metas.supervisorModulo')->with('success', 'Estatus del supervisor actualizado.');
    }


    public function registroSemanal()
    {
        $supervisoresPlanta1 = Supervisor::where('planta', 'Intimark1')->where('estatus', 'A')->get();
        $supervisoresPlanta2 = Supervisor::where('planta', 'Intimark2')->where('estatus', 'A')->get();
        $current_week = 23;
        $current_month = date('F');
        $currentYear = date('Y');

        $produccionPlanta1 = Produccion1::where('semana', $current_week)
            ->whereIn('supervisor_id', $supervisoresPlanta1->pluck('id'))
            ->get()->keyBy('supervisor_id');

        $produccionPlanta2 = Produccion1::where('semana', $current_week)
            ->whereIn('supervisor_id', $supervisoresPlanta2->pluck('id'))
            ->get()->keyBy('supervisor_id');

        return view('metas.registroSemanal', compact('supervisoresPlanta1', 'supervisoresPlanta2', 'produccionPlanta1', 'produccionPlanta2', 'current_week', 'current_month', 'currentYear'));
    }

    public function storeProduccion1(Request $request)
    {
        $current_week = 23;

        foreach ($request->semanas as $supervisor_id => $data) {
            $te_value = isset($data['te']) ? 1 : 0; // Obtener el valor de 'te' o usar 0 si no está presente

            // Inicializamos el valor como nulo para comprobar si se seleccionó algún checkbox de semana
            $valor = null;

            // Iterar a través de los datos para encontrar el valor de la semana
            foreach ($data as $key => $value) {
                if ($key !== 'te' && is_numeric($value)) {
                    $valor = $value;
                }
            }

            // Si se ha seleccionado un valor para la semana, actualizamos/creamos el registro
            if ($valor !== null) {
                Produccion1::updateOrCreate(
                    ['supervisor_id' => $supervisor_id, 'semana' => $current_week],
                    ['te' => $te_value, 'valor' => $valor]
                );
            }
        }

        return redirect()->route('metas.registroSemanal')->with('success', 'Datos de producción actualizados correctamente.');
    }


    public function reporteGeneralMetas()
    {
        $supervisoresPlanta1 = Supervisor::where('planta', 'Intimark1')->get();
        $supervisoresPlanta2 = Supervisor::where('planta', 'Intimark2')->get();

        $produccionPlanta1 = Produccion1::with('supervisor')->whereHas('supervisor', function ($query) {
            $query->where('planta', 'Intimark1');
        })->get();

        $produccionPlanta2 = Produccion1::with('supervisor')->whereHas('supervisor', function ($query) {
            $query->where('planta', 'Intimark2');
        })->get();

        $mesesAMostrar = $this->obtenerMeses();

        $contadorTS = [];
        $contadorSuma = [];
        $contadoresSemana = [];
        $TcontadorSuma3 = [];
        $Tporcentajes3 = [];
        $TcontadorSuma = [];
        $Tporcentajes = [];

        $colores = ['#00B0F0', '#00B050', '#FFFF00', '#C65911', '#FF0000', '#A6A6A6', '#F9F9EB']; // Definir los colores
        $titulos = ['CUMPLIMIENTO DE META JUEVES 7:00 P.M.', 
                    'CUMPLIMIENTO META VIERNES ANTES DE LAS 2:00 P.M ', 
                    'CUMPLIMIENTO META VIERNES 2:00 P.M.', 
                    'CUMPLIMIENTO META VIERNES DESPUES DE LAS 2:00 P.M. ', 
                    'NO CUPLIO META VIERNES 2:00 P.M. ,SIN APOYO TE', 
                    'NO CUMPLE META VIERNES 2:00 P.M., CON TE VIERNES Y SIN APOYO SABADO TE ', 
                    'SIN CUMPLIR META MOD ENTTO NO PARTICIPA EN PROGRAMA ']; // Definir los títulos

        foreach ($mesesAMostrar as $mes => $semanas) {
            foreach ($semanas as $semana) {
                $contadorTS[$semana] = 0;
                $contadorSuma[$semana] = 0;
                $TcontadorSuma3[$semana] = 0;
                $Tporcentajes3[$semana] = 0;
                $TcontadorSuma[$semana] = 0;
                $Tporcentajes[$semana] = 0;
                for ($i = 1; $i <= 7; $i++) {
                    $contadoresSemana[$semana][$i] = 0;
                }
            }
        }

        foreach ($produccionPlanta1 as $produccion) {
            $semana = $produccion->semana;
            $valor = $produccion->valor;
            $te = $produccion->te;

            $contadorTS[$semana] += 1;
            $contadorSuma[$semana] += $valor;
            $contadoresSemana[$semana][$valor] += 1;

            if ($te == 1) {
                $TcontadorSuma3[$semana] += 1;
            }
        }

        foreach ($produccionPlanta2 as $produccion) {
            $semana = $produccion->semana;
            $valor = $produccion->valor;
            $te = $produccion->te;

            $contadorTS[$semana] += 1;
            $contadorSuma[$semana] += $valor;
            $contadoresSemana[$semana][$valor] += 1;

            if ($te == 1) {
                $TcontadorSuma[$semana] += 1;
            }
        }

        foreach ($mesesAMostrar as $mes => $semanas) {
            foreach ($semanas as $semana) {
                $total = $contadorTS[$semana];
                if ($total != 0) {
                    $Tporcentajes3[$semana] = number_format(($TcontadorSuma3[$semana] / $total) * 100, 2);
                    $Tporcentajes[$semana] = number_format(($TcontadorSuma[$semana] / $total) * 100, 2);
                }
            }
        }

        return view('metas.ReporteGeneralMetas', compact(
            'mesesAMostrar',
            'contadorTS',
            'contadorSuma',
            'contadoresSemana',
            'TcontadorSuma3',
            'Tporcentajes3',
            'TcontadorSuma',
            'Tporcentajes',
            'produccionPlanta1',
            'produccionPlanta2',
            'supervisoresPlanta1',
            'supervisoresPlanta2',
            'colores',
            'titulos'
        ));
    }

    private function obtenerMeses()
    {
        // Obtener todos los registros de la tabla produccion1
        $produccion1 = Produccion1::all();

        // Crear un array para almacenar los meses y las semanas
        $mesesAMostrar = [];

        // Recorrer todos los registros
        foreach ($produccion1 as $produccion) {
            // Obtener la semana y el año del registro
            $semana = $produccion->semana;
            $año = date('Y', strtotime($produccion->created_at)); // Asumimos que created_at es la fecha de creación del registro

            // Convertir la semana y el año a una fecha
            $fecha = new DateTime();
            $fecha->setISODate($año, $semana);

            // Obtener el mes de la fecha
            $mes = $fecha->format('F');

            // Añadir la semana al mes correspondiente en el array
            if (!isset($mesesAMostrar[$mes])) {
                $mesesAMostrar[$mes] = [];
            }
            if (!in_array($semana, $mesesAMostrar[$mes])) {
                $mesesAMostrar[$mes][] = $semana;
            }
        }

        // Ordenar las semanas dentro de cada mes
        foreach ($mesesAMostrar as $mes => $semanas) {
            sort($semanas);
        }

        // Devolver el array de meses y semanas
        return $mesesAMostrar;
    }


}

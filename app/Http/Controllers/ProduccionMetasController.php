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
        $current_week = date('W');
        $current_month = date('F');
        $currentYear = date('Y');

        return view('metas.registroSemanal', compact('supervisoresPlanta1', 'supervisoresPlanta2', 'current_week', 'current_month', 'currentYear'));
    }

    public function storeProduccion1(Request $request)
    {
        foreach ($request->semanas as $supervisor_id => $data) {
            foreach ($data as $key => $value) {
                Produccion1::updateOrCreate(
                    ['supervisor_id' => $supervisor_id, 'semana' => $key],
                    ['te' => $value]
                );
            }
        }

        return redirect()->route('metas.registroSemanal')->with('success', 'Datos de producción actualizados correctamente.');
    }

}

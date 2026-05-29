<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Licencia;
use App\Models\Contribuyente;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index(Request $request): View
    {
        // Obtener año del filtro (por defecto el año actual)
        $anio = $request->input('anio', now()->year);

        // ===== ESTADÍSTICAS PRINCIPALES =====
        $solicitudes_total = Solicitud::whereYear('created_at', $anio)->count();
        $solicitudes_recibidas = Solicitud::where('estado', 'recibido')->whereYear('created_at', $anio)->count();
        $solicitudes_revision = Solicitud::where('estado', 'en_revision')->whereYear('created_at', $anio)->count();
        $solicitudes_aprobadas = Solicitud::where('estado', 'aprobado')->whereYear('created_at', $anio)->count();
        $solicitudes_rechazadas = Solicitud::where('estado', 'rechazado')->whereYear('created_at', $anio)->count();

        // ===== CÁLCULOS DE VIGENCIA CON ESTADO CORRECTO =====
        // Un certificado es VIGENTE si estado='aprobado' y fecha_vencimiento >= hoy
        // Un certificado es VENCIDO si fecha_vencimiento < hoy
        $licencias_total = Licencia::whereYear('fecha_emision', $anio)->count();
        $licencias_vigentes = Licencia::where('estado', 'aprobado')
            ->where('fecha_vencimiento', '>=', today())
            ->whereYear('fecha_emision', $anio)
            ->count();
        
        $licencias_vencidas = Licencia::where('fecha_vencimiento', '<', today())
            ->whereYear('fecha_emision', $anio)
            ->count();

        $contribuyentes_total = Contribuyente::count();

        // ===== ÚLTIMAS ACTIVIDADES =====
        $ultimas_solicitudes = Solicitud::orderBy('created_at', 'DESC')->limit(10)->get();
        $ultimos_certificados = Licencia::with('contribuyente', 'actividadEconomica')
            ->orderBy('created_at', 'DESC')->limit(10)->get();

        $solicitudes_hoy = Solicitud::whereDate('created_at', today())
            ->where('estado', 'recibido')->count();

        $proximas_renovaciones = Licencia::whereBetween('fecha_vencimiento', [today(), today()->addDays(30)])
            ->where('estado', 'aprobado')->count();

        // ===== DISTRIBUCIÓN POR TIPO DE CERTIFICADO =====
        $distribucion_certificados = Licencia::select('tipo_certificado', DB::raw('count(*) as total'))
            ->groupBy('tipo_certificado')
            ->orderByRaw('count(*) DESC')
            ->get();

        $data_certificados = [];
        $colores_certificados = ['#667eea', '#764ba2', '#f093fb'];
        foreach ($distribucion_certificados as $index => $item) {
            $data_certificados[] = [
                'label' => $this->getTipoCertificadoLabel($item->tipo_certificado),
                'value' => $item->total,
                'color' => $colores_certificados[$index % count($colores_certificados)]
            ];
        }

        // ===== SOLICITUDES POR MES (últimos 6 meses) =====
        $solicitudes_por_mes = [];
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $mes_nombre = $fecha->translatedFormat('M');
            $meses[] = $mes_nombre;
            
            $count = Solicitud::whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month)
                ->count();
            $solicitudes_por_mes[] = $count;
        }

        // ===== CERTIFICADOS PRÓXIMOS A VENCER =====
        $certificados_vencer = Licencia::whereBetween('fecha_vencimiento', [today(), today()->addDays(30)])
            ->where('estado', 'aprobado')
            ->whereYear('fecha_emision', $anio)
            ->with('contribuyente')
            ->orderBy('fecha_vencimiento', 'ASC')
            ->limit(10)
            ->get();

        // ===== CERTIFICADOS VENCIDOS =====
        $certificados_vencidos = Licencia::where('fecha_vencimiento', '<', today())
            ->with('contribuyente')
            ->orderBy('fecha_vencimiento', 'DESC')
            ->limit(10)
            ->get();

        // ===== TOP CONTRIBUYENTES (más certificados) =====
        $top_contribuyentes = DB::table('contribuyentes')
            ->select('contribuyentes.nombres_razon_social', 'contribuyentes.id', DB::raw('count(licencias.id) as total_certificados'))
            ->leftJoin('licencias', 'contribuyentes.id', '=', 'licencias.contribuyente_id')
            ->groupBy('contribuyentes.id', 'contribuyentes.nombres_razon_social')
            ->orderByRaw('count(licencias.id) DESC')
            ->limit(5)
            ->get();

        // ===== SOLICITUDES POR ESTADO (Gráfico de Pastel) =====
        $datos_estado_solicitudes = [
            ['label' => 'Recibidas', 'value' => $solicitudes_recibidas, 'color' => '#17a2b8'],
            ['label' => 'En Revisión', 'value' => $solicitudes_revision, 'color' => '#ffc107'],
            ['label' => 'Aprobadas', 'value' => $solicitudes_aprobadas, 'color' => '#28a745'],
            ['label' => 'Rechazadas', 'value' => $solicitudes_rechazadas, 'color' => '#dc3545'],
        ];

        // ===== ACTIVIDAD DIARIA (últimos 7 días) =====
        $actividad_diaria = [];
        $dias_nombres = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $dias_nombres[] = $fecha->translatedFormat('ddd');
            
            $count = Solicitud::whereDate('created_at', $fecha)->count();
            $actividad_diaria[] = $count;
        }

        // ===== SOLICITUDES SIN PROCESAR =====
        $solicitudes_sin_procesar = Solicitud::where('estado', 'recibido')
            ->orderBy('created_at', 'ASC')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'solicitudes_total',
            'solicitudes_recibidas',
            'solicitudes_revision',
            'solicitudes_aprobadas',
            'solicitudes_rechazadas',
            'licencias_total',
            'licencias_vigentes',
            'licencias_vencidas',
            'contribuyentes_total',
            'ultimas_solicitudes',
            'ultimos_certificados',
            'solicitudes_hoy',
            'proximas_renovaciones',
            'data_certificados',
            'solicitudes_por_mes',
            'meses',
            'certificados_vencer',
            'certificados_vencidos',
            'top_contribuyentes',
            'datos_estado_solicitudes',
            'actividad_diaria',
            'dias_nombres',
            'anio',
            'solicitudes_sin_procesar'
        ));
    }

    public function getTipoCertificadoLabel($tipo): string
    {
        return match($tipo) {
            'anexo_13' => 'ITSE Anexo 13',
            'anexo_14' => 'ITSE Anexo 14',
            'evento_publico' => 'Evento Público',
            default => $tipo
        };
    }

    /**
     * Exportar listado de certificados próximos a vencer a PDF
     */
    public function exportarVencerPdf(Request $request)
    {
        $anio = $request->input('anio', now()->year);

        $certificados = Licencia::whereBetween('fecha_vencimiento', [today(), today()->addDays(30)])
            ->where('estado', 'aprobado')
            ->whereYear('fecha_emision', $anio)
            ->with('contribuyente')
            ->orderBy('fecha_vencimiento', 'ASC')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.certificados-vencer-pdf', [
            'certificados' => $certificados,
            'anio' => $anio,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('certificados-proximos-vencer-' . $anio . '.pdf');
    }
}

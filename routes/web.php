<?php

use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\ContribuyenteController;
use App\Http\Controllers\ActividadEconomicaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ConsultaPublicaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ===== RUTAS PÚBLICAS =====
Route::get('consulta', [ConsultaPublicaController::class, 'index'])->name('consulta.index');
Route::post('consulta/buscar', [ConsultaPublicaController::class, 'buscar'])->name('consulta.buscar');
Route::get('consulta/{licencia}', [ConsultaPublicaController::class, 'detalle'])->name('consulta.detalle');
Route::get('verificar/{numero}', [PdfController::class, 'verificar'])->name('licencias.verificar');
Route::get('pdf/fut', [PdfController::class, 'generarFUT'])->name('pdf.fut');
Route::post('importar/licencias', [ImportController::class, 'importLicencias'])->name('importar.licencias');

// Trámite online público
Route::get('tramite', [SolicitudController::class, 'formulario'])->name('solicitudes.formulario');
Route::post('tramite/enviar', [SolicitudController::class, 'enviar'])->name('solicitudes.enviar');
Route::get('tramite/confirmacion/{codigo}', [SolicitudController::class, 'confirmacion'])->name('solicitudes.confirmacion');
Route::get('tramite/seguimiento', [SolicitudController::class, 'seguimiento'])->name('solicitudes.seguimiento');

// APIs Consultas Públicas (DNI/RUC) - Sin CSRF
Route::withoutMiddleware(['Illuminate\Foundation\Http\Middleware\VerifyCsrfToken'])->group(function () {
    Route::post('api/consultar-dni', [SolicitudController::class, 'consultarDNI'])->name('api.consultar-dni');
    Route::post('api/consultar-ruc', [SolicitudController::class, 'consultarRUC'])->name('api.consultar-ruc');
});

// ===== RUTAS PRIVADAS =====
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // IMPORTANTE: esta ruta debe ir ANTES del resource de licencias
    Route::get('licencias/crear-desde/{solicitud}', [LicenciaController::class, 'crearDesdeSolicitud'])->name('licencias.crear-desde-solicitud');

    Route::resource('licencias', LicenciaController::class);
    Route::resource('contribuyentes', ContribuyenteController::class);
    Route::resource('actividades', ActividadEconomicaController::class);
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::get('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');
    Route::post('usuarios/{usuario}/update-password', [UsuarioController::class, 'updatePassword'])->name('usuarios.update-password');

    Route::post('licencias/{licencia}/aprobar', [LicenciaController::class, 'aprobar'])
        ->name('licencias.aprobar');
    Route::get('licencias/{licencia}/pdf', [PdfController::class, 'generarLicencia'])
        ->name('licencias.pdf');
    Route::post('licencias/{licencia}/enviar-correo', [LicenciaController::class, 'enviarCorreo'])
        ->name('licencias.enviar-correo');
    Route::post('licencias/{licencia}/enviar-whatsapp', [LicenciaController::class, 'enviarWhatsApp'])
        ->name('licencias.enviar-whatsapp');

    Route::get('importar', [ImportController::class, 'showForm'])->name('importar.form');
    Route::post('importar/contribuyentes', [ImportController::class, 'importContribuyentes'])->name('importar.contribuyentes');
    Route::post('importar/actividades', [ImportController::class, 'importActividades'])->name('importar.actividades');

    Route::get('importar/plantilla-contribuyentes', function () {
        return response()->download(public_path('plantillas/plantilla_titulares.xlsx'));
    })->name('importar.plantilla-contribuyentes');

    Route::get('importar/plantilla-actividades', function () {
        return response()->download(public_path('plantillas/plantilla_actividades.xlsx'));
    })->name('importar.plantilla-actividades');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // Solicitudes panel funcionario
    Route::get('solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
    Route::post('solicitudes/{solicitud}/procesar', [SolicitudController::class, 'procesarEstado'])->name('solicitudes.procesar');
    Route::get('reportes/vencer-pdf', [ReporteController::class, 'vencerPdf'])->name('reportes.vencer-pdf');
});

require __DIR__.'/auth.php';
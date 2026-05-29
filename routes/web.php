<?php

use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\LicenciaHistoricaController;
use App\Http\Controllers\ImportExampleController;
use App\Http\Controllers\ContribuyenteController;
use App\Http\Controllers\ActividadEconomicaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ConsultaPublicaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\RevisionSolicitudController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LicenciaSignatureController;
use App\Http\Controllers\AdminSignatureController;
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
Route::any('tramite/enviar', [SolicitudController::class, 'enviar'])->name('solicitudes.enviar');
Route::get('tramite/confirmacion/{codigo}', [SolicitudController::class, 'confirmacion'])->name('solicitudes.confirmacion');
Route::get('tramite/seguimiento', [SolicitudController::class, 'seguimiento'])->name('solicitudes.seguimiento');
Route::get('tramite/comprobante/{codigo}', [SolicitudController::class, 'formularioComprobante'])->name('solicitudes.formulario.comprobante');
Route::post('tramite/comprobante/{codigo}', [SolicitudController::class, 'guardarComprobante'])->name('solicitudes.guardar.comprobante');

// Revisión de solicitudes (público - para revisores)
Route::get('revision/{token}', [RevisionSolicitudController::class, 'formulario'])->name('revision.formulario');
Route::get('revision/{token}/detalles', [RevisionSolicitudController::class, 'detallesFrPublico'])->name('revision.detalles');
Route::post('revision/{token}/guardar', [RevisionSolicitudController::class, 'guardar'])->name('revision.guardar');

// APIs Consultas Públicas (DNI/RUC) - Sin CSRF
Route::withoutMiddleware(['Illuminate\Foundation\Http\Middleware\VerifyCsrfToken'])->group(function () {
    Route::post('api/consultar-dni', [SolicitudController::class, 'consultarDNI'])->name('api.consultar-dni');
    Route::post('api/consultar-ruc', [SolicitudController::class, 'consultarRUC'])->name('api.consultar-ruc');
});

// ===== RUTAS PRIVADAS =====
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/exportar-vencer-pdf', [DashboardController::class, 'exportarVencerPdf'])->name('dashboard.exportar-vencer-pdf');

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
    Route::post('solicitudes/{solicitud}/enviar-revision', [SolicitudController::class, 'enviarARevision'])->name('solicitudes.enviar-revision');
    Route::post('solicitudes/{solicitud}/actualizar-pago', [SolicitudController::class, 'actualizarEstadoPago'])->name('solicitudes.actualizar-pago');
    Route::get('solicitudes/{solicitud}/descargar/{tipo}', [SolicitudController::class, 'descargarDocumento'])->name('solicitudes.descargar');
    
    // API Notificaciones (polling)
    Route::get('api/notificaciones/nuevas-solicitudes', [SolicitudController::class, 'obtenerNuevasSolicitudes'])->name('api.notificaciones.nuevas-solicitudes');
    
    Route::get('reportes/vencer-pdf', [ReporteController::class, 'vencerPdf'])->name('reportes.vencer-pdf');

    // ===== LICENCIAS HISTÓRICAS =====
    Route::prefix('licencias-historicas')->name('licencias-historicas.')->group(function () {
        Route::get('/', [LicenciaHistoricaController::class, 'index'])->name('index');
        Route::get('/importar', [LicenciaHistoricaController::class, 'importar'])->name('importar');
        Route::post('/previsualizar', [LicenciaHistoricaController::class, 'previsualizar'])->name('previsualizar');
        Route::post('/confirmar', [LicenciaHistoricaController::class, 'confirmar'])->name('confirmar');
        Route::get('/listar', [LicenciaHistoricaController::class, 'listar'])->name('listar');
        Route::get('/exportar-excel', [LicenciaHistoricaController::class, 'exportarExcel'])->name('exportar-excel');
        Route::get('/exportar-pdf', [LicenciaHistoricaController::class, 'exportarPdf'])->name('exportar-pdf');
        Route::get('/{licenciaHistorica}', [LicenciaHistoricaController::class, 'show'])->name('show');
        Route::delete('/{licenciaHistorica}', [LicenciaHistoricaController::class, 'destroy'])->name('destroy');
        Route::get('/exportar', [LicenciaHistoricaController::class, 'exportar'])->name('exportar');
        Route::get('/descargar-ejemplo', [ImportExampleController::class, 'descargarEjemplo'])->name('descargar-ejemplo');
    });

    // ===== RUTAS DE FIRMA DIGITAL =====
    // Rutas para usuarios/admin (firmar certificados)
    Route::get('licencias/{licencia}/firmar', [LicenciaSignatureController::class, 'show'])
        ->name('licencias.firmar');
    Route::get('licencias/{licencia}/preview-firma', [LicenciaSignatureController::class, 'previewFirma'])
        ->name('licencias.preview-firma');
    Route::post('licencias/{licencia}/firmar', [LicenciaSignatureController::class, 'firmar'])
        ->name('licencias.firmar.procesar');
    Route::post('licencias/{licencia}/adjuntar-pdf-firmado', [LicenciaSignatureController::class, 'adjuntarPdfFirmado'])
        ->name('licencias.adjuntar-pdf-firmado');
    Route::get('licencias/{licencia}/descargar', [LicenciaSignatureController::class, 'descargar'])
        ->name('licencias.descargar');
    Route::get('licencias/{licencia}/descargar-original', [LicenciaSignatureController::class, 'descargarOriginal'])
        ->name('licencias.descargar-original');

    // Rutas para admin (gestionar firmas de usuarios)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('gestionar-firmas', [AdminSignatureController::class, 'index'])
            ->name('signatures.index');
        Route::get('gestionar-firmas/{user}/editar', [AdminSignatureController::class, 'edit'])
            ->name('signatures.edit');
        Route::post('gestionar-firmas/{user}/guardar', [AdminSignatureController::class, 'store'])
            ->name('signatures.store');
        Route::delete('gestionar-firmas/{user}', [AdminSignatureController::class, 'destroy'])
            ->name('signatures.destroy');
    });
});

// ===== RUTA PARA SERVIR ARCHIVOS PÚBLICOS =====
Route::get('storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    
    if (!file_exists($file)) {
        abort(404);
    }
    
    return response()->file($file);
})->where('path', '.*')->name('storage.serve');

require __DIR__.'/auth.php';
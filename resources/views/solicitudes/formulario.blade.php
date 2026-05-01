<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trámite Online ITSE M.A..A.C.D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">

    <style>
        body { background: linear-gradient(180deg, #12961dff 0%, #0f2812ff 100%); min-height: 100vh; padding: 30px 0; }
        .form-card { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .header-municipalidad { text-align: center; color: white; margin-bottom: 25px; }
        .header-municipalidad h4 { font-size: 18px; font-weight: 700; }
        .header-municipalidad p { font-size: 13px; color: #d4f5d4; }
        .section-title { background: #17B323; color: white; padding: 8px 15px; border-radius: 6px; margin: 15px 0 10px; font-size: 13px; }
        .footer-pub { text-align: center; color: #d4f5d4; font-size: 12px; padding: 15px; }

        .paso { display: none; }
        .paso.activo { display: block; }

        .progress-steps { display: flex; justify-content: center; gap: 10px; margin-bottom: 25px; }
        .step-dot { width: 35px; height: 35px; border-radius: 50%; background: rgba(255,255,255,0.3); color: white; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; transition: all 0.3s; }
        .step-dot.activo { background: white; color: #17B323; }
        .step-dot.completado { background: #17B323; border: 2px solid white; color: white; }
        .step-line { width: 40px; height: 2px; background: rgba(255,255,255,0.3); align-self: center; }
        .step-line.completado { background: white; }

        .opcion-card { cursor: pointer; border: 2px solid #dee2e6; border-radius: 12px; padding: 25px; text-align: center; transition: all 0.2s; }
        .opcion-card:hover { border-color: #17B323; background: #f0fff0; transform: translateY(-3px); }
        .opcion-card i { font-size: 40px; margin-bottom: 10px; color: #17B323; }
        .opcion-card h5 { font-weight: bold; margin-bottom: 5px; }
        .opcion-card p { font-size: 13px; color: #666; margin: 0; }

        .area-card { cursor: pointer; border: 2px solid #dee2e6; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.2s; }
        .area-card:hover { border-color: #17B323; background: #f0fff0; }
        .area-card i { font-size: 30px; color: #17B323; margin-bottom: 8px; }
        .area-card h6 { font-weight: bold; }
        .area-card small { color: #666; }

        /* ERRORES */
        .campo-error { border-color: #dc3545 !important; background-color: #fff5f5 !important; }
        .mensaje-error { background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px 15px; margin-bottom: 15px; font-size: 13px; display: none; }
        .mensaje-error ul { margin: 5px 0 0 0; padding-left: 20px; }
        .mensaje-error ul li { margin-bottom: 3px; }
        .label-error { color: #dc3545; font-size: 12px; margin-top: 3px; display: none; }

        /* PRECIO */
        .precio-box {
            display: none;
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            border: 2px solid #17B323;
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 15px;
            text-align: center;
        }
        .precio-box .precio-label { font-size: 13px; color: #555; margin-bottom: 4px; }
        .precio-box .precio-monto { font-size: 28px; font-weight: bold; color: #17B323; }
        .precio-box .precio-monto span { font-size: 14px; color: #888; }
        .precio-box .precio-tipo { font-size: 12px; color: #777; margin-top: 4px; }
        .precio-box .precio-nota { font-size: 11px; color: #999; margin-top: 6px; font-style: italic; }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-end gap-2 mb-2">
    <a href="{{ route('solicitudes.seguimiento') }}" class="btn btn-sm" style="background:rgba(7, 7, 77, 0.55); color:white; border:1px solid rgba(39, 3, 93, 0.3);">
        <i class="fas fa-search me-2"></i>Seguimiento Trámite
    </a>
    <a href="{{ route('login') }}" class="btn btn-sm" style="background:rgba(7, 7, 77, 0.55); color:white; border:1px solid rgba(39, 3, 93, 0.3);">
        <i class="fas fa-lock me-2"></i>Acceso Funcionarios
    </a>
</div>

    <div class="header-municipalidad">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:70px; margin-bottom:10px;">
        <h4>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</h4>
        <p><i class="fas fa-file-alt me-2"></i>Solicitud de Certificado ITSE en Línea</p>
    </div>

    <div class="progress-steps">
        <div class="step-dot activo" id="dot1">1</div>
        <div class="step-line" id="line1"></div>
        <div class="step-dot" id="dot2">2</div>
        <div class="step-line" id="line2"></div>
        <div class="step-dot" id="dot3">3</div>
        <div class="step-line" id="line3"></div>
        <div class="step-dot" id="dot4">4</div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('solicitudes.enviar') }}" method="POST" enctype="multipart/form-data" id="formSolicitud">
                @csrf
                <input type="hidden" name="tipo_certificado" id="tipo_certificado_hidden">

                {{-- PASO 1 --}}
                <div class="paso activo" id="paso1">
                    <div class="card form-card p-4">
                        <h5 class="fw-bold text-center mb-4">
                            <i class="fas fa-question-circle text-success me-2"></i>
                            ¿Qué necesitas certificar?
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="opcion-card" onclick="elegirTipo('evento')">
                                    <i class="fas fa-calendar-star"></i>
                                    <h5>Organizar un Evento</h5>
                                    <p>Conciertos, ferias, espectáculos públicos, festivales</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="opcion-card" onclick="elegirTipo('negocio')">
                                    <i class="fas fa-store"></i>
                                    <h5>Mi Local o Negocio</h5>
                                    <p>Tiendas, restaurantes, oficinas, instituciones educativas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PASO 2A: Tipo de negocio --}}
                <div class="paso" id="paso2negocio">
                    <div class="card form-card p-4">
                        <h5 class="fw-bold text-center mb-2">
                            <i class="fas fa-store text-success me-2"></i>
                            ¿Qué tipo de negocio tienes?
                        </h5>
                        <p class="text-center text-muted mb-4">Selecciona el que más se parezca a tu local</p>

                        <p class="fw-bold text-success mb-2"><i class="fas fa-circle me-1" style="font-size:10px;"></i> Riesgo Bajo / Medio</p>
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Bodega / Tienda de abarrotes', 'anexo_13', 'bajo')">
                                    <i class="fas fa-shopping-basket d-block"></i>
                                    <h6>Bodega</h6>
                                    <small>Tienda de abarrotes</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Farmacia / Botica', 'anexo_13', 'medio')">
                                    <i class="fas fa-pills d-block"></i>
                                    <h6>Farmacia / Botica</h6>
                                    <small>Consultorio, óptica</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Restaurante / Cevichería', 'anexo_13', 'medio')">
                                    <i class="fas fa-utensils d-block"></i>
                                    <h6>Restaurante</h6>
                                    <small>Cevichería, picantería</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Oficina / Consultorio', 'anexo_13', 'medio')">
                                    <i class="fas fa-briefcase d-block"></i>
                                    <h6>Oficina</h6>
                                    <small>Consultorio, estudio</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Peluquería / Salón de belleza', 'anexo_13', 'bajo')">
                                    <i class="fas fa-cut d-block"></i>
                                    <h6>Peluquería</h6>
                                    <small>Salón de belleza</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Institución educativa', 'anexo_13', 'medio')">
                                    <i class="fas fa-school d-block"></i>
                                    <h6>Colegio / Academia</h6>
                                    <small>Institución educativa</small>
                                </div>
                            </div>
                        </div>

                        <p class="fw-bold text-danger mb-2"><i class="fas fa-circle me-1" style="font-size:10px;"></i> Riesgo Alto / Muy Alto</p>
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Grifo / Estación de combustible', 'anexo_14', 'alto')">
                                    <i class="fas fa-gas-pump d-block"></i>
                                    <h6>Grifo</h6>
                                    <small>Estación de combustible</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Almacén / Depósito', 'anexo_14', 'alto')">
                                    <i class="fas fa-warehouse d-block"></i>
                                    <h6>Almacén</h6>
                                    <small>Depósito, ferretería</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Hospital / Clínica', 'anexo_14', 'muyalto')">
                                    <i class="fas fa-hospital d-block"></i>
                                    <h6>Hospital / Clínica</h6>
                                    <small>Centro de salud</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Centro comercial / Galería', 'anexo_14', 'muyalto')">
                                    <i class="fas fa-building d-block"></i>
                                    <h6>Centro comercial</h6>
                                    <small>Galería, mercado</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Discoteca / Bar / Karaoke', 'anexo_14', 'muyalto')">
                                    <i class="fas fa-music d-block"></i>
                                    <h6>Discoteca / Bar</h6>
                                    <small>Karaoke, recreo</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Hotel / Hospedaje', 'anexo_14', 'alto')">
                                    <i class="fas fa-hotel d-block"></i>
                                    <h6>Hotel / Hospedaje</h6>
                                    <small>Hostal, alojamiento</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-start mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="irPaso(1)">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PASO 2B: Confirmación evento --}}
                <div class="paso" id="paso2evento">
                    <div class="card form-card p-4 text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Certificado de Evento Público</h5>
                        <p class="text-muted">Para organizar tu evento necesitas un <strong>Certificado de Inspección Técnica de Seguridad Previa a Evento y/o Espectáculo Público</strong></p>
                        <div class="precio-box" style="display:block; margin: 15px auto; max-width:250px;">
                            <div class="precio-label">Costo del trámite</div>
                            <div class="precio-monto">S/ 178.90 <span></span></div>
                            <div class="precio-tipo">Evento / Espectáculo Público</div>
                        </div>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="irPaso(1)">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                            <button type="button" class="btn btn-success" onclick="irPaso(3)">
                                Continuar <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PASO 3: Datos --}}
                <div class="paso" id="paso3">
                    <div class="card form-card p-4">
                        <div class="alert alert-success mb-4" id="resumen-tipo">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="texto-tipo"></span>
                        </div>

                        <div class="mensaje-error" id="mensajeError">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                            <strong>Por favor completa los siguientes campos obligatorios:</strong>
                            <ul id="listaErrores"></ul>
                        </div>

                        <div class="section-title"><i class="fas fa-user me-2"></i>Tus Datos</div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nombres y Apellidos / Razón Social <span class="text-danger">*</span></label>
                                <input type="text" name="nombres_solicitante" id="nombres_solicitante"
                                    class="form-control @error('nombres_solicitante') is-invalid @enderror"
                                    value="{{ old('nombres_solicitante') }}">
                                <div class="label-error" id="err_nombres">Ingresa tu nombre completo</div>
                                @error('nombres_solicitante')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">DNI / RUC <span class="text-danger">*</span></label>
                                <input type="text" name="dni_ruc" id="dni_ruc"
                                    class="form-control @error('dni_ruc') is-invalid @enderror"
                                    value="{{ old('dni_ruc') }}"
                                    maxlength="11" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    placeholder="8 a 11 dígitos">
                                <div class="label-error" id="err_dni">Ingresa tu DNI o RUC</div>
                                @error('dni_ruc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">WhatsApp <i class="fab fa-whatsapp text-success"></i> <span class="text-danger">*</span></label>
                                <input type="text" name="telefono_whatsapp" id="telefono_whatsapp"
                                    class="form-control @error('telefono_whatsapp') is-invalid @enderror"
                                    value="{{ old('telefono_whatsapp') }}"
                                    maxlength="9" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    placeholder="9 dígitos">
                                <div class="label-error" id="err_telefono">Ingresa tu número de WhatsApp</div>
                                @error('telefono_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Correo electrónico (opcional)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        {{-- DATOS NEGOCIO --}}
                        <div id="datos-negocio">
                            <div class="section-title"><i class="fas fa-store me-2"></i>Datos del Local</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nombre Comercial <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre_comercial" id="nombre_comercial_neg"
                                        class="form-control" value="{{ old('nombre_comercial') }}">
                                    <div class="label-error" id="err_nombre_comercial">Ingresa el nombre comercial</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Dirección <span class="text-danger">*</span></label>
                                    <input type="text" name="direccion" id="direccion_neg"
                                        class="form-control" value="{{ old('direccion') }}">
                                    <div class="label-error" id="err_direccion_neg">Ingresa la dirección del local</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Provincia <span class="text-danger">*</span></label>
                                    <input type="text" name="provincia" id="provincia_neg" class="form-control" value="{{ old('provincia', 'HUAMANGA') }}">
                                    <div class="label-error" id="err_provincia">Ingresa la provincia</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Departamento <span class="text-danger">*</span></label>
                                    <input type="text" name="departamento" id="departamento_neg" class="form-control" value="{{ old('departamento', 'AYACUCHO') }}">
                                    <div class="label-error" id="err_departamento">Ingresa el departamento</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Área (m2) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="1" max="99999.99"
                                        name="area_edificacion" id="area_neg"
                                        class="form-control" value="{{ old('area_edificacion') }}"
                                        placeholder="Ej: 250.50"
                                        oninput="calcularPrecio()">
                                    <div class="label-error" id="err_area_neg">Ingresa el área en m²</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Giro o Actividad <span class="text-danger">*</span></label>
                                    <input type="text" name="actividad" id="actividad_neg"
                                        class="form-control" value="{{ old('actividad') }}"
                                        placeholder="Se completó automáticamente según tu selección">
                                    <div class="label-error" id="err_actividad">Ingresa el giro o actividad del local</div>
                                </div>
                            </div>

                            {{-- PRECIO CALCULADO --}}
                            <div class="precio-box" id="precio-box">
                                <div class="precio-label">Costo estimado del trámite</div>
                                <div class="precio-monto" id="precio-monto">S/ 0.00</div>
                                <div class="precio-tipo" id="precio-tipo"></div>
                                <div class="precio-nota">* El monto puede variar según verificación municipal</div>
                            </div>
                        </div>

                        {{-- DATOS EVENTO --}}
                        <div id="datos-evento" style="display:none;">
                            <div class="section-title"><i class="fas fa-calendar-alt me-2"></i>Datos del Evento</div>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold">Nombre del Evento <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre_evento" id="nombre_evento" class="form-control" value="{{ old('nombre_evento') }}">
                                    <div class="label-error" id="err_nombre_evento">Ingresa el nombre del evento</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Fecha del Evento <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_evento" id="fecha_evento" class="form-control" value="{{ old('fecha_evento') }}">
                                    <div class="label-error" id="err_fecha_evento">Selecciona la fecha del evento</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nombre del Lugar <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre_comercial" id="nombre_comercial_ev"
                                        class="form-control" value="{{ old('nombre_comercial') }}">
                                    <div class="label-error" id="err_nombre_lugar">Ingresa el nombre del lugar</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Dirección del Lugar <span class="text-danger">*</span></label>
                                    <input type="text" name="direccion" id="direccion_ev"
                                        class="form-control" value="{{ old('direccion') }}">
                                    <div class="label-error" id="err_direccion_ev">Ingresa la dirección del lugar</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Organizador <span class="text-danger">*</span></label>
                                    <input type="text" name="organizador_nombre" id="organizador_nombre" class="form-control" value="{{ old('organizador_nombre') }}">
                                    <div class="label-error" id="err_organizador">Ingresa el nombre del organizador</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">DNI Organizador <span class="text-danger">*</span></label>
                                    <input type="text" name="organizador_dni" id="organizador_dni" class="form-control"
                                        value="{{ old('organizador_dni') }}"
                                        maxlength="8" inputmode="numeric"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    <div class="label-error" id="err_organizador_dni">Ingresa el DNI del organizador</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Área (m2) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="1" max="99999.99"
                                        name="area_edificacion" id="area_ev"
                                        class="form-control" value="{{ old('area_edificacion') }}"
                                        placeholder="Ej: 6845.40">
                                    <div class="label-error" id="err_area_ev">Ingresa el área en m²</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="volverPaso2()">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                            <button type="button" class="btn btn-success" onclick="validarYContinuar()">
                                Continuar <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PASO 4 --}}
                <div class="paso" id="paso4">
                    <div class="card form-card p-4">
                        <div class="section-title"><i class="fas fa-paperclip me-2"></i>Documentos Adjuntos</div>
                        <div class="alert alert-warning mb-3">
    <i class="fas fa-download me-2"></i>
    <strong>¿No tienes el formato FUT?</strong> Descárgalo, llénalo e imprímelo para adjuntarlo.
    <a href="{{ asset('plantillas/FUT_PDF.pdf') }}" target="_blank" class="btn btn-warning btn-sm ms-2">
        <i class="fas fa-file-pdf me-1"></i>Descargar FUT
    </a>
</div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Solicitud / FUT</label>
                                <input type="file" name="doc_solicitud" class="form-control" accept=".pdf,.jpg,.png">
                                <small class="text-muted">PDF, JPG o PNG</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Plano / Croquis</label>
                                <input type="file" name="doc_plano" class="form-control" accept=".pdf,.jpg,.png">
                                <small class="text-muted">PDF, JPG o PNG</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Otros documentos</label>
                                <input type="file" name="doc_otros" class="form-control" accept=".pdf,.jpg,.png">
                                <small class="text-muted">PDF, JPG o PNG</small>
                            </div>
                        </div>

                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Al enviar recibirás un <strong>código de seguimiento</strong> para consultar el estado de tu trámite.
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="irPaso(3)">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="footer-pub">
    <p>© {{ date('Y') }} Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let tipoCertificado = '';
let tipoFlujo      = '';
let nivelRiesgo    = ''; // 'bajo', 'medio', 'alto', 'muyalto'

// ============================================================
// TABLA DE PRECIOS — modifica aquí cuando cambien los precios
// ============================================================
const PRECIOS = {
    anexo_13: {
        bajo:  { menorA100: 99.80,  mayorA100: 99.80  },  // bodegas: precio fijo
        medio: { menorA100: 99.80,  mayorA100: 133.80 },  // resto anexo 13
    },
    anexo_14: {
        alto:    { menorA100: 316.80, mayorA100: 546.40 },
        muyalto: { menorA100: 316.80, mayorA100: 546.40 },
    },
    evento_publico: 178.90
};
// ============================================================

function actualizarDots(pasoActual) {
    for (let i = 1; i <= 4; i++) {
        const dot  = document.getElementById('dot' + i);
        const line = document.getElementById('line' + i);
        if (i < pasoActual) {
            dot.className = 'step-dot completado';
            dot.innerHTML = '<i class="fas fa-check" style="font-size:11px;"></i>';
        } else if (i == pasoActual) {
            dot.className = 'step-dot activo';
            dot.innerHTML = i;
        } else {
            dot.className = 'step-dot';
            dot.innerHTML = i;
        }
        if (line && i < pasoActual) line.className = 'step-line completado';
        else if (line) line.className = 'step-line';
    }
}

function ocultarTodos() {
    document.querySelectorAll('.paso').forEach(p => p.classList.remove('activo'));
}

function elegirTipo(tipo) {
    tipoFlujo = tipo;
    ocultarTodos();
    if (tipo === 'evento') {
        tipoCertificado = 'evento_publico';
        document.getElementById('tipo_certificado_hidden').value = 'evento_publico';
        document.getElementById('paso2evento').classList.add('activo');
        actualizarDots(2);
    } else {
        document.getElementById('paso2negocio').classList.add('activo');
        actualizarDots(2);
    }
}

function elegirNegocio(nombreNegocio, anexo, riesgo) {
    tipoCertificado = anexo;
    nivelRiesgo     = riesgo;
    document.getElementById('tipo_certificado_hidden').value = anexo;
    // Pre-llenar el campo actividad con el tipo elegido
    document.getElementById('actividad_neg').value = nombreNegocio;
    irPaso(3);
}

function calcularPrecio() {
    const area = parseFloat(document.getElementById('area_neg').value) || 0;
    if (!tipoCertificado || tipoCertificado === 'evento_publico' || area <= 0) return;

    const tabla  = PRECIOS[tipoCertificado][nivelRiesgo];
    const precio = area < 100 ? tabla.menorA100 : tabla.mayorA100;
    const rango  = area < 100 ? 'Área menor a 100 m²' : 'Área mayor o igual a 100 m²';

    document.getElementById('precio-monto').textContent = 'S/ ' + precio.toFixed(2);
    document.getElementById('precio-tipo').textContent  =
        (tipoCertificado === 'anexo_13' ? 'Anexo 13 — Riesgo Bajo/Medio' : 'Anexo 14 — Riesgo Alto/Muy Alto')
        + ' · ' + rango;
    document.getElementById('precio-box').style.display = 'block';
}

function irPaso(n) {
    ocultarTodos();
    if (n == 3) {
        document.getElementById('paso3').classList.add('activo');
        actualizarDots(3);
        if (tipoCertificado === 'evento_publico') {
            document.getElementById('datos-negocio').style.display = 'none';
            document.getElementById('datos-evento').style.display  = 'block';
            document.getElementById('texto-tipo').innerHTML = '📅 <strong>Evento Público</strong> — Certificado de Inspección Técnica Previa a Evento';
            document.getElementById('nombre_comercial_neg').disabled = true;
            document.getElementById('direccion_neg').disabled        = true;
            document.getElementById('nombre_comercial_ev').disabled  = false;
            document.getElementById('direccion_ev').disabled         = false;
        } else {
            document.getElementById('datos-negocio').style.display = 'block';
            document.getElementById('datos-evento').style.display  = 'none';
            const label = tipoCertificado === 'anexo_13'
                ? '🏪 <strong>Riesgo Bajo/Medio (Anexo 13)</strong> — Local menor a 500 m²'
                : '🏢 <strong>Riesgo Alto/Muy Alto (Anexo 14)</strong> — Local mayor a 500 m²';
            document.getElementById('texto-tipo').innerHTML = label;
            document.getElementById('nombre_comercial_neg').disabled = false;
            document.getElementById('direccion_neg').disabled        = false;
            document.getElementById('nombre_comercial_ev').disabled  = true;
            document.getElementById('direccion_ev').disabled         = true;
            // Recalcular precio si ya hay área
            calcularPrecio();
        }
        limpiarErrores();
    } else if (n == 4) {
        document.getElementById('paso4').classList.add('activo');
        actualizarDots(4);
    } else if (n == 1) {
        document.getElementById('paso1').classList.add('activo');
        actualizarDots(1);
    }
}

function volverPaso2() {
    ocultarTodos();
    if (tipoFlujo === 'evento') {
        document.getElementById('paso2evento').classList.add('activo');
    } else {
        document.getElementById('paso2negocio').classList.add('activo');
    }
    actualizarDots(2);
}

// ===== VALIDACIÓN =====
function limpiarErrores() {
    document.querySelectorAll('.campo-error').forEach(el => el.classList.remove('campo-error'));
    document.querySelectorAll('.label-error').forEach(el => el.style.display = 'none');
    document.getElementById('mensajeError').style.display = 'none';
    document.getElementById('listaErrores').innerHTML = '';
}

function marcarError(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    if (input) input.classList.add('campo-error');
    if (error) error.style.display = 'block';
}

function validarYContinuar() {
    limpiarErrores();
    const errores = [];

    const nombres  = document.getElementById('nombres_solicitante').value.trim();
    const dni      = document.getElementById('dni_ruc').value.trim();
    const telefono = document.getElementById('telefono_whatsapp').value.trim();

    if (!nombres) { marcarError('nombres_solicitante', 'err_nombres'); errores.push('Nombres y Apellidos / Razón Social'); }
    if (!dni)     { marcarError('dni_ruc', 'err_dni'); errores.push('DNI / RUC'); }
    if (!telefono || telefono.length < 9) { marcarError('telefono_whatsapp', 'err_telefono'); errores.push('WhatsApp (9 dígitos)'); }

    if (tipoCertificado === 'evento_publico') {
        const nombreEvento = document.getElementById('nombre_evento').value.trim();
        const fechaEvento  = document.getElementById('fecha_evento').value.trim();
        const nombreLugar  = document.getElementById('nombre_comercial_ev').value.trim();
        const dirEvento    = document.getElementById('direccion_ev').value.trim();
        const organizador  = document.getElementById('organizador_nombre').value.trim();
        const dniOrg       = document.getElementById('organizador_dni').value.trim();
        const areaEv       = parseFloat(document.getElementById('area_ev').value);

        if (!nombreEvento)           { marcarError('nombre_evento', 'err_nombre_evento'); errores.push('Nombre del Evento'); }
        if (!fechaEvento)            { marcarError('fecha_evento', 'err_fecha_evento'); errores.push('Fecha del Evento'); }
        if (!nombreLugar)            { marcarError('nombre_comercial_ev', 'err_nombre_lugar'); errores.push('Nombre del Lugar'); }
        if (!dirEvento)              { marcarError('direccion_ev', 'err_direccion_ev'); errores.push('Dirección del Lugar'); }
        if (!organizador)            { marcarError('organizador_nombre', 'err_organizador'); errores.push('Nombre del Organizador'); }
        if (!dniOrg)                 { marcarError('organizador_dni', 'err_organizador_dni'); errores.push('DNI del Organizador'); }
        if (!areaEv || areaEv <= 0)  { marcarError('area_ev', 'err_area_ev'); errores.push('Área (m2)'); }
        else if (areaEv > 99999.99)  { marcarError('area_ev', 'err_area_ev'); errores.push('Área (m2): máximo 99,999 m²'); }
    } else {
        const nombreCom = document.getElementById('nombre_comercial_neg').value.trim();
        const dirNeg    = document.getElementById('direccion_neg').value.trim();
        const provincia = document.getElementById('provincia_neg').value.trim();
        const depto     = document.getElementById('departamento_neg').value.trim();
        const areaNeg   = parseFloat(document.getElementById('area_neg').value);
        const actividad = document.getElementById('actividad_neg').value.trim();

        if (!nombreCom)                { marcarError('nombre_comercial_neg', 'err_nombre_comercial'); errores.push('Nombre Comercial'); }
        if (!dirNeg)                   { marcarError('direccion_neg', 'err_direccion_neg'); errores.push('Dirección del Local'); }
        if (!provincia)                { marcarError('provincia_neg', 'err_provincia'); errores.push('Provincia'); }
        if (!depto)                    { marcarError('departamento_neg', 'err_departamento'); errores.push('Departamento'); }
        if (!areaNeg || areaNeg <= 0)  { marcarError('area_neg', 'err_area_neg'); errores.push('Área (m2)'); }
        else if (areaNeg > 99999.99)   { marcarError('area_neg', 'err_area_neg'); errores.push('Área (m2): máximo 99,999 m²'); }
        if (!actividad)                { marcarError('actividad_neg', 'err_actividad'); errores.push('Giro o Actividad'); }
    }

    if (errores.length > 0) {
        const lista = document.getElementById('listaErrores');
        errores.forEach(e => {
            const li = document.createElement('li');
            li.textContent = e;
            lista.appendChild(li);
        });
        document.getElementById('mensajeError').style.display = 'block';
        document.getElementById('mensajeError').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    irPaso(4);
}
</script>
</body>
</html>
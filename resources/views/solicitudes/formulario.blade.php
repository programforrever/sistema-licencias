<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trámite Online ITSE M.A..A.C.D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Local -->
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">

    <!-- Fallback icons via CSS if FontAwesome fails -->
    <style>
        /* Icon display fallback */
        i[class*="fas "], i[class*="fab "], i[class*="far "] {
            display: inline-block;
            font-family: 'FontAwesome';
        }
        
        /* Ensure icons are visible */
        i[class*="fa-"] {
            font-style: normal;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .fa, .fas, .far, .fab, .fa-brands, .fa-classic, .fa-regular, .fa-solid, .fa-sharp { font-family: var(--fa-style-family,"Font Awesome 6 Free") !important; }

        :root {
            --brand:        #2563eb;
            --brand-light:  #eff6ff;
            --brand-dark:   #1e40af;
            --surface:      #ffffff;
            --bg:           #f8fafc;
            --border:       #e2e8f0;
            --text-main:    #0f172a;
            --text-muted:   #64748b;
            --green-main:   #12961d;
            --green-dark:   #0f2812;
        }

        body { 
            background: linear-gradient(180deg, var(--green-main) 0%, var(--green-dark) 100%);
            min-height: 100vh;
            padding: 30px 0;
        }

        .form-card {
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            background: var(--surface);
        }

        .header-municipalidad {
            text-align: center;
            color: white;
            margin-bottom: 25px;
        }

        .header-municipalidad h4 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .header-municipalidad p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
        }

        .section-title {
            background: linear-gradient(135deg, var(--green-main), #1a9e28);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 15px 0 10px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-pub {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            padding: 15px;
        }

        .paso { display: none; }
        .paso.activo { display: block; animation: fadeIn 0.25s ease-in; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .step-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .step-dot.activo {
            background: white;
            color: var(--green-main);
            border-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .step-dot.completado {
            background: var(--green-main);
            border: 2px solid white;
            color: white;
        }

        .step-line {
            width: 40px;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
            align-self: center;
        }

        .step-line.completado { background: white; }

        .opcion-card {
            cursor: pointer;
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.25s ease;
            background: var(--surface);
        }

        .opcion-card:hover {
            border-color: var(--green-main);
            background: #f0fff0;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(18, 150, 29, 0.15);
        }

        .opcion-card i {
            font-size: 40px;
            margin-bottom: 10px;
            color: var(--green-main);
        }

        .opcion-card h5 {
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-main);
        }

        .opcion-card p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        .area-card {
            cursor: pointer;
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.2s ease;
            background: var(--surface);
        }

        .area-card:hover {
            border-color: var(--green-main);
            background: #f0fff0;
            box-shadow: 0 4px 12px rgba(18, 150, 29, 0.1);
        }

        .area-card i {
            font-size: 30px;
            color: var(--green-main);
            margin-bottom: 8px;
        }

        .area-card h6 {
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .area-card small { color: var(--text-muted); }

        /* ERRORES */
        .campo-error {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .mensaje-error {
            background: #fff8e1;
            border: 1px solid #ffd54f;
            border-left: 4px solid #f57c00;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 13px;
            display: none;
            color: #e65100;
        }

        .mensaje-error ul { margin: 5px 0 0 0; padding-left: 20px; }
        .mensaje-error ul li { margin-bottom: 3px; }
        .label-error { color: #dc3545; font-size: 12px; margin-top: 3px; display: none; }

        /* PRECIO */
        .precio-box {
            display: none;
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
            border: 2px solid var(--green-main);
            border-radius: 12px;
            padding: 18px 20px;
            margin-top: 15px;
            text-align: center;
        }

        .precio-box .precio-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 4px;
            font-weight: 600;
        }

        .precio-box .precio-monto {
            font-size: 32px;
            font-weight: 700;
            color: var(--green-main);
            letter-spacing: -0.01em;
        }

        .precio-box .precio-monto span { font-size: 14px; color: var(--text-muted); }
        .precio-box .precio-tipo { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .precio-box .precio-nota { font-size: 11px; color: var(--text-muted); margin-top: 6px; font-style: italic; }

        /* FORM CONTROLS */
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
            font-size: 0.85rem;
            color: var(--text-main);
            background: var(--bg);
            transition: border-color 0.15s, box-shadow 0.15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(18, 150, 29, 0.12);
            outline: none;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        /* BUTTONS */
        .btn-success {
            background: var(--green-main);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .btn-success:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border: 1.5px solid var(--border);
            color: var(--text-muted);
            border-radius: 8px;
            transition: all 0.15s;
        }

        .btn-outline-secondary:hover {
            border-color: var(--green-main);
            color: var(--green-main);
            background: #f0fff0;
        }

        /* ALERTS */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-left: 4px solid var(--green-main);
            color: #166534;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        /* BÚSQUEDA ESTADO */
        #busqueda-estado { margin-top: 8px; }
        #busqueda-cargando { color: var(--brand); }
        #busqueda-exito {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }
        #busqueda-error {
            background: #fff5f5;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        /* SELECTOR DE DÍAS */
        .dias-selector {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .dias-btn {
            width: 50px;
            height: 50px;
            border: 2px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: var(--text-muted);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .dias-btn:hover {
            border-color: var(--green-main);
            background: #f0fff0;
        }
        
        .dias-btn.activo {
            background: var(--green-main);
            border-color: var(--green-main);
            color: white;
            box-shadow: 0 4px 12px rgba(18, 150, 29, 0.3);
        }

        /* INPUT PERSONALIZADO DE DÍAS */
        #dias-personalizado {
            border: 2px solid var(--border) !important;
            border-radius: 8px !important;
            padding: 0.55rem 0.75rem !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: var(--text-main) !important;
            background: var(--surface) !important;
            transition: all 0.2s ease !important;
        }

        #dias-personalizado:focus {
            border-color: var(--green-main) !important;
            box-shadow: 0 0 0 3px rgba(18, 150, 29, 0.12) !important;
            outline: none !important;
        }

        #dias-personalizado::-webkit-outer-spin-button,
        #dias-personalizado::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #dias-personalizado[type=number] {
            -moz-appearance: textfield;
        }

        /* DRAG & DROP ZONES */
        .drag-drop-zone {
            border: 2px dashed #0055cc;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: rgba(0, 85, 204, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 180px;
            position: relative;
            overflow: hidden;
        }

        .drag-drop-zone:hover {
            background-color: rgba(0, 85, 204, 0.12);
            border-color: #0044a0;
            box-shadow: 0 4px 12px rgba(0, 85, 204, 0.1);
        }

        .drag-drop-zone i {
            display: block;
            margin-bottom: 12px;
            transition: transform 0.3s ease;
        }

        .drag-drop-zone:hover i {
            transform: scale(1.1);
        }

        .drag-drop-zone p {
            margin: 0;
            color: #333;
        }

        .file-preview {
            animation: slideIn 0.2s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .file-preview button.btn-outline-danger {
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .file-preview button.btn-outline-danger:hover {
            background: #dc3545;
            color: white;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .progress-steps { gap: 5px; }
            .step-dot { width: 35px; height: 35px; font-size: 12px; }
            .step-line { width: 20px; }
            .opcion-card { padding: 18px; }
            .area-card { padding: 15px; }
            .precio-box { margin: 10px auto; }
            .dias-selector { gap: 5px; }
            .dias-btn { width: 45px; height: 45px; font-size: 12px; }
            #dias-personalizado { font-size: 0.95rem !important; padding: 0.5rem 0.6rem !important; }
            .drag-drop-zone { padding: 20px 15px; min-height: 150px; }
            .drag-drop-zone i { font-size: 2rem; }
        }
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
        <div class="step-line" id="line4"></div>
        <div class="step-dot" id="dot5">5</div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('solicitudes.enviar') }}" method="POST" enctype="multipart/form-data" id="formSolicitud" onsubmit="copiarDatosAntesDePHP()">
                @csrf
                <input type="hidden" name="tipo_certificado" id="tipo_certificado_hidden">
                <input type="hidden" name="monto_pago" id="monto_pago_hidden" value="0">
                <input type="hidden" name="dias_evento" id="dias_evento_hidden" value="1">
                <!-- Hidden fields para copiar datos de paso 3 que está en divs display:none -->
                <input type="hidden" name="nombre_comercial" id="nombre_comercial_global">
                <input type="hidden" name="direccion" id="direccion_global">
                <input type="hidden" name="provincia" id="provincia_global">
                <input type="hidden" name="departamento" id="departamento_global">
                <input type="hidden" name="area_edificacion" id="area_edificacion_global">
                <input type="hidden" name="actividad" id="actividad_global">

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
                                <div class="area-card" onclick="elegirNegocio('Bodega / Tienda de abarrotes', 'anexo_13', 'bajo', 'bodega')">
                                    <i class="fas fa-shopping-basket d-block"></i>
                                    <h6>Bodega</h6>
                                    <small>Tienda de abarrotes</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Farmacia / Botica', 'anexo_13', 'medio', 'farmacia')">
                                    <i class="fas fa-pills d-block"></i>
                                    <h6>Farmacia / Botica</h6>
                                    <small>Consultorio, óptica</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Restaurante / Cevichería', 'anexo_13', 'medio', 'restaurante_menu')">
                                    <i class="fas fa-utensils d-block"></i>
                                    <h6>Restaurante</h6>
                                    <small>Cevichería, picantería</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Oficina / Consultorio', 'anexo_13', 'medio', 'oficina_admin')">
                                    <i class="fas fa-briefcase d-block"></i>
                                    <h6>Oficina</h6>
                                    <small>Consultorio, estudio</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Peluquería / Salón de belleza', 'anexo_13', 'bajo', 'peluqueria')">
                                    <i class="fas fa-cut d-block"></i>
                                    <h6>Peluquería</h6>
                                    <small>Salón de belleza</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Institución educativa', 'anexo_13', 'medio', 'educativo')">
                                    <i class="fas fa-school d-block"></i>
                                    <h6>Colegio / Academia</h6>
                                    <small>Institución educativa</small>
                                </div>
                            </div>
                        </div>

                        <p class="fw-bold text-danger mb-2"><i class="fas fa-circle me-1" style="font-size:10px;"></i> Riesgo Alto / Muy Alto</p>
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Grifo / Estación de combustible', 'anexo_14', 'alto', 'grifo')">
                                    <i class="fas fa-gas-pump d-block"></i>
                                    <h6>Grifo</h6>
                                    <small>Estación de combustible</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Almacén / Depósito', 'anexo_14', 'alto', 'almacen')">
                                    <i class="fas fa-warehouse d-block"></i>
                                    <h6>Almacén</h6>
                                    <small>Depósito, ferretería</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Hospital / Clínica', 'anexo_14', 'muyalto', 'hospital')">
                                    <i class="fas fa-hospital d-block"></i>
                                    <h6>Hospital / Clínica</h6>
                                    <small>Centro de salud</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Centro comercial / Galería', 'anexo_14', 'muyalto', 'centro_comercial')">
                                    <i class="fas fa-building d-block"></i>
                                    <h6>Centro comercial</h6>
                                    <small>Galería, mercado</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Discoteca / Bar / Karaoke', 'anexo_14', 'muyalto', 'discoteca')">
                                    <i class="fas fa-music d-block"></i>
                                    <h6>Discoteca / Bar</h6>
                                    <small>Karaoke, recreo</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="area-card" onclick="elegirNegocio('Hotel / Hospedaje', 'anexo_14', 'alto', 'hotel')">
                                    <i class="fas fa-hotel d-block"></i>
                                    <h6>Hotel / Hospedaje</h6>
                                    <small>Hostal, alojamiento</small>
                                </div>
                            </div>
                        </div>

                        {{-- AVISO DE PAGO PRESENCIAL --}}
                        <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 2px solid #fca5a5; border-left: 6px solid #dc2626; color: #7f1d1d; border-radius: 10px; padding: 1.5rem; font-size: 0.9rem; margin: 1.5rem 0; line-height: 1.6;">
                            <div style="font-size: 1.2rem; margin-bottom: 0.75rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div><strong>⚠️ IMPORTANTE - PAGO PRESENCIAL</strong></div>
                            <p style="margin: 0.75rem 0 0.5rem 0;">
                                El pago del trámite se realiza <strong>presencialmente en nuestras instalaciones</strong>.
                            </p>
                            <ul style="margin: 0.5rem 0; padding-left: 1.5rem;">
                                <li>Completa tu solicitud online aquí</li>
                                <li>Recibirás un código de seguimiento y ticket de pago</li>
                                <li>Acude a la Municipalidad presencialmente</li>
                                <li>Presenta el ticket y realiza el pago</li>
                                <li>Sube el comprobante de pago en el link del ticket</li>
                            </ul>
                        </div>

                        <div class="text-start mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="irPaso(1)">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                            <button type="button" class="btn btn-success btn-sm float-end" onclick="irPaso(3)">
                                Continuar <i class="fas fa-arrow-right ms-1"></i>
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
                        
                        {{-- SELECTOR DE DÍAS --}}
                        <div class="section-title" style="margin-top: 20px;"><i class="fas fa-calendar-days me-2"></i>¿Por cuántos días?</div>
                        <p class="text-muted mb-3">El precio se calcula por día de validez del certificado</p>
                        
                        {{-- OPCIONES RÁPIDAS --}}
                        <div style="margin-bottom: 15px;">
                            <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 10px;">Opciones rápidas:</p>
                            <div class="dias-selector" id="diasSelector">
                                <button type="button" class="dias-btn activo" onclick="seleccionarDias(1)">1 día</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(2)">2 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(3)">3 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(5)">5 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(7)">7 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(10)">10 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(15)">15 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(21)">21 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(30)">30 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(60)">60 días</button>
                                <button type="button" class="dias-btn" onclick="seleccionarDias(90)">90 días</button>
                            </div>
                        </div>

                        {{-- INPUT PERSONALIZADO --}}
                        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 15px; margin: 15px 0;">
                            <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 10px;">O ingresa una cantidad personalizada:</p>
                            <div style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                                <input type="number" id="dias-personalizado" 
                                    class="form-control" style="max-width: 100px; text-align: center;" 
                                    min="1" max="365" value="1" placeholder="1-365"
                                    onchange="seleccionarDiasPersonalizado()"
                                    oninput="seleccionarDiasPersonalizado()">
                                <span style="color: var(--text-muted); font-weight: 600;">días</span>
                            </div>
                            <small class="text-muted d-block mt-2">Mínimo: 1 día | Máximo: 365 días</small>
                        </div>
                        
                        {{-- PRECIO CALCULADO --}}
                        <div class="precio-box" style="display:block; margin: 15px auto; max-width:300px;">
                            <div class="precio-label">Costo del trámite por días</div>
                            <div class="precio-monto" id="precio-evento-monto">S/ 178.90 <span></span></div>
                            <div class="precio-tipo" id="precio-evento-tipo">Evento Público - 1 día</div>
                            <div class="precio-nota">* Válido por el período seleccionado</div>
                        </div>

                        {{-- AVISO DE PAGO PRESENCIAL --}}
                        <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 2px solid #fca5a5; border-left: 6px solid #dc2626; color: #7f1d1d; border-radius: 10px; padding: 1.5rem; font-size: 0.9rem; margin: 1.5rem 0; line-height: 1.6;">
                            <div style="font-size: 1.2rem; margin-bottom: 0.75rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div><strong>⚠️ IMPORTANTE - PAGO PRESENCIAL</strong></div>
                            <p style="margin: 0.75rem 0 0.5rem 0;">
                                El pago del trámite se realiza <strong>presencialmente en nuestras instalaciones</strong>.
                            </p>
                            <ul style="margin: 0.5rem 0; padding-left: 1.5rem;">
                                <li>Completa tu solicitud online aquí</li>
                                <li>Recibirás un código de seguimiento y ticket de pago</li>
                                <li>Acude a la Municipalidad presencialmente</li>
                                <li>Presenta el ticket y realiza el pago</li>
                                <li>Sube el comprobante de pago en el link del ticket</li>
                            </ul>
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
                            {{-- BÚSQUEDA DNI/RUC PRIMERO --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">🔍 Buscar DNI / RUC <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="dni_ruc" id="dni_ruc"
                                        class="form-control @error('dni_ruc') is-invalid @enderror"
                                        value="{{ old('dni_ruc') }}"
                                        maxlength="11" inputmode="numeric"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                        placeholder="8 a 11 dígitos">
                                    <button type="button" class="btn btn-outline-primary" id="btnVerificar" title="Verificar DNI/RUC">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="label-error" id="err_dni">Ingresa tu DNI o RUC</div>
                                <!-- Indicador de búsqueda -->
                                <div id="busqueda-estado" style="display:none; padding:8px; margin-top:8px; border-radius:6px; font-size:12px;">
                                    <div id="busqueda-cargando" style="display:none; color:#0066cc;">
                                        <i class="fas fa-spinner fa-spin"></i> Verificando...
                                    </div>
                                    <div id="busqueda-exito" style="display:none; color:#28a745; background:#f0fff0; border:1px solid #c3e6cb; padding:8px; border-radius:4px;">
                                        <i class="fas fa-check-circle"></i> Datos encontrados
                                    </div>
                                    <div id="busqueda-error" style="display:none; color:#dc3545; background:#fff5f5; border:1px solid #f5c6cb; padding:8px; border-radius:4px;">
                                        <i class="fas fa-exclamation-circle"></i> <span id="error-msg"></span>
                                    </div>
                                </div>
                                @error('dni_ruc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- NOMBRES/RAZÓN SOCIAL SEGUNDO (resultado de búsqueda) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Nombres y Apellidos / Razón Social <span class="text-danger">*</span></label>
                                <input type="text" name="nombres_solicitante" id="nombres_solicitante"
                                    class="form-control @error('nombres_solicitante') is-invalid @enderror"
                                    value="{{ old('nombres_solicitante') }}"
                                    placeholder="Se rellena automáticamente">
                                <div class="label-error" id="err_nombres">Ingresa tu nombre completo</div>
                                @error('nombres_solicitante')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- WHATSAPP TERCERO --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">WhatsApp <i class="fab fa-whatsapp text-success"></i> <span class="text-danger">*</span></label>
                                <input type="text" name="telefono_whatsapp" id="telefono_whatsapp"
                                    class="form-control @error('telefono_whatsapp') is-invalid @enderror"
                                    value="{{ old('telefono_whatsapp') }}"
                                    maxlength="9" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    placeholder="9 dígitos"
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
                                    <input type="text" id="nombre_comercial_neg"
                                        class="form-control" value="{{ old('nombre_comercial') }}">
                                    <div class="label-error" id="err_nombre_comercial">Ingresa el nombre comercial</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Dirección <span class="text-danger">*</span></label>
                                    <input type="text" id="direccion_neg"
                                        class="form-control" value="{{ old('direccion') }}">
                                    <div class="label-error" id="err_direccion_neg">Ingresa la dirección del local</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Provincia <span class="text-danger">*</span></label>
                                    <input type="text" id="provincia_neg" class="form-control" value="{{ old('provincia', 'HUAMANGA') }}">
                                    <div class="label-error" id="err_provincia">Ingresa la provincia</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Departamento <span class="text-danger">*</span></label>
                                    <input type="text" id="departamento_neg" class="form-control" value="{{ old('departamento', 'AYACUCHO') }}">
                                    <div class="label-error" id="err_departamento">Ingresa el departamento</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Área (m2) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="1" max="99999.99"
                                        id="area_neg"
                                        class="form-control" value="{{ old('area_edificacion') }}"
                                        placeholder="Ej: 250.50"
                                        oninput="calcularPrecio()">
                                    <div class="label-error" id="err_area_neg">Ingresa el área en m²</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Giro o Actividad <span class="text-danger">*</span></label>
                                    <input type="text" id="actividad_neg"
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
                                    <input type="text" id="nombre_comercial_ev"
                                        class="form-control" value="{{ old('nombre_comercial') }}">
                                    <div class="label-error" id="err_nombre_lugar">Ingresa el nombre del lugar</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Dirección del Lugar <span class="text-danger">*</span></label>
                                    <input type="text" id="direccion_ev"
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
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>⚠️ CAMPOS REQUERIDOS:</strong> Todos los siguientes documentos son OBLIGATORIOS para procesar tu solicitud.
                        </div>
                        <div class="alert alert-success mb-3" style="border-left: 5px solid #12961d; background: linear-gradient(90deg, rgba(18, 150, 29, 0.1), transparent);">
                            <i class="fas fa-file-alt me-2" style="color: #12961d; font-size: 1.2rem;"></i>
                            <strong style="font-size: 1.05rem;">📋 Completa tu Plantilla FIT ITSE</strong>
                            <p class="mb-2 mt-2">Se abrirá con tus datos prellenados automáticamente.</p>
                            <button type="button" class="btn btn-lg btn-success" onclick="abrirPlantillaITCE()" style="font-weight: 700; font-size: 1rem; padding: 0.75rem 2rem;">
                                <i class="fas fa-file-excel me-2"></i>✨ Llenar Plantilla FIT ITSE ✨
                            </button>
                        </div>
                        <div class="row">
                            <!-- Solicitud / FUT -->
                            <div class="col-lg-6 mb-4">
                                <label class="form-label fw-bold" style="color: #0055cc; font-size: 1rem;"><i class="fas fa-file me-2"></i>1. Solicitud / FUT <span class="text-danger" id="label-req-solicitud">*</span></label>
                                <div class="drag-drop-zone" id="drop-solicitud" data-field="doc_solicitud">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #0055cc; margin-bottom: 0.5rem;"></i>
                                    <p class="fw-bold mb-1">Arrastra tu archivo aquí</p>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">o haz clic para seleccionar</p>
                                    <small class="text-muted">PDF, JPG o PNG (máx 5 MB)</small>
                                </div>
                                <input type="file" name="doc_solicitud" class="d-none file-input" accept=".pdf,.jpg,.png" id="doc_solicitud_input">
                                <div class="file-preview mt-2" id="preview-doc_solicitud"></div>
                            </div>

                            <!-- Plano / Croquis -->
                            <div class="col-lg-6 mb-4">
                                <label class="form-label fw-bold" style="color: #0055cc; font-size: 1rem;"><i class="fas fa-image me-2"></i>2. Plano / Croquis <span class="text-danger" id="label-req-plano">*</span></label>
                                <div class="drag-drop-zone" id="drop-plano" data-field="doc_plano">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #0055cc; margin-bottom: 0.5rem;"></i>
                                    <p class="fw-bold mb-1">Arrastra tu archivo aquí</p>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">o haz clic para seleccionar</p>
                                    <small class="text-muted">PDF, JPG o PNG (máx 5 MB)</small>
                                </div>
                                <input type="file" name="doc_plano" class="d-none file-input" accept=".pdf,.jpg,.png" id="doc_plano_input">
                                <div class="file-preview mt-2" id="preview-doc_plano"></div>
                            </div>

                            <!-- DNI / Pasaporte -->
                            <div class="col-lg-6 mb-4">
                                <label class="form-label fw-bold" style="color: #0055cc; font-size: 1rem;"><i class="fas fa-id-card me-2"></i>3. Copia de DNI / Pasaporte <span class="text-danger" id="label-req-dni">*</span></label>
                                <div class="drag-drop-zone" id="drop-dni" data-field="doc_dni_copia">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #0055cc; margin-bottom: 0.5rem;"></i>
                                    <p class="fw-bold mb-1">Arrastra tu archivo aquí</p>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">o haz clic para seleccionar</p>
                                    <small class="text-muted">PDF, JPG o PNG (máx 5 MB)</small>
                                </div>
                                <input type="file" name="doc_dni_copia" class="d-none file-input" accept=".pdf,.jpg,.png" id="doc_dni_copia_input">
                                <div class="file-preview mt-2" id="preview-doc_dni_copia"></div>
                            </div>

                            <!-- Comprobante de Pago -->
                            <div class="col-lg-6 mb-4">
                                <label class="form-label fw-bold" style="color: #999; font-size: 1rem;"><i class="fas fa-receipt me-2"></i>4. Comprobante de Pago <span class="text-muted" style="font-size: 0.85rem; font-weight: normal;">(Se sube desde el link del ticket)</span></label>
                                <div class="drag-drop-zone" id="drop-pago" data-field="doc_comprobante_pago" style="opacity: 0.6; pointer-events: none; background-color: #f5f5f5; border-color: #ccc;">
                                    <i class="fas fa-lock" style="font-size: 2.5rem; color: #999; margin-bottom: 0.5rem;"></i>
                                    <p class="fw-bold mb-1" style="color: #999;">Este campo está deshabilitado</p>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">Carga tu comprobante desde el link "Subir Comprobante" en tu ticket</p>
                                </div>
                                <input type="file" name="doc_comprobante_pago" class="d-none file-input" accept=".pdf,.jpg,.png" disabled>
                                <div class="file-preview mt-2" id="preview-doc_comprobante_pago"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Otros Documentos (Opcional) -->
                        <div class="col-lg-6 mb-4">
                            <label class="form-label fw-bold" style="color: #999; font-size: 1rem;"><i class="fas fa-file me-2"></i>5. Otros Documentos <small class="text-muted">(Opcional)</small></label>
                            <div class="drag-drop-zone" id="drop-otros" data-field="doc_otros" style="background-color: rgba(255, 193, 7, 0.08); border-color: #ffc107;">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #ffc107; margin-bottom: 0.5rem;"></i>
                                <p class="fw-bold mb-1">Arrastra archivos adicionales</p>
                                <p class="text-muted mb-3" style="font-size: 0.9rem;">o haz clic para seleccionar</p>
                                <small class="text-muted">PDF, JPG o PNG (máx 5 MB)</small>
                            </div>
                            <input type="file" name="doc_otros" class="d-none file-input" accept=".pdf,.jpg,.png">
                            <div class="file-preview mt-2" id="preview-doc_otros"></div>
                        </div>

                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Al enviar recibirás un <strong>código de seguimiento</strong> para consultar el estado de tu trámite.
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="volverDesdePaso4()">
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
let nichoSeleccionado = ''; // ID del nicho/tipo de negocio seleccionado

// ============================================================
// TABLA DE PRECIOS — modifica aquí cuando cambien los precios
// ============================================================
const PRECIOS = {
    // Precios por tipo de negocio (nicho)
    nichos: {
        bodega:               99.80,     // Bodega / Tienda de abarrotes
        farmacia:            133.80,     // Farmacia / Botica
        restaurante_menu:    133.80,     // Restaurante / Cevichería (menú del día)
        restaurante_turistico: 333.80,   // Restaurante turístico / Recreo
        oficina_admin:       133.80,     // Oficina / Consultorio (< 500m²)
        oficina_admin_grande: 333.80,    // Oficina / Consultorio (> 500m²)
        peluqueria:          133.80,     // Peluquería / Salón de belleza
        educativo:           333.80,     // Institución educativa / Centros educativos
        grifo:               546.40,     // Grifo / Estación de combustible
        almacen:             546.40,     // Almacén / Depósito
        hospital:            546.40,     // Hospital / Clínica
        centro_comercial:    546.40,     // Centro comercial / Galería
        discoteca:           546.40,     // Discoteca / Bar / Karaoke
        hotel:               546.40,     // Hotel / Hospedaje
    },
    // Precios por anexo (si no hay nicho específico, usa el cálculo de área)
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
    for (let i = 1; i <= 5; i++) {
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
        // Establecer monto para evento público
        document.getElementById('monto_pago_hidden').value = PRECIOS.evento_publico;
        document.getElementById('paso2evento').classList.add('activo');
        actualizarDots(2);
        actualizarCamposRequeridos(); // Actualizar validación de documentos
    } else {
        document.getElementById('paso2negocio').classList.add('activo');
        actualizarDots(2);
    }
}

function elegirNegocio(nombreNegocio, anexo, riesgo, nicho = '') {
    tipoCertificado = anexo;
    nivelRiesgo     = riesgo;
    nichoSeleccionado = nicho; // Guardar el ID del nicho
    document.getElementById('tipo_certificado_hidden').value = anexo;
    // Pre-llenar el campo actividad con el tipo elegido
    //MAYUSCULAS
document.getElementById('actividad_neg').value = nombreNegocio.toUpperCase();     
    // Establece el monto según el anexo seleccionado
    const monto = tipoCertificado === 'anexo_14' ? PRECIOS.anexo_14 : PRECIOS.anexo_13;
    document.getElementById('monto_pago_hidden').value = monto;
    
    actualizarCamposRequeridos(); // Actualizar validación de documentos
    // Ir automáticamente a paso 3 (datos del negocio)
    irPaso(3);
}

function actualizarCamposRequeridos() {
    const esEvento = tipoCertificado === 'evento_publico';
    const inputs = ['doc_solicitud_input', 'doc_plano_input', 'doc_dni_copia_input'];
    const labels = ['label-req-solicitud', 'label-req-plano', 'label-req-dni'];
    
    inputs.forEach((inputId, index) => {
        const input = document.getElementById(inputId);
        const label = document.getElementById(labels[index]);
        
        if (esEvento) {
            input.removeAttribute('required'); // No requerido para evento
            label.style.display = 'none'; // Ocultar asterisco
        } else {
            input.setAttribute('required', 'required'); // Requerido para negocio
            label.style.display = 'inline'; // Mostrar asterisco
        }
    });
}

function seleccionarDias(dias) {
    // Actualizar campo hidden con número de días
    document.getElementById('dias_evento_hidden').value = dias;
    
    // Limpiar input personalizado
    document.getElementById('dias-personalizado').value = dias;
    
    // Calcular precio base del evento
    const precioBase = PRECIOS.evento_publico;
    const precioTotal = precioBase * dias;
    
    // Actualizar visual en paso 2B
    document.getElementById('precio-evento-monto').textContent = 'S/ ' + precioTotal.toFixed(2);
    const diaLabel = dias === 1 ? 'día' : 'días';
    document.getElementById('precio-evento-tipo').textContent = 'Evento Público - ' + dias + ' ' + diaLabel;
    
    // Guardar en campo hidden de monto
    document.getElementById('monto_pago_hidden').value = precioTotal;
    
    // Actualizar botones de días (marcar activo)
    document.querySelectorAll('.dias-btn').forEach((btn, index) => {
        btn.classList.remove('activo');
    });
    event.target.classList.add('activo');
}

function seleccionarDiasPersonalizado() {
    const diasInput = document.getElementById('dias-personalizado');
    let dias = parseInt(diasInput.value) || 1;
    
    // Validar rango
    if (dias < 1) dias = 1;
    if (dias > 365) dias = 365;
    
    // Actualizar input con valor validado
    diasInput.value = dias;
    
    // Actualizar campo hidden con número de días
    document.getElementById('dias_evento_hidden').value = dias;
    
    // Calcular precio base del evento
    const precioBase = PRECIOS.evento_publico;
    const precioTotal = precioBase * dias;
    
    // Actualizar visual en paso 2B
    document.getElementById('precio-evento-monto').textContent = 'S/ ' + precioTotal.toFixed(2);
    const diaLabel = dias === 1 ? 'día' : 'días';
    document.getElementById('precio-evento-tipo').textContent = 'Evento Público - ' + dias + ' ' + diaLabel;
    
    // Guardar en campo hidden de monto
    document.getElementById('monto_pago_hidden').value = precioTotal;
    
    // Deseleccionar botones rápidos
    document.querySelectorAll('.dias-btn').forEach(btn => {
        btn.classList.remove('activo');
    });
}

function calcularPrecio() {
    const area = parseFloat(document.getElementById('area_neg').value) || 0;
    
    // Si es evento público, establecer monto fijo
    if (tipoCertificado === 'evento_publico') {
        const precioEvento = PRECIOS.evento_publico;
        document.getElementById('monto_pago_hidden').value = precioEvento;
        return;
    }
    
    // Si hay un nicho seleccionado con precio fijo, usarlo
    if (nichoSeleccionado && PRECIOS.nichos[nichoSeleccionado]) {
        const precioNicho = PRECIOS.nichos[nichoSeleccionado];
        document.getElementById('monto_pago_hidden').value = precioNicho;
        
        // Mostrar en pantalla
        document.getElementById('precio-monto').textContent = 'S/ ' + precioNicho.toFixed(2);
        document.getElementById('precio-tipo').textContent = 'Precio fijo por tipo de negocio';
        document.getElementById('precio-box').style.display = 'block';
        return;
    }
    
    if (!tipoCertificado || area <= 0) return;

    const tabla  = PRECIOS[tipoCertificado][nivelRiesgo];
    const precio = area < 100 ? tabla.menorA100 : tabla.mayorA100;
    const rango  = area < 100 ? 'Área menor a 100 m²' : 'Área mayor o igual a 100 m²';

    // Actualizar campo hidden con el monto
    document.getElementById('monto_pago_hidden').value = precio;
    
    // Mostrar en pantalla
    document.getElementById('precio-monto').textContent = 'S/ ' + precio.toFixed(2);
    document.getElementById('precio-tipo').textContent  =
        (tipoCertificado === 'anexo_13' ? 'Anexo 13 — Riesgo Bajo/Medio' : 'Anexo 14 — Riesgo Alto/Muy Alto')
        + ' · ' + rango;
    document.getElementById('precio-box').style.display = 'block';
}

function irPaso(n) {
    ocultarTodos();
    if (n == 1) {
        document.getElementById('paso1').classList.add('activo');
        actualizarDots(1);
    } else if (n == 2) {
        // Paso 2: Para evento, muestra selector de días; para negocio no existe
        if (tipoFlujo === 'evento') {
            document.getElementById('paso2evento').classList.add('activo');
            actualizarDots(2);
        }
    } else if (n == 3) {
        // Paso 3: Datos del solicitante
        document.getElementById('paso3').classList.add('activo');
        actualizarDots(3);
        
        // Actualizar descripción del tipo seleccionado
        const textoTipo = document.getElementById('texto-tipo');
        if (tipoCertificado === 'evento_publico') {
            textoTipo.innerHTML = '📅 <strong>Evento Público</strong> — Certificado de Inspección Técnica Previa a Evento';
            document.getElementById('datos-negocio').style.display = 'none';
            document.getElementById('datos-evento').style.display  = 'block';
        } else {
            const label = tipoCertificado === 'anexo_13'
                ? '🏪 <strong>Riesgo Bajo/Medio (Anexo 13)</strong> — Local menor a 500 m²'
                : '🏢 <strong>Riesgo Alto/Muy Alto (Anexo 14)</strong> — Local mayor a 500 m²';
            textoTipo.innerHTML = label;
            document.getElementById('datos-negocio').style.display = 'block';
            document.getElementById('datos-evento').style.display  = 'none';
            // Recalcular precio si ya hay área
            calcularPrecio();
        }
        limpiarErrores();
    } else if (n == 4) {
        // Paso 4: Documentos adjuntos
        document.getElementById('paso4').classList.add('activo');
        actualizarDots(4);
    }
}

function volverPaso2() {
    ocultarTodos();
    if (tipoFlujo === 'evento') {
        // Para evento, volver a paso2evento (selector de días)
        document.getElementById('paso2evento').classList.add('activo');
        actualizarDots(2);
    } else {
        // Para negocio, volver a paso2negocio
        document.getElementById('paso2negocio').classList.add('activo');
        actualizarDots(2);
    }
}

function volverDesdePaso4() {
    // Desde paso 4 (documentos), volver a paso 3 (datos)
    ocultarTodos();
    document.getElementById('paso3').classList.add('activo');
    actualizarDots(3);
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

    // Ir directamente a paso 4 (documentos adjuntos) - pago presencial no necesita pasos intermedios
    irPaso(4);
}

// ===== BÚSQUEDA DNI/RUC =====
let nombreYaRelleno = false; // Flag para evitar doble relleno

// ===== AUTO-BÚSQUEDA BUSCAR AL COMPLETAR DNI/RUC =====
document.getElementById('dni_ruc').addEventListener('input', function() {
    const valor = this.value.trim();
    // Disparar búsqueda automática cuando tenga 8 (DNI) u 11 (RUC) dígitos
    if (valor.length === 8 || valor.length === 11) {
        document.getElementById('btnVerificar').click();
    }
});

document.getElementById('btnVerificar').addEventListener('click', async function() {
    const dniRuc = document.getElementById('dni_ruc').value.trim();
    
    if (!dniRuc) {
        mostrarErrorBusqueda('Por favor ingresa un DNI o RUC');
        return;
    }
    
    if (dniRuc.length < 8 || dniRuc.length > 11) {
        mostrarErrorBusqueda('DNI debe tener 8 dígitos, RUC debe tener 11');
        return;
    }
    
    // Limpiar el flag cuando se inicia una nueva búsqueda
    nombreYaRelleno = false;
    
    // Verificar si es DNI (8 dígitos) o RUC (11 dígitos)
    const esDNI = dniRuc.length === 8;
    const tipoConsulta = esDNI ? 'DNI' : 'RUC';
    
    mostrarCargando();
    
    try {
        const endpoint = esDNI ? '/api/consultar-dni' : '/api/consultar-ruc';
        const campoNombre = esDNI ? 'dni' : 'ruc';
        
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ [campoNombre]: dniRuc })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Solo rellenar si aún no lo hemos hecho
            if (!nombreYaRelleno) {
                // DNI encontrado
                if (esDNI) {
                    // Concatenar todos los campos de nombre
                    let textoCompleto = [
                        data.nombres,
                        data.apellido_paterno,
                        data.apellido_materno
                    ].filter(n => n).join(' ');
                    
                    // Limpiar caracteres especiales y espacios múltiples
                    textoCompleto = textoCompleto
                        .replace(/[,\.;]/g, ' ') // Reemplazar comas, puntos, etc. por espacios
                        .replace(/\s+/g, ' ') // Convertir múltiples espacios en uno
                        .trim();
                    
                    // Dividir en palabras y eliminar duplicados (manteniendo primer aparición)
                    const palabras = textoCompleto.split(' ');
                    const palabrasUnicas = [];
                    const vistos = new Set();
                    
                    for (const palabra of palabras) {
                        const palabraLower = palabra.toLowerCase();
                        if (!vistos.has(palabraLower)) {
                            vistos.add(palabraLower);
                            palabrasUnicas.push(palabra);
                        }
                    }
                    
                    const nombreLimpio = palabrasUnicas.join(' ');
                    
                    document.getElementById('nombres_solicitante').value = nombreLimpio || data.nombres;
                } else {
                    // RUC encontrado
                    document.getElementById('nombres_solicitante').value = data.nombres || '';
                    // Rellenar datos del negocio si existen
                    if (data.direccion) {
                        document.getElementById('direccion_neg').value = data.direccion;
                    }
                    if (data.departamento) {
                        document.getElementById('departamento_neg').value = data.departamento;
                    }
                    if (data.provincia) {
                        document.getElementById('provincia_neg').value = data.provincia;
                    }
                }
                
                // Marcar como rellenado
                nombreYaRelleno = true;
            }
            
            // Marcar como completado y mostrar éxito
            document.getElementById('dni_ruc').style.borderColor = '#28a745';
            document.getElementById('dni_ruc').style.backgroundColor = '#f0fff0';
            mostrarExitoBusqueda();
            
        } else {
            mostrarErrorBusqueda(data.message || `${tipoConsulta} no encontrado`);
        }
    } catch (error) {
        console.error('Error en búsqueda:', error);
        mostrarErrorBusqueda('Error al conectar. Intenta más tarde.');
    }
});

function mostrarCargando() {
    document.getElementById('busqueda-estado').style.display = 'block';
    document.getElementById('busqueda-cargando').style.display = 'block';
    document.getElementById('busqueda-exito').style.display = 'none';
    document.getElementById('busqueda-error').style.display = 'none';
}

function mostrarExitoBusqueda() {
    document.getElementById('busqueda-cargando').style.display = 'none';
    document.getElementById('busqueda-exito').style.display = 'block';
    document.getElementById('busqueda-error').style.display = 'none';
}

function mostrarErrorBusqueda(mensaje) {
    document.getElementById('busqueda-cargando').style.display = 'none';
    document.getElementById('busqueda-exito').style.display = 'none';
    document.getElementById('busqueda-error').style.display = 'block';
    document.getElementById('error-msg').textContent = mensaje;
    document.getElementById('dni_ruc').style.borderColor = '#dc3545';
    document.getElementById('dni_ruc').style.backgroundColor = '#fff5f5';
}

// ===== ABRIR PLANTILLA ITCE PRELLENADA =====
function abrirPlantillaITCE() {
    // Capturar datos del formulario
    const datos = {
        nombres_solicitante: document.getElementById('nombres_solicitante').value,
        dni_ruc: document.getElementById('dni_ruc').value,
        telefono_whatsapp: document.getElementById('telefono_whatsapp').value,
        email: document.querySelector('input[name="email"]')?.value || '',
        representante: document.querySelector('input[name="representante"]')?.value || '',
        solicitud: 'Certificado de Inspección Técnica de Seguridad e ITSE'
    };

    // Según tipo de certificado
    if (tipoCertificado === 'evento_publico') {
        datos.nombre_evento = document.getElementById('nombre_evento').value;
        datos.fecha_evento = document.getElementById('fecha_evento').value;
        datos.nombre_comercial = document.getElementById('nombre_comercial_ev').value;
        datos.direccion = document.getElementById('direccion_ev').value;
        datos.solicitud = 'Certificado de Inspección Técnica Previa para Evento/Espectáculo Público';
        
        datos.detalle = `Evento: ${datos.nombre_comercial || ''} - Fecha: ${datos.fecha_evento || ''} - Ubicación: ${datos.direccion || ''}`;
    } else {
        datos.nombre_comercial = document.getElementById('nombre_comercial_neg').value;
        datos.direccion = document.getElementById('direccion_neg').value;
        datos.actividad = document.getElementById('actividad_neg').value;
        datos.area_edificacion = document.getElementById('area_neg').value;
        datos.solicitud = 'Certificado de Inspección Técnica de Seguridad de Local/Negocio';
        
        datos.detalle = `Local: ${datos.nombre_comercial || ''} - Actividad: ${datos.actividad || ''} - Área: ${datos.area_edificacion || ''} m²`;
    }

    // Construir URL con parámetros codificados
    let url = '{{ asset("plantillas/fut_municipalidad.html") }}?';
    const params = new URLSearchParams(datos);
    url += params.toString();

    // Abrir en nueva pestaña
    window.open(url, '_blank');
}

// ===== COPIA DE DATOS ANTES DE ENVIAR =====
/**
 * Esta función se ejecuta when onsubmit del formulario
 * Copia valores desde los inputs visibles a los inputs ocultos globales
 * para que se envíen correctamente al servidor
 */
function copiarDatosAntesDePHP() {
    console.log('copiarDatosAntesDePHP() iniciado. tipoCertificado:', tipoCertificado);
    
    // ===== VALIDAR ARCHIVOS REQUERIDOS =====
    const doc_solicitud = document.getElementById('doc_solicitud_input');
    const doc_plano = document.getElementById('doc_plano_input');
    const doc_dni_copia = document.getElementById('doc_dni_copia_input');
    
    const archivosRequeridos = [];
    if (!doc_solicitud.files || doc_solicitud.files.length === 0) {
        archivosRequeridos.push('❌ Solicitud / FUT');
    }
    if (!doc_plano.files || doc_plano.files.length === 0) {
        archivosRequeridos.push('❌ Plano / Croquis');
    }
    if (!doc_dni_copia.files || doc_dni_copia.files.length === 0) {
        archivosRequeridos.push('❌ Copia de DNI / Pasaporte');
    }
    
    if (archivosRequeridos.length > 0) {
        alert('⚠️ DOCUMENTOS FALTANTES\n\nPor favor adjunta los siguientes documentos para continuar:\n\n' + archivosRequeridos.join('\n'));
        return false; // Prevenir envío del formulario
    }
    
    // ===== COPIAR TIPO DE CERTIFICADO =====
    document.getElementById('tipo_certificado_hidden').value = tipoCertificado;
    console.log('tipo_certificado_hidden set to:', tipoCertificado);
    
    // ===== COPIAR DATOS SEGÚN TIPO DE CERTIFICADO =====
    if (tipoCertificado !== 'evento_publico') {
        // Para negocio: copiar datos desde inputs visibles a hidden inputs globales
        const nombreComercialValue = document.getElementById('nombre_comercial_neg').value;
        const direccionValue = document.getElementById('direccion_neg').value;
        const provinciaValue = document.getElementById('provincia_neg').value;
        const departamentoValue = document.getElementById('departamento_neg').value;
        const areaValue = document.getElementById('area_neg').value;
        const actividadValue = document.getElementById('actividad_neg').value;
        
        document.getElementById('nombre_comercial_global').value = nombreComercialValue;
        document.getElementById('direccion_global').value = direccionValue;
        document.getElementById('provincia_global').value = provinciaValue;
        document.getElementById('departamento_global').value = departamentoValue;
        document.getElementById('area_edificacion_global').value = areaValue;
        document.getElementById('actividad_global').value = actividadValue;
        
        console.log('Negocio - Datos copiados:', {
            nombre_comercial: nombreComercialValue,
            direccion: direccionValue,
            provincia: provinciaValue,
            departamento: departamentoValue,
            area: areaValue,
            actividad: actividadValue
        });
    } else {
        // Para evento: copiar nombre_comercial desde evento field
        const nombreLugarValue = document.getElementById('nombre_comercial_ev').value;
        const direccionEvValue = document.getElementById('direccion_ev').value;
        
        document.getElementById('nombre_comercial_global').value = nombreLugarValue;
        document.getElementById('direccion_global').value = direccionEvValue;
        
        console.log('Evento - Datos copiados:', {
            nombre_lugar: nombreLugarValue,
            direccion: direccionEvValue
        });
    }
    
    console.log('Formulario listo para enviar');
    // El formulario continúa con su envío normal
    return true;
}

// ===== DRAG & DROP FILE UPLOAD =====
document.addEventListener('DOMContentLoaded', function() {
    const dragDropZones = document.querySelectorAll('.drag-drop-zone');
    const fileInputs = document.querySelectorAll('.file-input');

    // Configurar cada zona de drag-drop
    dragDropZones.forEach(zone => {
        const fieldName = zone.getAttribute('data-field');
        const fileInput = document.querySelector(`input[name="${fieldName}"]`);

        // Click para abrir selector de archivo
        zone.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.click();
        });

        // Prevenir comportamiento por defecto
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            zone.style.backgroundColor = 'rgba(0, 85, 204, 0.15)';
            zone.style.borderColor = '#0055cc';
            zone.style.borderWidth = '2px';
        });

        zone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            resetZoneStyle(zone, fieldName);
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            resetZoneStyle(zone, fieldName);
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0], fileInput, fieldName, zone);
            }
        });
    });

    // Cambio de archivo mediante input
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fieldName = this.getAttribute('name');
                const zone = document.querySelector(`.drag-drop-zone[data-field="${fieldName}"]`);
                handleFileSelect(this.files[0], this, fieldName, zone);
            }
        });
    });

    function resetZoneStyle(zone, fieldName) {
        if (fieldName === 'doc_otros') {
            zone.style.backgroundColor = 'rgba(255, 193, 7, 0.08)';
            zone.style.borderColor = '#ffc107';
        } else {
            zone.style.backgroundColor = 'rgba(0, 85, 204, 0.08)';
            zone.style.borderColor = '#0055cc';
        }
        zone.style.borderWidth = '1px';
    }

    function handleFileSelect(file, input, fieldName, zone) {
        // Validar tipo de archivo
        const extensionesPermitidas = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!extensionesPermitidas.includes(file.type)) {
            alert('❌ Tipo de archivo no permitido. Solo PDF, JPG o PNG.');
            return;
        }

        // Validar tamaño (5 MB máximo)
        const maxSize = 5 * 1024 * 1024; // 5 MB
        if (file.size > maxSize) {
            alert('❌ El archivo es demasiado grande. Máximo 5 MB.');
            return;
        }

        // Crear DataTransfer para simular un cambio de archivo real
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        input.files = dataTransfer.files;

        // Mostrar preview del archivo
        updateFilePreview(file, fieldName);

        // Visual feedback
        zone.style.backgroundColor = 'rgba(18, 150, 29, 0.1)';
        zone.style.borderColor = '#12961d';
        resetZoneStyle(zone, fieldName);
    }

    function updateFilePreview(file, fieldName) {
        const previewDiv = document.querySelector(`#preview-${fieldName}`);
        if (!previewDiv) return;

        const fileName = file.name;
        const fileSize = (file.size / 1024).toFixed(2); // KB
        
        previewDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px; background: rgba(18, 150, 29, 0.1); padding: 10px 12px; border-radius: 6px; border-left: 4px solid #12961d;">
                <i class="fas fa-check-circle" style="color: #12961d; font-size: 1.2rem;"></i>
                <div style="flex: 1;">
                    <p style="margin: 0; font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        ${fileName}
                    </p>
                    <small style="color: #666;">
                        ${fileSize} KB
                    </small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarArchivo('${fieldName}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }
});

// ===== AUTO-MAYÚSCULAS mayusculas EN CAMPOS DE TEXTO =====
const camposMayusculas = [
    'nombre_evento',
    'fecha_evento',
    'nombre_comercial_ev',
    'direccion_ev',
    'organizador_nombre',
    'nombres_solicitante',
    'nombre_comercial_neg',
    'direccion_neg',
    'provincia_neg',
    'departamento_neg',
    'actividad_neg'
];

camposMayusculas.forEach(id => {
    const campo = document.getElementById(id);
    if (campo) {
        campo.addEventListener('input', function() {
            const pos = this.selectionStart; // Guardar posición del cursor
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos); // Restaurar posición del cursor
        });
    }
});


function limpiarArchivo(fieldName) {
    const fileInput = document.querySelector(`input[name="${fieldName}"]`);
    const previewDiv = document.querySelector(`#preview-${fieldName}`);
    const zone = document.querySelector(`.drag-drop-zone[data-field="${fieldName}"]`);
    
    // Limpiar el input
    fileInput.value = '';
    
    // Limpiar preview
    if (previewDiv) {
        previewDiv.innerHTML = '';
    }
    
    // Resetear estilos de zona
    if (fieldName === 'doc_otros') {
        zone.style.backgroundColor = 'rgba(255, 193, 7, 0.08)';
        zone.style.borderColor = '#ffc107';
    } else {
        zone.style.backgroundColor = 'rgba(0, 85, 204, 0.08)';
        zone.style.borderColor = '#0055cc';
    }
    zone.style.borderWidth = '1px';
}

</script>
</body>
</html>
<?php

namespace Database\Seeders;

use App\Models\Contribuyente;
use App\Models\ActividadEconomica;
use Illuminate\Database\Seeder;

class DataTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear actividades económicas
        $actividades = [
            [
                'codigo' => '4511',
                'descripcion' => 'Venta al por mayor de vehículos automotores',
                'categoria' => 'Comercio',
                'tasa_derecho' => 250.00,
            ],
            [
                'codigo' => '4520',
                'descripcion' => 'Mantenimiento y reparación de vehículos automotores',
                'categoria' => 'Servicios',
                'tasa_derecho' => 180.00,
            ],
            [
                'codigo' => '4711',
                'descripcion' => 'Venta al por menor en establecimientos no especializados',
                'categoria' => 'Comercio',
                'tasa_derecho' => 200.00,
            ],
            [
                'codigo' => '5610',
                'descripcion' => 'Expendios de comida',
                'categoria' => 'Alimentos y Bebidas',
                'tasa_derecho' => 150.00,
            ],
            [
                'codigo' => '5621',
                'descripcion' => 'Servicio de cantinas',
                'categoria' => 'Alimentos y Bebidas',
                'tasa_derecho' => 180.00,
            ],
            [
                'codigo' => '6820',
                'descripcion' => 'Alquiler de bienes inmuebles por cuenta propia',
                'categoria' => 'Servicios Inmobiliarios',
                'tasa_derecho' => 220.00,
            ],
            [
                'codigo' => '7210',
                'descripcion' => 'Investigación y desarrollo experimental',
                'categoria' => 'Servicios Profesionales',
                'tasa_derecho' => 300.00,
            ],
            [
                'codigo' => '8211',
                'descripcion' => 'Servicios administrativos de oficina',
                'categoria' => 'Servicios Administrativos',
                'tasa_derecho' => 120.00,
            ],
        ];

        foreach ($actividades as $actividad) {
            ActividadEconomica::firstOrCreate(
                ['codigo' => $actividad['codigo']],
                $actividad
            );
        }

        // Crear contribuyentes de prueba
        $contribuyentes = [
            [
                'dni_ruc' => '12345678',
                'tipo_persona' => 'natural',
                'nombres_razon_social' => 'Juan Pérez García',
                'direccion' => 'Calle Principal 123, Lima',
                'telefono' => '987654321',
                'email' => 'juan.perez@example.com',
            ],
            [
                'dni_ruc' => '87654321',
                'tipo_persona' => 'natural',
                'nombres_razon_social' => 'María López Rodríguez',
                'direccion' => 'Avenida Comercio 456, Lima',
                'telefono' => '987123456',
                'email' => 'maria.lopez@example.com',
            ],
            [
                'dni_ruc' => '10123456789',
                'tipo_persona' => 'juridica',
                'nombres_razon_social' => 'Comercial El Éxito S.A.C.',
                'direccion' => 'Calle Secundaria 789, Lima',
                'telefono' => '0151234567',
                'email' => 'info@exitocomercial.pe',
            ],
            [
                'dni_ruc' => '20987654321',
                'tipo_persona' => 'juridica',
                'nombres_razon_social' => 'Servicios Profesionales Integrados E.I.R.L.',
                'direccion' => 'Avenida Industrial 321, Lima',
                'telefono' => '01654321098',
                'email' => 'contacto@servprofint.pe',
            ],
            [
                'dni_ruc' => '12888777',
                'tipo_persona' => 'natural',
                'nombres_razon_social' => 'Carlos Mendoza Flores',
                'direccion' => 'Calle Tercera 555, Lima',
                'telefono' => '987555666',
                'email' => 'carlos.mendoza@example.com',
            ],
            [
                'dni_ruc' => '15999888',
                'tipo_persona' => 'natural',
                'nombres_razon_social' => 'Rosa Martínez Vargas',
                'direccion' => 'Pasaje Cuarta 999, Lima',
                'telefono' => '987888999',
                'email' => 'rosa.martinez@example.com',
            ],
        ];

        foreach ($contribuyentes as $contribuyente) {
            Contribuyente::firstOrCreate(
                ['dni_ruc' => $contribuyente['dni_ruc']],
                $contribuyente
            );
        }

        $this->command->info('✓ Contribuyentes creados exitosamente');
        $this->command->info('✓ Actividades económicas creadas exitosamente');
    }
}

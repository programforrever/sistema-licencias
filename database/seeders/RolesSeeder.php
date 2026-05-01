<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $admin     = Role::firstOrCreate(['name' => 'admin']);
        $evaluador = Role::firstOrCreate(['name' => 'evaluador']);
        $ingeniero = Role::firstOrCreate(['name' => 'ingeniero']);

        // Crear permisos
        Permission::firstOrCreate(['name' => 'ver licencias']);
        Permission::firstOrCreate(['name' => 'crear licencias']);
        Permission::firstOrCreate(['name' => 'editar licencias']);
        Permission::firstOrCreate(['name' => 'eliminar licencias']);
        Permission::firstOrCreate(['name' => 'aprobar licencias']);
        Permission::firstOrCreate(['name' => 'rechazar licencias']);

        // Asignar permisos
        $admin->givePermissionTo(Permission::all());

        $evaluador->givePermissionTo([
            'ver licencias',
            'crear licencias',
            'editar licencias',
            'aprobar licencias',
            'rechazar licencias',
        ]);

        $ingeniero->givePermissionTo([
            'ver licencias',
            'crear licencias',
            'editar licencias',
            'aprobar licencias',
            'rechazar licencias',
        ]);

        // Crear usuario administrador
        $user = User::firstOrCreate(
            ['email' => 'admin@municipalidad.gob.pe'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('admin123'),
            ]
        );

        $user->assignRole('admin');
    }
}
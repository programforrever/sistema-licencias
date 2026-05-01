<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $usuario->id) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $usuario->id) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $request->validate([
            'name'  => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role'  => 'required|exists:roles,name',
        ]);

        $usuario->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'min:6|confirmed']);
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        if (auth()->user()->hasRole('admin')) {
            $usuario->syncRoles($request->role);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    public function resetPassword(User $usuario)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        return view('usuarios.reset_password', compact('usuario'));
    }

    public function updatePassword(Request $request, User $usuario)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Contraseña de ' . $usuario->name . ' actualizada correctamente.');
    }
}
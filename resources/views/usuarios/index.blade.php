@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Gestión de Usuarios</h2>
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Usuario
        </a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        {{ $usuario->name }}
                        @if($usuario->id === auth()->id())
                            <span class="badge bg-secondary ms-1">Tú</span>
                        @endif
                    </td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        @foreach($usuario->roles as $rol)
                            <span class="badge bg-primary">{{ $rol->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td>
                        {{-- Editar: admin puede editar a todos, otros solo a sí mismos --}}
                        @if(auth()->user()->hasRole('admin') || $usuario->id === auth()->id())
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif

                        {{-- Resetear contraseña: solo admin --}}
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('usuarios.reset-password', $usuario) }}" class="btn btn-sm btn-warning" title="Resetear contraseña">
                                <i class="fas fa-key"></i>
                            </a>
                        @endif

                        {{-- Eliminar: solo admin y no puede eliminarse a sí mismo --}}
                        @if(auth()->user()->hasRole('admin') && $usuario->id !== auth()->id())
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('¿Eliminar usuario {{ $usuario->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $usuarios->links() }}
    </div>
</div>
@endsection
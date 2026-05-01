@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-briefcase me-2"></i>Actividades Económicas</h2>
    <a href="{{ route('actividades.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva Actividad
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Tasa Derecho</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actividades as $actividad)
                <tr>
                    <td><strong>{{ $actividad->codigo }}</strong></td>
                    <td>{{ $actividad->descripcion }}</td>
                    <td>{{ $actividad->categoria ?? '-' }}</td>
                    <td>S/ {{ number_format($actividad->tasa_derecho, 2) }}</td>
                    <td>
                        <a href="{{ route('actividades.edit', $actividad) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('actividades.destroy', $actividad) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar actividad?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay actividades registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $actividades->links() }}
    </div>
</div>
@endsection
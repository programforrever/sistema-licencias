@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Titulares</h2>
    <a href="{{ route('contribuyentes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo Titular
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>DNI/RUC</th>
                    <th>Tipo</th>
                    <th>Nombres / Razón Social</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contribuyentes as $contribuyente)
                <tr>
                    <td>{{ $contribuyente->dni_ruc }}</td>
                    <td>
                        <span class="badge bg-{{ $contribuyente->tipo_persona == 'natural' ? 'info' : 'warning' }}">
                            {{ strtoupper($contribuyente->tipo_persona) }}
                        </span>
                    </td>
                    <td>{{ $contribuyente->nombres_razon_social }}</td>
                    <td>{{ $contribuyente->telefono ?? '-' }}</td>
                    <td>{{ $contribuyente->email ?? '-' }}</td>
                    <td>
                        <a href="{{ route('contribuyentes.edit', $contribuyente) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('contribuyentes.destroy', $contribuyente) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar contribuyente?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay Titulares registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $contribuyentes->links() }}
    </div>
</div>
@endsection
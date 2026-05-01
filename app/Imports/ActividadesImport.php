<?php

namespace App\Imports;

use App\Models\ActividadEconomica;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ActividadesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new ActividadEconomica([
            'codigo'       => $row['codigo'],
            'descripcion'  => $row['descripcion'],
            'categoria'    => $row['categoria'] ?? null,
            'tasa_derecho' => $row['tasa_derecho'] ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'codigo'      => 'required|unique:actividades_economicas,codigo',
            'descripcion' => 'required',
        ];
    }
}
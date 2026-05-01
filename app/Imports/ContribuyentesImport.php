<?php

namespace App\Imports;

use App\Models\Contribuyente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContribuyentesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Contribuyente([
            'dni_ruc'              => $row['dni_ruc'],
            'tipo_persona'         => $row['tipo_persona'],
            'nombres_razon_social' => $row['nombres_razon_social'],
            'direccion'            => $row['direccion'],
            'telefono'             => $row['telefono'] ?? null,
            'email'                => $row['email'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'dni_ruc'              => 'required|unique:contribuyentes,dni_ruc',
            'tipo_persona'         => 'required|in:natural,juridica',
            'nombres_razon_social' => 'required',
            'direccion'            => 'required',
        ];
    }
}
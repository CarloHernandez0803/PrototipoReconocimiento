<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsuarioExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data['detalle_usuarios'];
    }

    public function headings(): array
    {
        return ['Nombre', 'Rol', 'Actividades', 'Tiempo Promedio'];
    }
}
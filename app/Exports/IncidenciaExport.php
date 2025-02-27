<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncidenciaExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data['detalle_incidencias']->map(function ($incidencia) {
            return [
                $incidencia->tipo_experiencia,
                $incidencia->total,
                $incidencia->tiempo_promedio,
                $incidencia->estado_resolucion,
                $incidencia->reportado_por,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['Tipo', 'Total', 'Tiempo Promedio', 'Estado', 'Reportado Por'];
    }
}
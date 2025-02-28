<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExperienciaExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $datasets = [];
        foreach ($this->data['labels'] as $index => $label) {
            $datasets[] = [
                $label,
                $this->data['datasets'][0]['data'][$index],
                $this->data['datasets'][1]['data'][$index],
                $this->data['datasets'][2]['data'][$index],
            ];
        }
        return $datasets;
    }

    public function headings(): array
    {
        return ['Tipo', 'Alto', 'Medio', 'Bajo'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
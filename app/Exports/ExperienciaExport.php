<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExperienciaExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $datasets = [];
        foreach ($this->data['datasets'] as $dataset) {
            foreach ($dataset['data'] as $index => $value) {
                $datasets[] = [
                    $dataset['label'],
                    $this->data['labels'][$index],
                    $value,
                ];
            }
        }
        return $datasets;
    }

    public function headings(): array
    {
        return ['Tipo de Experiencia', 'Impacto', 'Total'];
    }
}
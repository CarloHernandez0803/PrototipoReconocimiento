<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experiencia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ReporteExperienciasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Experiencia::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_experiencia', [$startDate, $endDate]);
        }

        $experiencias = $query->selectRaw('tipo_experiencia, impacto, COUNT(*) as total')
            ->groupBy('tipo_experiencia', 'impacto')
            ->get();

        $labels = ['Positivo', 'Negativo', 'Neutro'];
        $datasets = [
            'alto' => [0, 0, 0],
            'medio' => [0, 0, 0],
            'bajo' => [0, 0, 0],
        ];

        foreach ($experiencias as $experiencia) {
            $tipo = strtolower($experiencia->tipo_experiencia);
            $impacto = strtolower($experiencia->impacto);

            if ($tipo === 'positiva') {
                $index = 0;
            } elseif ($tipo === 'negativa') {
                $index = 1;
            } else {
                $index = 2;
            }

            if ($impacto === 'alto') {
                $datasets['alto'][$index] += $experiencia->total;
            } elseif ($impacto === 'medio') {
                $datasets['medio'][$index] += $experiencia->total;
            } else {
                $datasets['bajo'][$index] += $experiencia->total;
            }
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Alto',
                    'data' => $datasets['alto'],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Medio',
                    'data' => $datasets['medio'],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Bajo',
                    'data' => $datasets['bajo'],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        return response()->json($chartData);
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->index($request)->getData();
        $dataArray = json_decode(json_encode($data), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tipo');
        $sheet->setCellValue('B1', 'Alto');
        $sheet->setCellValue('C1', 'Medio');
        $sheet->setCellValue('D1', 'Bajo');

        $row = 2;
        foreach ($dataArray['labels'] as $index => $label) {
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $dataArray['datasets'][0]['data'][$index]);
            $sheet->setCellValue('C' . $row, $dataArray['datasets'][1]['data'][$index]);
            $sheet->setCellValue('D' . $row, $dataArray['datasets'][2]['data'][$index]);
            $row++;
        }

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), 
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), 
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), 
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . ($row - 1), null, count($dataArray['labels'])),
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$' . ($row - 1), null, count($dataArray['labels'])),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$' . ($row - 1), null, count($dataArray['labels'])),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$' . ($row - 1), null, count($dataArray['labels'])),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, 
            DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Gráfica de Experiencias');
        $chart = new Chart(
            'chart1', 
            $title,
            $legend,
            $plotArea,
            true,
            0,
            null,
            null
        );

        $chart->setTopLeftPosition('F2');
        $chart->setBottomRightPosition('M20');
        $sheet->addChart($chart);

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $fileName = 'reporte_experiencias.xlsx';
        $writer->save($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
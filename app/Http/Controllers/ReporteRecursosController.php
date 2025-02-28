<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SenEntrenamiento;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ReporteRecursosController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = SenEntrenamiento::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_creacion', [$startDate, $endDate]);
        }

        $recursos = $query->selectRaw('categoria, DATE(fecha_creacion) as fecha, COUNT(*) as total')
            ->groupBy('categoria', 'fecha')
            ->get();

        $labels = $recursos->pluck('fecha');
        $datasets = [
            [
                'label' => 'Recursos',
                'data' => $recursos->pluck('total'),
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->index($request)->getData();
        $dataArray = json_decode(json_encode($data), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Fecha');
        $sheet->setCellValue('B1', 'Total');

        $row = 2;
        foreach ($dataArray['labels'] as $index => $label) {
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $dataArray['datasets'][0]['data'][$index]);
            $row++;
        }

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // Total
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . ($row - 1), null, count($dataArray['labels'])),
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$' . ($row - 1), null, count($dataArray['labels'])),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, 
            DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Gráfica de Recursos');
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

        $chart->setTopLeftPosition('D2');
        $chart->setBottomRightPosition('M20');
        $sheet->addChart($chart);

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true); 
        $fileName = 'reporte_recursos.xlsx';
        $writer->save($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
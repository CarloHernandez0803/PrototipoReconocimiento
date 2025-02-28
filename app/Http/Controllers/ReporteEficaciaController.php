<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ReporteEficaciaController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Evaluacion::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_evaluacion', [$startDate, $endDate]);
        }

        $eficacia = $query->selectRaw('categoria_senal, AVG(calificacion_media) as promedio, SUM(senales_correctas) as correctas, SUM(senales_totales) as totales')
            ->groupBy('categoria_senal')
            ->get();

        $labels = $eficacia->pluck('categoria_senal');
        $datasets = [
            [
                'label' => 'Señales Correctas',
                'data' => $eficacia->pluck('correctas'),
            ],
            [
                'label' => 'Señales Totales',
                'data' => $eficacia->pluck('totales'),
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

        $sheet->setCellValue('A1', 'Categoría');
        $sheet->setCellValue('B1', 'Señales Correctas');
        $sheet->setCellValue('C1', 'Señales Totales');

        $row = 2;
        foreach ($dataArray['labels'] as $index => $label) {
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $dataArray['datasets'][0]['data'][$index]);
            $sheet->setCellValue('C' . $row, $dataArray['datasets'][1]['data'][$index]);
            $row++;
        }

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), 
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . ($row - 1), null, count($dataArray['labels'])),
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$' . ($row - 1), null, count($dataArray['labels'])),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$' . ($row - 1), null, count($dataArray['labels'])),
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
        $title = new Title('Gráfica de Eficacia');
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

        $chart->setTopLeftPosition('E2');
        $chart->setBottomRightPosition('M20');
        $sheet->addChart($chart);

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $fileName = 'reporte_eficacia.xlsx';
        $writer->save($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
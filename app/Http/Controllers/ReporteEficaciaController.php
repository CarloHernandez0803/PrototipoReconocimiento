<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteEficaciaController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Evaluacion::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_evaluacion', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $eficacia = $query->selectRaw('categoria_senal, AVG(calificacion_media) as promedio, SUM(senales_correctas) as correctas, SUM(senales_totales) as totales')
            ->groupBy('categoria_senal')
            ->get();

        // Calcular porcentaje de eficacia
        $eficacia = $eficacia->map(function ($item) {
            $item->porcentaje_eficacia = $item->totales > 0 
                ? round(($item->correctas / $item->totales) * 100, 2)
                : 0;
            return $item;
        });

        $labels = $eficacia->pluck('categoria_senal');
        $datasets = [
            [
                'label' => 'Porcentaje de Eficacia',
                'data' => $eficacia->pluck('porcentaje_eficacia'),
                'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            ],
            [
                'label' => 'Señales Correctas',
                'data' => $eficacia->pluck('correctas'),
                'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1
            ],
            [
                'label' => 'Señales Totales',
                'data' => $eficacia->pluck('totales'),
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1
            ]
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
            'rango_fechas' => $startDate && $endDate 
                ? Carbon::parse($startDate)->format('d/m/Y').' - '.Carbon::parse($endDate)->format('d/m/Y')
                : 'Todos los registros'
        ]);
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->index($request)->getData();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Establecer propiedades del documento
        $spreadsheet->getProperties()
            ->setCreator("Sistema de Reportes")
            ->setTitle("Reporte de Eficacia")
            ->setDescription("Reporte de eficacia del modelo de reconocimiento");
        
        // Encabezado del reporte
        $sheet->setCellValue('A1', 'Reporte de Eficacia del Modelo de Reconocimiento');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Rango de fechas
        $sheet->setCellValue('A2', 'Período:');
        $sheet->setCellValue('B2', $data->rango_fechas);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        
        // Encabezados de tabla
        $sheet->setCellValue('A4', 'Categoría de Señal');
        $sheet->setCellValue('B4', 'Señales Correctas');
        $sheet->setCellValue('C4', 'Señales Totales');
        $sheet->setCellValue('D4', 'Porcentaje de Eficacia');
        
        // Estilo para encabezados de tabla
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3490DC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A4:D4')->applyFromArray($headerStyle);
        
        // Llenar datos
        $row = 5;
        foreach ($data->labels as $index => $label) {
            $correctas = $data->datasets[1]->data[$index];
            $totales = $data->datasets[2]->data[$index];
            $porcentaje = $totales > 0 ? round(($correctas / $totales) * 100, 2) : 0;
            
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $correctas);
            $sheet->setCellValue('C' . $row, $totales);
            $sheet->setCellValue('D' . $row, $porcentaje . '%');
            
            // Formato condicional para porcentaje
            if ($porcentaje < 50) {
                $sheet->getStyle('D' . $row)->getFont()->getColor()->setRGB('FF0000');
            } elseif ($porcentaje < 80) {
                $sheet->getStyle('D' . $row)->getFont()->getColor()->setRGB('FFA500');
            } else {
                $sheet->getStyle('D' . $row)->getFont()->getColor()->setRGB('008000');
            }
            
            $row++;
        }
        
        // Autoajustar columnas
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Crear gráfico
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$4', null, 1),
        ];
        
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$5:$A$' . ($row - 1), null, count($data->labels)),
        ];
        
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$5:$B$' . ($row - 1), null, count($data->labels)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$5:$C$' . ($row - 1), null, count($data->labels)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$5:$D$' . ($row - 1), null, count($data->labels)),
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );
        
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Eficacia por Categoría de Señal');
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
        
        $chart->setTopLeftPosition('F4');
        $chart->setBottomRightPosition('P20');
        $sheet->addChart($chart);
        
        // Pie de página
        $sheet->setCellValue('A' . ($row + 2), 'Generado el: ' . now()->format('d/m/Y H:i:s'));
        
        // Generar archivo
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $fileName = 'reporte_eficacia_' . now()->format('Ymd_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
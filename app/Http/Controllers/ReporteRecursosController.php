<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SenEntrenamiento;
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

class ReporteRecursosController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = SenEntrenamiento::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_creacion', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Obtener datos agrupados por fecha y categoría
        $recursos = $query->selectRaw('categoria, DATE(fecha_creacion) as fecha, COUNT(*) as total')
            ->groupBy('categoria', 'fecha')
            ->orderBy('fecha')
            ->get();

        // Preparar estructura de datos para el gráfico
        $dates = $recursos->pluck('fecha')->unique()->sort()->values();
        $categories = $recursos->pluck('categoria')->unique()->sort()->values();

        // Inicializar estructura para datasets
        $datasets = [];
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryData[$category] = array_fill(0, $dates->count(), 0);
        }

        // Llenar los datos por categoría y fecha
        foreach ($recursos as $recurso) {
            $dateIndex = $dates->search($recurso->fecha);
            $categoryData[$recurso->categoria][$dateIndex] = $recurso->total;
        }

        // Preparar datasets para Chart.js
        $colors = [
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)'
        ];

        $colorIndex = 0;
        foreach ($categoryData as $category => $data) {
            $datasets[] = [
                'label' => $category,
                'data' => $data,
                'backgroundColor' => $colors[$colorIndex % count($colors)],
                'borderColor' => str_replace('0.6', '1', $colors[$colorIndex % count($colors)]),
                'borderWidth' => 1
            ];
            $colorIndex++;
        }

        return response()->json([
            'labels' => $dates->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            }),
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
            ->setTitle("Reporte de Recursos")
            ->setDescription("Reporte de uso de recursos de entrenamiento");
        
        // Encabezado del reporte
        $sheet->setCellValue('A1', 'Reporte de Uso de Recursos de Entrenamiento');
        $sheet->mergeCells('A1:Z1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Rango de fechas
        $sheet->setCellValue('A2', 'Período:');
        $sheet->setCellValue('B2', $data->rango_fechas);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        
        // Encabezados de tabla (fechas en columnas)
        $sheet->setCellValue('A4', 'Categoría');
        $col = 'B';
        foreach ($data->labels as $label) {
            $sheet->setCellValue($col.'4', $label);
            $col++;
        }
        
        // Estilo para encabezados de tabla
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3490DC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A4:'.$col.'4')->applyFromArray($headerStyle);
        
        // Llenar datos por categoría
        $row = 5;
        foreach ($data->datasets as $dataset) {
            $sheet->setCellValue('A'.$row, $dataset->label);
            
            $currentCol = 'B';
            foreach ($dataset->data as $value) {
                $sheet->setCellValue($currentCol.$row, $value);
                $currentCol++;
            }
            $row++;
        }
        
        // Totales por día
        $sheet->setCellValue('A'.$row, 'Total por día');
        $currentCol = 'B';
        foreach ($data->labels as $index => $label) {
            $sumFormula = '=';
            foreach (range(5, $row-1) as $r) {
                $sumFormula .= $currentCol.$r.'+';
            }
            $sumFormula = rtrim($sumFormula, '+');
            $sheet->setCellValue($currentCol.$row, $sumFormula);
            $currentCol++;
        }
        $sheet->getStyle('A'.$row.':'.$currentCol.$row)->getFont()->setBold(true);
        
        // Autoajustar columnas
        foreach (range('A', $col) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Crear gráfico de barras apiladas
        $dataSeriesLabels = [];
        $xAxisTickValues = [];
        $dataSeriesValues = [];
        
        // Preparar series para cada categoría
        foreach ($data->datasets as $index => $dataset) {
            $dataSeriesLabels[] = new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                'Worksheet!$A$' . ($index + 5),
                null,
                1
            );
            
            $dataSeriesValues[] = new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER,
                'Worksheet!$B$' . ($index + 5) . ':$' . $col . '$' . ($index + 5),
                null,
                count($data->labels)
            );
        }
        
        // Valores del eje X (fechas)
        $xAxisTickValues = [
            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                'Worksheet!$B$4:$' . $col . '$4',
                null,
                count($data->labels))
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STACKED, // Barras apiladas
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );
        
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Uso de Recursos por Día y Categoría');
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
        
        $chart->setTopLeftPosition('A'.($row + 3));
        $chart->setBottomRightPosition('P'.($row + 20));
        $sheet->addChart($chart);
        
        // Pie de página
        $sheet->setCellValue('A'.($row + 2), 'Generado el: ' . now()->format('d/m/Y H:i:s'));
        
        // Generar archivo
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $fileName = 'reporte_recursos_' . now()->format('Ymd_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
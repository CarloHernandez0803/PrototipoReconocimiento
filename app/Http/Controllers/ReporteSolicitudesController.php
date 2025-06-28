<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
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

class ReporteSolicitudesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Solicitud::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_solicitud', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $solicitudes = $query->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        // Colores para cada estado (puedes personalizarlos)
        $backgroundColors = [
            'Pendiente' => 'rgba(255, 206, 86, 0.5)',
            'Aprobado' => 'rgba(75, 192, 192, 0.5)',
            'Rechazado' => 'rgba(255, 99, 132, 0.5)',
            'Completado' => 'rgba(54, 162, 235, 0.5)',
        ];

        $borderColors = [
            'Pendiente' => 'rgba(255, 206, 86, 1)',
            'Aprobado' => 'rgba(75, 192, 192, 1)',
            'Rechazado' => 'rgba(255, 99, 132, 1)',
            'Completado' => 'rgba(54, 162, 235, 1)',
        ];

        // Asignar colores según el estado
        $colors = $solicitudes->map(function($item) use ($backgroundColors, $borderColors) {
            return [
                'background' => $backgroundColors[$item->estado] ?? 'rgba(201, 203, 207, 0.5)',
                'border' => $borderColors[$item->estado] ?? 'rgba(201, 203, 207, 1)'
            ];
        });

        $labels = $solicitudes->pluck('estado');
        $datasets = [
            [
                'label' => 'Solicitudes por Estado',
                'data' => $solicitudes->pluck('total'),
                'backgroundColor' => $colors->pluck('background')->toArray(),
                'borderColor' => $colors->pluck('border')->toArray(),
                'borderWidth' => 1,
            ],
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
            ->setTitle("Reporte de Solicitudes")
            ->setDescription("Reporte de gestión de solicitudes de pruebas");
        
        // Encabezado del reporte
        $sheet->setCellValue('A1', 'Reporte de Gestión de Solicitudes de Pruebas');
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Rango de fechas
        $sheet->setCellValue('A2', 'Período:');
        $sheet->setCellValue('B2', $data->rango_fechas);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        
        // Encabezados de tabla
        $sheet->setCellValue('A4', 'Estado');
        $sheet->setCellValue('B4', 'Total de Solicitudes');
        $sheet->setCellValue('C4', 'Porcentaje');
        
        // Estilo para encabezados de tabla
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3490DC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A4:C4')->applyFromArray($headerStyle);
        
        // Llenar datos
        $row = 5;
        $totalSolicitudes = array_sum($data->datasets[0]->data);
        
        foreach ($data->labels as $index => $label) {
            $total = $data->datasets[0]->data[$index];
            $porcentaje = $totalSolicitudes > 0 ? round(($total / $totalSolicitudes) * 100, 2) : 0;
            
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $total);
            $sheet->setCellValue('C' . $row, $porcentaje . '%');
            
            // Formato condicional para porcentaje
            if ($porcentaje < 20) {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('FF0000');
            } elseif ($porcentaje < 50) {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('FFA500');
            } else {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('008000');
            }
            
            $row++;
        }
        
        // Total general
        $sheet->setCellValue('A' . $row, 'Total General');
        $sheet->setCellValue('B' . $row, $totalSolicitudes);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        
        // Autoajustar columnas
        foreach (range('A', 'C') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Crear gráfico circular 3D
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$4', null, 1)
        ];
        
        $xAxisTickValues = [
            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING, 
                'Worksheet!$A$5:$A$' . ($row - 1), 
                null, 
                count($data->labels))
        ];
        
        $dataSeriesValues = [
            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER, 
                'Worksheet!$B$5:$B$' . ($row - 1), 
                null, 
                count($data->labels))
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_PIECHART_3D, // Gráfico circular 3D
            DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues,
            null, // plotDirection
            null, // smooth line
            true  // 3D effect
        );
        
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Distribución de Solicitudes por Estado (3D)');
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
        
        $chart->setTopLeftPosition('E4');
        $chart->setBottomRightPosition('P20');
        $sheet->addChart($chart);
        
        // Pie de página
        $sheet->setCellValue('A' . ($row + 2), 'Generado el: ' . now()->format('d/m/Y H:i:s'));
        
        // Generar archivo
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $fileName = 'reporte_solicitudes_' . now()->format('Ymd_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experiencia;
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

class ReporteExperienciasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Experiencia::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_experiencia', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $experiencias = $query->selectRaw('tipo_experiencia, impacto, COUNT(*) as total')
            ->groupBy('tipo_experiencia', 'impacto')
            ->get();

        // Preparar datos para el gráfico apilado
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
                    'label' => 'Alto Impacto',
                    'data' => $datasets['alto'],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Medio Impacto',
                    'data' => $datasets['medio'],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Bajo Impacto',
                    'data' => $datasets['bajo'],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'rango_fechas' => $startDate && $endDate 
                ? Carbon::parse($startDate)->format('d/m/Y').' - '.Carbon::parse($endDate)->format('d/m/Y')
                : 'Todos los registros'
        ];

        return response()->json($chartData);
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->index($request)->getData();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Establecer propiedades del documento
        $spreadsheet->getProperties()
            ->setCreator("Sistema de Reportes")
            ->setTitle("Reporte de Experiencias")
            ->setDescription("Reporte de análisis de experiencias de usuarios");
        
        // Encabezado del reporte
        $sheet->setCellValue('A1', 'Reporte de Análisis de Experiencias de Usuarios');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Rango de fechas
        $sheet->setCellValue('A2', 'Período:');
        $sheet->setCellValue('B2', $data->rango_fechas);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        
        // Encabezados de tabla
        $sheet->setCellValue('A4', 'Tipo de Experiencia');
        $sheet->setCellValue('B4', 'Alto Impacto');
        $sheet->setCellValue('C4', 'Medio Impacto');
        $sheet->setCellValue('D4', 'Bajo Impacto');
        
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
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $data->datasets[0]->data[$index]);
            $sheet->setCellValue('C' . $row, $data->datasets[1]->data[$index]);
            $sheet->setCellValue('D' . $row, $data->datasets[2]->data[$index]);
            $row++;
        }
        
        // Total general
        $sheet->setCellValue('A' . $row, 'Total General');
        $sheet->setCellValue('B' . $row, '=SUM(B5:B' . ($row - 1) . ')');
        $sheet->setCellValue('C' . $row, '=SUM(C5:C' . ($row - 1) . ')');
        $sheet->setCellValue('D' . $row, '=SUM(D5:D' . ($row - 1) . ')');
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        
        // Autoajustar columnas
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Crear gráfico de barras apiladas
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$4', null, 1)
        ];
        
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$5:$A$' . ($row - 1), null, count($data->labels))
        ];
        
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$5:$B$' . ($row - 1), null, count($data->labels)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$5:$C$' . ($row - 1), null, count($data->labels)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$5:$D$' . ($row - 1), null, count($data->labels))
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STACKED, // Cambiado a STACKED para barras apiladas
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues,
            null,
            null,
            false,
            [0, 1, 2] // Indices de las series a agrupar
        );
        
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Análisis de Experiencias por Tipo e Impacto');
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
        $fileName = 'reporte_experiencias_' . now()->format('Ymd_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
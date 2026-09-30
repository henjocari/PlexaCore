<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class InformeGerencialExport implements WithMultipleSheets
{
    protected $consolidado;
    protected $manifiestos;

    public function __construct($consolidado, $manifiestos)
    {
        $this->consolidado = $consolidado;
        $this->manifiestos = $manifiestos;
    }

    public function sheets(): array
    {
        return [
            new ConsolidadoSheet($this->consolidado),
            new DataManifiestosSheet($this->manifiestos)
        ];
    }
}

// ==========================================
// HOJA 1: RESUMEN CONSOLIDADO
// ==========================================
class ConsolidadoSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function array(): array
    {
        return [[
            $this->data['periodo'],
            $this->data['viajes'],
            $this->data['galones'],
            $this->data['barriles'],
            $this->data['facturacion'],
            $this->data['costo'],
            $this->data['margen']
        ]];
    }

    public function headings(): array
    {
        return ['Periodo', 'Total Viajes', 'Total Galones', 'Total Barriles', 'Facturación Total', 'Costo Terceros', 'Margen Neto'];
    }

    public function title(): string { return 'Consolidado'; }
}

// ==========================================
// HOJA 2: DATA CRUDA
// ==========================================
class DataManifiestosSheet implements FromArray, WithHeadings, WithTitle
{
    protected $manifiestos;

    public function __construct($manifiestos) { $this->manifiestos = $manifiestos; }

    public function array(): array
    {
        return $this->manifiestos->map(function($m) {
            return [
                $m->manifiesto,
                $m->fecha,
                $m->estado,
                $m->origen,
                $m->destino,
                $m->poseedor,
                $m->producto,
                $m->tipoOperacion,
                $m->cantidad,
                $m->fleteRemesa,
                $m->fleteNeto
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['Manifiesto', 'Fecha', 'Estado', 'Origen', 'Destino', 'Poseedor', 'Producto', 'Operación', 'Galones', 'Facturación', 'Costo Tercero'];
    }

    public function title(): string { return 'Data Manifiestos'; }
}
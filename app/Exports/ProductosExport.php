<?php

namespace App\Exports;

use App\Models\Producto;
use Illuminate\Contracts\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $localId;

    public function __construct($localId)
    {
        $this->localId = $localId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Producto::delLocal($this->localId)->orderBy('id')->get();
    }

    public function headings(): array
    {
        return ['Código', 'Nombre', 'Precio Venta', 'Precio Compra', 'Existencias', 'Creado'];
    }

    public function map($producto): array
    {
        return [
            $producto->codigo,
            $producto->nombre,
            number_format($producto->precio_venta, 2, '.', ''),
            number_format($producto->precio_compra, 2, '.', ''),
            $producto->stock_actual,
            optional($producto->created_at)->format('Y-m-d'),
        ];
    }
}

<!-- Ejemplo de integración de botones PDF en la vista de salidas -->

<!-- Botón para ver PDF (stream/preview) -->
<a href="{{ route('salidas.factura', $salida->id) }}" target="_blank" class="btn btn-primary">
    <i class="fas fa-file-pdf"></i> Ver Factura PDF
</a>

<!-- Botón para descargar PDF -->
<a href="{{ route('salidas.factura.download', $salida->id) }}" class="btn btn-success">
    <i class="fas fa-download"></i> Descargar PDF
</a>

<!-- Botón para imprimir (abre en nueva ventana y activa diálogo de impresión) -->
<button onclick="imprimirFactura({{ $salida->id }})" class="btn btn-info">
    <i class="fas fa-print"></i> Imprimir
</button>

<script>
    function imprimirFactura(salidaId) {
        // Abrir PDF en nueva ventana
        const ventana = window.open(
            `/salidas/${salidaId}/factura`,
            '_blank',
            'width=800,height=600'
        );

        // Esperar a que cargue y activar impresión
        ventana.onload = function () {
            ventana.print();
        };
    }
</script>

<!-- 
NOTAS DE INTEGRACIÓN:
1. Reemplaza $salida->id con la variable correcta de tu vista
2. Ajusta las clases CSS según tu framework (Bootstrap, Tailwind, etc.)
3. Los iconos usan Font Awesome, ajusta según tus necesidades
4. Puedes integrar estos botones en:
   - Vista de listado de salidas (index)
   - Vista de detalle de salida (show)
   - Modal de confirmación después de crear una salida
-->
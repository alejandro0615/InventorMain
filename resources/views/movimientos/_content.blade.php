<tbody>
    @forelse ($movimientos as $salida)
        <tr>
            <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                {{ $salida->detalles->count() }}
                {{ $salida->detalles->count() === 1 ? 'producto' : 'productos' }}
            </td>

            <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                {{ $salida->cliente->nombre }}
            </td>

            <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                salida
            </td>

            <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:right;">
                {{ $salida->detalles->sum('cantidad') }}
            </td>

            <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                {{ \Carbon\Carbon::parse($salida->fecha)->format('Y-m-d') }}
            </td>

            <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:center;">
                <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank" class="btn-pdf">
                    <i class="fas fa-file-pdf"></i> Ver PDF
                </a>

                <a href="{{ route('salidas.factura.download', $salida->id) }}" class="btn-download">
                    <i class="fas fa-download"></i> Descargar
                </a>

                <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST"
                      style="display:inline-block;"
                      onsubmit="return confirm('¿Seguro que deseas eliminar esta factura?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="padding:0.75rem; text-align:center; color:#6b7280;">
                No hay movimientos registrados.
            </td>
        </tr>
    @endforelse
</tbody>

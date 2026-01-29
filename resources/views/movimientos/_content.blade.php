<tbody>
    @forelse ($movimientos as $grupoLlave => $itemsGrupo)
        @php $primerItem = $itemsGrupo->first(); @endphp
        <tr style="background:#f9fafb;">
            <td colspan="6" style="padding:0.75rem; font-weight:bold;">
                {{ $primerItem->proveedor_nombre }} - {{ \Carbon\Carbon::parse($primerItem->fecha)->format('Y-m-d') }}
                ({{ $itemsGrupo->count() }} {{ $itemsGrupo->count() === 1 ? 'producto' : 'productos' }})
            </td>
        </tr>
        @foreach ($itemsGrupo as $mov)
            <tr>
                <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; padding-left:2rem;">
                    {{ $mov->producto_nombre }}
                </td>
                <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; padding-left:2rem;">
                    {{ $mov->proveedor_nombre }}
                </td>
                <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; padding-left:2rem;">
                    <span class="{{ $mov->tipo_movimiento === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                        {{ ucfirst($mov->tipo_movimiento) }}
                    </span>
                </td>
                <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:right; padding-left:2rem;">
                    {{ $mov->cantidad }}
                </td>
                <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; padding-left:2rem;">
                    {{ \Carbon\Carbon::parse($mov->fecha)->format('Y-m-d') }}
                </td>
                <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:center;">
                    @if ($mov->tipo_movimiento === 'salida' && $mov->salida_id)
                        <a href="{{ route('salidas.factura', $mov->salida_id) }}" target="_blank" class="btn-pdf">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    @endif
                    <form action="{{ route('movimientos.destroy', $mov->id) }}" method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('¿Eliminar?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" style="padding:0.2rem 0.4rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @empty
        <tr>
            <td colspan="6" style="padding:1rem; text-align:center; color:#6b7280;">
                No hay movimientos
            </td>
        </tr>
    @endforelse
</tbody>

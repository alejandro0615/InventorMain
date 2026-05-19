@extends('layouts.panel')

@section('title', 'Movimientos')
@section('page_title', 'Registrar movimiento')

@section('content')
<section class="card">
    <h2 class="card-title">Registrar movimiento con varios productos</h2>

    @if ($errors->any())
        <div style="color:#b91c1c; font-size:0.9rem; margin-bottom:0.75rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('movimientos.store') }}">
        @csrf

        {{-- Buscador de proveedor --}}
        <div class="form-group" style="margin-bottom:0.75rem;">
            <label class="card-subtitle" for="buscador_proveedor">Buscar proveedor o persona natural</label>
            <input type="text" id="buscador_proveedor" placeholder="Escribe el NIT del proveedor o persona natural"
                   style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
            <div id="resultado_proveedores"
                 style="max-height:200px; overflow-y:auto; margin-top:0.4rem; border:1px solid var(--border-color); border-radius:0.5rem; padding:0.2rem; display:none; background: var(--bg-card); color: var(--text-main);">
            </div>
        </div>

        {{-- Proveedor (cabecera) --}}
        <div class="form-group" style="margin-bottom:0.75rem;">
            <label for="proveedor_id" class="card-subtitle">Proveedor o persona</label>
            <input type="hidden" id="proveedor_id" name="proveedor_id" required>
        </div>

        {{-- Tipo --}}
        <div class="form-group" style="margin-bottom:0.75rem;">
            <label for="tipo_movimiento" class="card-subtitle">Tipo de movimiento</label>
            <select id="tipo_movimiento" name="tipo_movimiento" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
                <option value="">-- Selecciona tipo --</option>
                <option value="entrada" {{ old('tipo_movimiento') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                <option value="salida" {{ old('tipo_movimiento') == 'salida' ? 'selected' : '' }}>Salida</option>
            </select>
        </div>

        {{-- Fecha --}}
        <div class="form-group" style="margin-bottom:0.75rem;">
            <label for="fecha" class="card-subtitle">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}"
                   required style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
        </div>

        <hr style="margin:1rem 0;">

        <h3 class="card-subtitle" style="margin-bottom:0.5rem;">Productos del movimiento</h3>

        {{-- Buscador de productos --}}
        <div class="form-group" style="margin-bottom:0.75rem;">
            <label class="card-subtitle" for="buscador_producto">Buscar producto</label>
            <input type="text" id="buscador_producto" placeholder="Escribe código o nombre"
                   style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
            <div id="resultados_productos"
                 style="max-height:200px; overflow-y:auto; margin-top:0.4rem; border:1px solid var(--border-color); border-radius:0.5rem; padding:0.2rem; display:none; background: var(--bg-card); color: var(--text-main);">
            </div>
        </div>

        <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th width="80">Cant.</th>
                    <th width="100">Precio</th>
                    <th width="80">IVA %</th>
                    <th width="120">Subtotal</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody id="movimiento-items"></tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align:right;">Subtotal sin IVA:</th>
                    <th id="mov_subtotal">$0</th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align:right;">Total IVA:</th>
                    <th id="mov_total_iva">$0</th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align:right;">Total con IVA:</th>
                    <th id="mov_total">$0</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <div id="mov_resumen_iva" style="margin-top:0.5rem;"></div>

        <button type="submit" class="btn-tour" style="background:#16a34a; margin-top:0.5rem; color:white; border:none; padding:0.5rem 1rem; border-radius:0.4rem; cursor:pointer;">
            Registrar movimiento
        </button>
        <a href="{{ route('movimientos.index') }}" class="btn-tour"
           style="background:#cb0f0f; text-decoration:none; padding:0.4rem 0.8rem; color:white; border-radius:0.4rem; display:inline-block; margin-top:0.5rem;">
            Cancelar Movimiento
        </a>
    </form>
</section>

{{-- Datos para JS --}}
<script>
    const PRODUCTOS   = @json($productos);
    const PROVEEDORES = @json($proveedores);
</script>

{{-- JS PROVEEDOR --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputProv      = document.getElementById('buscador_proveedor');
    const resultadosProv = document.getElementById('resultado_proveedores');
    const selectProv     = document.getElementById('proveedor_id');

    function renderResultadosProveedor(lista) {
        resultadosProv.innerHTML = '';
        if (!lista.length) {
            resultadosProv.style.display = 'none';
            return;
        }

        lista.forEach(p => {
            const item = document.createElement('div');
            item.style.padding = '0.3rem 0.4rem';
            item.style.cursor  = 'pointer';
            item.textContent   = `${p.nit} - ${p.nombre}`;
            item.dataset.id    = p.id;
            resultadosProv.appendChild(item);
        });

        resultadosProv.style.display = 'block';
    }

    inputProv.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        if (!q) {
            resultadosProv.style.display = 'none';
            resultadosProv.innerHTML = '';
            return;
        }

        const filtrados = PROVEEDORES.filter(p =>
            (p.nit && p.nit.toLowerCase().includes(q)) ||
            (p.nombre && p.nombre.toLowerCase().includes(q))
        );

        renderResultadosProveedor(filtrados);
    });

    resultadosProv.addEventListener('click', function(e) {
        const item = e.target.closest('[data-id]');
        if (!item) return;

        const id = item.dataset.id;
        selectProv.value = id;
        inputProv.value  = item.textContent;

        resultadosProv.innerHTML = '';
        resultadosProv.style.display = 'none';
    });
});
</script>

{{-- JS PRODUCTOS + TOTALES --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar   = document.getElementById('buscador_producto');
    const resultadosDiv = document.getElementById('resultados_productos');
    const tbody         = document.getElementById('movimiento-items');
    const subtotalSpan  = document.getElementById('mov_subtotal');
    const totalIvaSpan  = document.getElementById('mov_total_iva');
    const totalSpan     = document.getElementById('mov_total');
    const resumenIvaDiv = document.getElementById('mov_resumen_iva');
    // Movement type handling
    const selectTipo = document.getElementById('tipo_movimiento');
    let movimientoTipo = selectTipo.value;
    selectTipo.addEventListener('change', () => {
        movimientoTipo = selectTipo.value;
        // Update existing items price based on new movement type
        items = items.map(item => {
            const prod = PRODUCTOS.find(p => p.id === item.id);
            if (!prod) return item;
            return {
                ...item,
                precio: movimientoTipo === 'entrada' ? (prod.precio_compra ?? 0) : (prod.precio_venta ?? 0)
            };
        });
        renderTabla();
    });

    let items = []; // {id, codigo, nombre, precio, cantidad, iva}

    function formatear(valor) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            maximumFractionDigits: 0
        }).format(valor);
    }

    function recalcularTotales() {
        let subtotal = 0;
        let totalIva = 0;
        const ivaMap = {};

        items.forEach(item => {
            const cant   = parseFloat(item.cantidad) || 0;
            const base   = cant * item.precio;
            const ivaPct = parseFloat(item.iva) || 0;
            const ivaVal = base * (ivaPct / 100);

            subtotal += base;
            totalIva += ivaVal;

            if (!ivaMap[ivaPct]) {
                ivaMap[ivaPct] = { base: 0, iva: 0 };
            }
            ivaMap[ivaPct].base += base;
            ivaMap[ivaPct].iva  += ivaVal;
        });

        subtotalSpan.textContent = formatear(subtotal);
        totalIvaSpan.textContent = formatear(totalIva);
        totalSpan.textContent    = formatear(subtotal + totalIva);

        if (resumenIvaDiv) {
            resumenIvaDiv.innerHTML = '';
            Object.keys(ivaMap).forEach(pct => {
                const g = ivaMap[pct];
                const div = document.createElement('div');
                div.textContent = `IVA ${pct}% → Base: ${formatear(g.base)} | IVA: ${formatear(g.iva)}`;
                resumenIvaDiv.appendChild(div);
            });
        }
    }

    function renderTabla() {
        tbody.innerHTML = '';

        items.forEach((item, index) => {
            const cant = parseFloat(item.cantidad) || 0;
            const base = cant * item.precio;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    ${item.codigo} - ${item.nombre}
                    <input type="hidden" name="producto_id[]" value="${item.id}">
                </td>
                <td>
                    <input type="number"
                           name="cantidad[]"
                           min="1"
                           value="${item.cantidad}"
                           data-index="${index}"
                           class="input-cantidad"
                           style="width:70px; padding:0.15rem 0.25rem; border-radius:0.3rem; border:1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
                </td>
                <td>
                    <input type="number"
                           name="precio[]"
                           min="0"
                           step="1"
                           value="${item.precio}"
                           data-index="${index}"
                           class="input-precio"
                           readonly
                           style="width:90px; padding:0.15rem 0.25rem; border-radius:0.3rem; border:1px solid var(--border-color); background-color: rgba(148, 163, 184, 0.1); color: var(--text-main); cursor: not-allowed;">
                </td>
                <td>
                    <input type="number"
                           name="iva_porcentaje[]"
                           class="input-iva-linea"
                           data-index="${index}"
                           min="0" max="100" step="1"
                           value="${item.iva}"
                           style="width:60px; padding:0.15rem 0.25rem; border-radius:0.3rem; border:1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
                </td>
                <td>${formatear(base)}</td>
                <td>
                    <button type="button"
                            data-index="${index}"
                            class="btn-quitar"
                            style="background:#b91c1c; color:white; border:none; padding:0.2rem 0.4rem; border-radius:0.4rem;">
                        X
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        recalcularTotales();
    }

    function renderResultados(lista) {
        resultadosDiv.innerHTML = '';
        if (!lista.length) {
            resultadosDiv.style.display = 'none';
            return;
        }

        lista.forEach(p => {
            const item = document.createElement('div');
            item.style.display = 'flex';
            item.style.justifyContent = 'space-between';
            item.style.alignItems = 'center';
            item.style.padding = '0.3rem 0.4rem';
            item.style.cursor = 'pointer';

            item.innerHTML = `
                <span>${p.codigo} - ${p.nombre}</span>
                <button type="button"
                        data-id="${p.id}"
                        style="background:#2563eb; color:white; border:none; padding:0.2rem 0.5rem; border-radius:0.4rem;">
                    +
                </button>
            `;
            resultadosDiv.appendChild(item);
        });

        resultadosDiv.style.display = 'block';
    }

    inputBuscar.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        if (!q) {
            resultadosDiv.style.display = 'none';
            resultadosDiv.innerHTML = '';
            return;
        }

        const filtrados = PRODUCTOS.filter(p =>
            (p.codigo && p.codigo.toLowerCase().includes(q)) ||
            (p.nombre && p.nombre.toLowerCase().includes(q))
        );

        renderResultados(filtrados);
    });

    resultadosDiv.addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-id]');
        if (!btn) return;

        const id   = parseInt(btn.getAttribute('data-id'));
        const prod = PRODUCTOS.find(p => p.id === id);
        if (!prod) return;

        const existente = items.find(i => i.id === id);
        if (existente) {
            existente.cantidad = (parseFloat(existente.cantidad) || 0) + 1;
        } else {
            items.push({
                id: prod.id,
                codigo: prod.codigo,
                nombre: prod.nombre,
                precio: movimientoTipo === 'entrada' ? (prod.precio_compra ?? 0) : (prod.precio_venta ?? 0),
                cantidad: 1,
                iva: prod.iva_porcentaje ?? 0,
            });
        }

        inputBuscar.value = '';
        resultadosDiv.innerHTML = '';
        resultadosDiv.style.display = 'none';

        renderTabla();
    });

    tbody.addEventListener('input', function(e) {
        const index = parseInt(e.target.dataset.index);
        if (e.target.classList.contains('input-cantidad')) {
            let val = parseFloat(e.target.value) || 1;
            if (val < 1) val = 1;
            items[index].cantidad = val;
            renderTabla();
        }

        if (e.target.classList.contains('input-precio')) {
            let val = parseFloat(e.target.value) || 0;
            if (val < 0) val = 0;
            items[index].precio = val;
            renderTabla();
        }

        if (e.target.classList.contains('input-iva-linea')) {
            let val = parseFloat(e.target.value) || 0;
            if (val < 0) val = 0;
            if (val > 100) val = 100;
            items[index].iva = val;
            recalcularTotales();
        }
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-quitar')) {
            const index = parseInt(e.target.dataset.index);
            items.splice(index, 1);
            renderTabla();
        }
    });
});
</script>
@endsection

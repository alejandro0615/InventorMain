<section class="card" style="animation: fadeIn 0.4s ease;">
    <style>
        .page-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        /* CONFIGURACIÓN CON MEJOR CONTRASTE PARA DARK MODE */
        .quote-config-box {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            background: var(--bg-body);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--primary);
            display: block;
            margin-bottom: 0.6rem;
            letter-spacing: 0.05em;
        }

        .quote-input {
            width: 100%;
            padding: 0.8rem;
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.6rem;
            font-size: 0.9rem;
        }

        .search-wrapper {
            margin-bottom: 2rem;
            position: relative;
        }

        .search-main-input {
            width: 100%;
            padding: 1.2rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 0.75rem;
            background: var(--bg-card);
            color: var(--text-main);
            border: 2px solid var(--border-color);
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .search-main-input:focus {
            border-color: var(--primary);
            box-shadow: 0 4px 20px var(--primary-glow);
            outline: none;
        }

        .results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 250px;
            overflow-y: auto;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 100;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: none;
        }

        .result-item {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s;
        }

        .result-item:hover { background: rgba(148, 163, 184, 0.08); }

        .summary-card {
            background: var(--bg-body);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid var(--border-color);
        }

        .quote-table-wrapper {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            box-shadow: var(--card-shadow);
        }

        .quote-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 0;
            background: transparent;
        }

        .quote-table thead th {
            background: transparent;
        }

        .quote-table th,
        .quote-table td {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .quote-config-box,
            #tourt-finC {
                grid-template-columns: 1fr !important;
            }

            .quote-table-wrapper {
                overflow: visible;
            }

            .quote-table thead {
                display: none;
            }

            .quote-table tbody,
            .quote-table tr,
            .quote-table td {
                display: block;
                width: 100%;
            }

            .quote-table tbody tr {
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
            }

                .quote-table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 1rem;
                padding: 0.9rem 1rem;
                border-bottom: 1px solid rgba(148, 163, 184, 0.16);
                white-space: normal;
                color: var(--text-main);
                font-size: 0.84rem;
            }

            .quote-table td:last-child {
                border-bottom: none;
            }

                .quote-table td::before {
                content: attr(data-label);
                flex: 0 0 40%;
                font-size: 0.69rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--text-muted);
                padding-top: 0.08rem;
            }

            .quote-table td > * {
                color: var(--text-main);
                text-align: right;
            }

            .quote-table td[data-label="Acciones"] {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                min-height: 62px;
            }

            .quote-table td[data-label="Acciones"]::before {
                order: 0;
                width: 100%;
                flex: none;
            }

            .quote-table td[data-label="Acciones"] > * {
                width: auto;
            }

            .quote-table td[data-label="Acciones"] .quote-actions {
                order: 1;
                margin-top: auto;
                display: flex;
                justify-content: flex-end;
                gap: 0.5rem;
                align-self: flex-end;
                flex-wrap: wrap;
            }

            .quote-table td[data-label="Acciones"] a,
            .quote-table td[data-label="Acciones"] button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.35rem 0.7rem;
                font-size: 0.75rem;
                font-weight: 800;
                border-radius: 999px;
                min-width: 56px;
                text-decoration: none;
            }

            .quote-table td[data-label="Acciones"] a,
            .quote-table td[data-label="Acciones"] button {
                position: relative;
                z-index: 8;
            }

            .quote-table td[data-label="Acciones"] a.btn-print { background: var(--primary); color: #fff; }
            .quote-table td[data-label="Acciones"] button.btn-delete { background: rgba(239,68,68,0.08); color: #ef4444; border: 1px solid rgba(239,68,68,0.12); }

            .quote-table tr,
            .quote-table td { background: transparent; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div id="tourt-cotizaciones" class="page-header">
        <h2 class="card-title" style="font-size: 1.6rem; margin: 0; text-transform: uppercase;">Nueva Cotización Comercial</h2>
        <p class="text-muted" style="font-size: 0.85rem;">Cree presupuestos profesionales de manera rápida.</p>
    </div>

    <form method="POST" action="{{ route('cotizaciones.store') }}">
        @csrf

        {{-- CONFIGURACIÓN DE CLIENTE Y FECHA --}}
        <div id="tour-config-cotizacion" class="quote-config-box">
            <div>
                <label for="cliente_id" class="form-label">Cliente Destinatario</label>
                {{-- BUSCADOR DE CLIENTE --}}
                <div style="position: relative; margin-bottom: 0.5rem;">
                    <input type="text" id="buscador_cliente" placeholder="Filtrar cliente por nombre o NIT..." 
                           style="width: 100%; padding: 0.5rem 0.8rem; font-size: 0.85rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);">
                </div>
                <select id="cliente_id" name="cliente_id" required class="quote-input">
                    <option value="">Seleccione el cliente...</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}" @selected(old('cliente_id') == $proveedor->id) 
                                data-search="{{ strtolower($proveedor->nombre . ' ' . $proveedor->nit) }}">
                            {{ $proveedor->nombre }} {{ $proveedor->nit ? '- ' . $proveedor->nit : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="fecha" class="form-label">Fecha de Emisión</label>
                <input type="date" id="fecha" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}" required class="quote-input">
            </div>
        </div>

        {{-- BÚSQUEDA DE PRODUCTOS GRANDE --}}
        <div id="tour-buscador-cotizacion" class="search-wrapper">
            <label class="form-label">Añadir Productos al Listado</label>
            <input type="text" id="buscador_producto" class="search-main-input" placeholder="Escriba el nombre o código del producto...">
            <div id="resultados_productos" class="results-dropdown">
            </div>
        </div>

        {{-- TABLA DE ÍTEMS --}}
        <div id="tourt-tablaC" class="table-wrapper quote-table-wrapper" style="margin-bottom: 2rem;">
            <table class="quote-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th width="120" class="text-center">Cantidad</th>
                        <th width="180" class="text-right">Precio Unitario</th>
                        <th width="180" class="text-right">Subtotal</th>
                        <th width="80"></th>
                    </tr>
                </thead>
                <tbody id="cotizacion-items">
                </tbody>
            </table>
        </div>

        {{-- TOTALES --}}
        <div id="tourt-finC" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: center;">
            <div class="summary-card">
                <div style="display:flex; justify-content:space-between; margin-bottom: 0.6rem; font-size: 0.95rem;">
                    <span class="text-muted">Subtotal:</span>
                    <strong id="subtotal_cotizacion" style="color: var(--text-main);">$ 0</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size: 1.3rem; color: var(--primary); font-weight: 800; border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                    <span>TOTAL COTIZACIÓN:</span>
                    <strong id="total_cotizacion">$ 0</strong>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary-action" style="padding: 1.2rem; font-size: 0.9rem; width: 100%;">
                    GENERAR DOCUMENTO PDF
                </button>
            </div>
        </div>
    </form>
</section>

<section class="card" style="margin-top: 2rem;">
    <h3 class="card-title" style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1.5rem;">Historial Reciente</h3>
    <div id="tablaHistorial" class="table-wrapper quote-table-wrapper">
        <table class="quote-table">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ultimasCotizaciones as $cot)
                    <tr>
                        <td class="bold" data-label="Ref.">#{{ str_pad($cot->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td data-label="Cliente">{{ $cot->cliente->nombre }}</td>
                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($cot->fecha)->format('d/m/Y') }}</td>
                        <td class="text-right bold" data-label="Total">$ {{ number_format($cot->total, 0, ',', '.') }}</td>
                        <td class="text-center" data-label="Acciones">
                            <a href="{{ route('cotizaciones.pdf', $cot->id) }}" target="_blank" class="btn-print" style="padding: 0.4rem 0.8rem;">PDF</a>
                            <form action="{{ route('cotizaciones.destroy', $cot->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete" style="padding: 0.4rem 0.8rem;" onclick="return confirm('¿Eliminar?')">X</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Sin registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<script>
    const PRODUCTOS = @json($productos);

    document.addEventListener('DOMContentLoaded', function() {
        const inputBuscar = document.getElementById('buscador_producto');
        const resultadosDiv = document.getElementById('resultados_productos');
        const tbody = document.getElementById('cotizacion-items');
        const subtotalSpan = document.getElementById('subtotal_cotizacion');
        const totalSpan = document.getElementById('total_cotizacion');

        let items = [];

        function formatear(valor) {
            return '$ ' + new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(valor);
        }

        function recalcularTotales() {
            let total = 0;
            items.forEach(item => { total += (parseFloat(item.cantidad) || 0) * item.precio; });
            subtotalSpan.textContent = formatear(total);
            totalSpan.textContent = formatear(total);
        }

        function renderTabla() {
            tbody.innerHTML = '';
            items.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="bold" data-label="Descripción">${item.nombre}<input type="hidden" name="producto_id[]" value="${item.id}"></td>
                    <td class="text-center" data-label="Cantidad"><input type="number" name="cantidad[]" min="1" value="${item.cantidad}" data-index="${index}" class="input-cantidad" style="width:80px; text-align:center; padding: 0.5rem; background: var(--bg-body); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 4px;"></td>
                    <td class="text-right" data-label="Precio Unitario">${formatear(item.precio)}</td>
                    <td class="text-right bold" data-label="Subtotal" style="color:var(--primary); font-size: 1rem;">${formatear((item.cantidad || 0) * item.precio)}</td>
                    <td class="text-center" data-label="Acciones"><button type="button" data-index="${index}" class="btn-quitar" style="color:#ef4444; border:none; background:none; cursor:pointer; font-weight:800; font-size: 0.8rem;">QUITAR</button></td>
                `;
                tbody.appendChild(tr);
            });
            recalcularTotales();
        }

        inputBuscar.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstResult = resultadosDiv.querySelector('.result-item');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });

        inputBuscar.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            if (!q) { resultadosDiv.style.display = 'none'; return; }
            const filtrados = PRODUCTOS.filter(p => (p.codigo && p.codigo.toLowerCase().includes(q)) || (p.nombre && p.nombre.toLowerCase().includes(q)));
            
            resultadosDiv.innerHTML = '';
            filtrados.forEach(p => {
                const item = document.createElement('div');
                item.className = 'result-item';
                item.innerHTML = `
                    <div style="font-weight:700; font-size:0.95rem;">${p.nombre}</div>
                    <div style="background:var(--primary); color:white; padding:2px 10px; border-radius:6px; font-size:0.8rem; font-weight:800;">$${p.precio_venta}</div>
                `;
                item.onclick = () => {
                    const ex = items.find(i => i.id === p.id);
                    if (ex) ex.cantidad++;
                    else items.push({ id: p.id, nombre: p.nombre, precio: p.precio_venta || 0, cantidad: 1 });
                    inputBuscar.value = '';
                    resultadosDiv.style.display = 'none';
                    renderTabla();
                };
                resultadosDiv.appendChild(item);
            });
            resultadosDiv.style.display = 'block';
        });

        tbody.addEventListener('input', function(e) {
            if (e.target.classList.contains('input-cantidad')) {
                items[parseInt(e.target.dataset.index)].cantidad = e.target.value;
                renderTabla();
            }
        });

        tbody.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-quitar')) {
                items.splice(parseInt(e.target.dataset.index), 1);
                renderTabla();
            }
        });

        // Lógica para el buscador de cliente
        const inputBuscadorCliente = document.getElementById('buscador_cliente');
        const selectCliente = document.getElementById('cliente_id');
        const optionsCliente = Array.from(selectCliente.options).slice(1); // Omitir el placeholder

        inputBuscadorCliente.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Opcional: seleccionar el primer resultado visible si hay uno
                const firstVisible = optionsCliente.find(opt => opt.style.display !== 'none');
                if (firstVisible) {
                    selectCliente.value = firstVisible.value;
                    // Opcional: limpiar el buscador tras seleccionar
                    this.value = '';
                    optionsCliente.forEach(opt => { opt.style.display = ''; opt.disabled = false; });
                }
            }
        });

        inputBuscadorCliente.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            
            optionsCliente.forEach(opt => {
                const searchData = opt.getAttribute('data-search') || '';
                if (searchData.includes(q)) {
                    opt.style.display = '';
                    opt.disabled = false;
                } else {
                    opt.style.display = 'none';
                    opt.disabled = true;
                }
            });

            // Si el valor actual del select ya no es visible, resetearlo
            const selectedOpt = selectCliente.options[selectCliente.selectedIndex];
            if (selectedOpt && selectedOpt.style.display === 'none') {
                selectCliente.value = '';
            }
        });
    });
</script>
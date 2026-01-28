<section class="card">
        <h2 class="card-title">Mira tu cotización</h2>

        @if ($errors->any())
            <div style="color:#b91c1c; font-size:0.9rem; margin-bottom:0.75rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('cotizacion.store') }}">
            @csrf

            {{-- Fecha --}}
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="fecha" class="card-subtitle">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}"
                    required style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <hr style="margin:1rem 0;">

            {{-- Buscador de productos --}}
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="card-subtitle" for="buscador_producto">Buscar producto</label>
                <input type="text" id="buscador_producto" placeholder="Escribe código o nombre"
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
                <div id="resultados_productos"
                    style="max-height:200px; overflow-y:auto; margin-top:0.4rem; border:1px solid #e5e7eb; border-radius:0.5rem; padding:0.2rem; display:none;">
                </div>
            </div>


            <table class="table">
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
                <tbody id="cotizacion-items"></tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Subtotal sin IVA:</th>
                        <th id="subtotal_cotizacion">$0</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Total IVA:</th>
                        <th id="total_iva">$0</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Total con IVA:</th>
                        <th id="total_cotizacion">$0</th>
                        <th></th>
                    </tr>
                </tfoot>
                <div id="resumen_iva" style="margin-top:0.5rem;"></div>

            </table>
        </form>
    </section>

    <script>
        const PRODUCTOS = @json($productos);

        document.addEventListener('DOMContentLoaded', function() {
            const inputBuscar = document.getElementById('buscador_producto');
            const resultadosDiv = document.getElementById('resultados_productos');
            const tbody = document.getElementById('cotizacion-items');
            const subtotalSpan = document.getElementById('subtotal_cotizacion');
            const totalIvaSpan = document.getElementById('total_iva');
            const totalSpan = document.getElementById('total_cotizacion');
            const resumenIvaDiv = document.getElementById('resumen_iva');

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
                const ivaMap = {}; // { '0': {base, iva}, '19': {base, iva} }

                items.forEach(item => {
                    const cant = parseFloat(item.cantidad) || 0;
                    const base = cant * item.precio;
                    const ivaPct = parseFloat(item.iva) || 0;
                    const ivaVal = base * (ivaPct / 100);

                    subtotal += base;
                    totalIva += ivaVal;

                    if (!ivaMap[ivaPct]) {
                        ivaMap[ivaPct] = {
                            base: 0,
                            iva: 0
                        };
                    }
                    ivaMap[ivaPct].base += base;
                    ivaMap[ivaPct].iva += ivaVal;
                });

                subtotalSpan.textContent = formatear(subtotal);
                totalIvaSpan.textContent = formatear(totalIva);
                totalSpan.textContent = formatear(subtotal + totalIva);

                if (resumenIvaDiv) {
                    resumenIvaDiv.innerHTML = '';
                    Object.keys(ivaMap).forEach(pct => {
                        const g = ivaMap[pct];
                        const div = document.createElement('div');
                        div.textContent =
                            `IVA ${pct}% → Base: ${formatear(g.base)} | IVA: ${formatear(g.iva)}`;
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
                           style="width:70px; padding:0.15rem 0.25rem; border-radius:0.3rem; border:1px solid #d1d5db;">
                </td>
                <td>${formatear(item.precio)}</td>
                <td>
                    <input type="number"
                           class="input-iva-linea"
                           data-index="${index}"
                           min="0" max="100" step="1"
                           value="${item.iva}"
                           style="width:60px; padding:0.15rem 0.25rem; border-radius:0.3rem; border:1px solid #d1d5db;">
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

                const id = parseInt(btn.getAttribute('data-id'));
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
                        precio: prod.precio_venta ?? 0,
                        cantidad: 1,
                        iva: prod.iva_porcentaje ?? 0, // ajusta al nombre del campo
                    });
                }

                inputBuscar.value = '';
                resultadosDiv.innerHTML = '';
                resultadosDiv.style.display = 'none';

                renderTabla();
            });

            tbody.addEventListener('input', function(e) {
                if (e.target.classList.contains('input-cantidad')) {
                    const index = parseInt(e.target.dataset.index);
                    items[index].cantidad = e.target.value;
                    recalcularTotales();
                }

                if (e.target.classList.contains('input-iva-linea')) {
                    const index = parseInt(e.target.dataset.index);
                    let val = parseFloat(e.target.value);
                    if (isNaN(val) || val < 0) val = 0;
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

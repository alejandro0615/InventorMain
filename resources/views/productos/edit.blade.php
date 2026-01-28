@extends('layouts.panel')

@section('title', 'Productos')
@section('page_title', 'Editar producto')

@section('content')
    <section class="card">
        <h2 class="card-title">Editar producto</h2>

        @if ($errors->any())
            <div style="color:#b91c1c; font-size:0.9rem; margin-bottom:0.75rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('productos.update', $producto->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="codigo" class="card-subtitle">Código</label>
                <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="nombre" class="card-subtitle">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="stock_actual" class="card-subtitle">Stock actual</label>
                <input type="number" id="stock_actual" name="stock_actual"
                    value="{{ old('stock_actual', $producto->stock_actual) }}" min="0" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="precio_compra" class="card-subtitle">Precio de compra (COP)</label>
                <input type="number" step="1" id="precio_compra" name="precio_compra"
                    value="{{ old('precio_compra', $producto->precio_compra) }}" min="0" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="precio_venta" class="card-subtitle">Precio de venta (COP)</label>
                <input type="number" step="1" id="precio_venta" name="precio_venta"
                    value="{{ old('precio_venta', $producto->precio_venta) }}" min="0" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <button type="submit" class="btn-tour" style="background:#16a34a; margin-top:0.5rem;">
                Actualizar
            </button>
        </form>
    </section>
@endsection

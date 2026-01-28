@extends('layouts.panel')

@section('title', 'Proveedores')
@section('page_title', 'Crear proveedores')

@section('content')
    <section class="card">
        <h2 class="card-title">Crear proveedor</h2>

        @if ($errors->any())
            <div style="color:#b91c1c; font-size:0.9rem; margin-bottom:0.75rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('proveedores.store') }}">
            @csrf

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="nombre" class="card-subtitle">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="direccion" class="card-subtitle">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="telefono" class="card-subtitle">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="email" class="card-subtitle">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label for="nit" class="card-subtitle">NIT</label>
                <input type="text" id="nit" name="nit" value="{{ old('nit') }}" required
                    style="width:100%; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
            </div>

            <button type="submit" class="btn-tour" style="background:#16a34a; margin-top:0.5rem;">
                Guardar
            </button>
        </form>

    </section>

@endsection

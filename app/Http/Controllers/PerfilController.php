<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        if ($request->ajax()) {
            return view('usuarios._content', compact('usuario'));
        }

        return view('usuarios.perfil', compact('usuario'));
    }
}

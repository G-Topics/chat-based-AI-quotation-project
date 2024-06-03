<?php

namespace App\Http\Controllers;

use App\Models\TipoCultivo;
use Illuminate\Http\Request;

class TipoCultivoController extends Controller
{
    public function index()
    {
        $tipoCultivos = TipoCultivo::all();
        return view('tipo_cultivos.index', compact('tipoCultivos'));
    }

    public function create()
    {
        return view('tipo_cultivos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $tipoCultivo = TipoCultivo::create($data);

        return redirect()->route('tipo_cultivos.index')->with('success', 'Tipo de cultivo creado exitosamente.');
    }

    public function show(TipoCultivo $tipoCultivo)
    {
        return view('tipo_cultivos.show', compact('tipoCultivo'));
    }

    public function edit(TipoCultivo $tipoCultivo)
    {
        return view('tipo_cultivos.edit', compact('tipoCultivo'));
    }

    public function update(Request $request, TipoCultivo $tipoCultivo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $tipoCultivo->update($data);

        return redirect()->route('tipo_cultivos.index')->with('success', 'Tipo de cultivo actualizado exitosamente.');
    }

    public function destroy(TipoCultivo $tipoCultivo)
    {
        $tipoCultivo->delete();

        return redirect()->route('tipo_cultivos.index')->with('success', 'Tipo de cultivo eliminado exitosamente.');
    }
}

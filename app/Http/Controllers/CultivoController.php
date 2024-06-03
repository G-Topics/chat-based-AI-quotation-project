<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\TipoCultivo;
use Illuminate\Http\Request;

class CultivoController extends Controller
{public function index()
    {
        $cultivos = Cultivo::all();
        return view('cultivos.index', compact('cultivos'));
    }

    public function create()
    {
        $tipoCultivos = TipoCultivo::all();
        return view('cultivos.create', compact('tipoCultivos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'id_tipo_cultivo' => 'required|integer|exists:tipo_cultivos,id',
        ]);

        $cultivo = Cultivo::create($data);

        return redirect()->route('cultivos.index')->with('success', 'Cultivo creado exitosamente.');
    }

    public function show(Cultivo $cultivo)
    {
        return view('cultivos.show', compact('cultivo'));
    }

    public function edit(Cultivo $cultivo)
    {
        $tipoCultivos = TipoCultivo::all();
        return view('cultivos.edit', compact('cultivo', 'tipoCultivos'));
    }

    public function update(Request $request, Cultivo $cultivo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'id_tipo_cultivo' => 'required|integer|exists:tipo_cultivos,id',
        ]);

        $cultivo->update($data);

        return redirect()->route('cultivos.index')->with('success', 'Cultivo actualizado exitosamente.');
    }

    public function destroy(Cultivo $cultivo)
    {
        $cultivo->delete();

        return redirect()->route('cultivos.index')->with('success', 'Cultivo eliminado exitosamente.');
    }
}

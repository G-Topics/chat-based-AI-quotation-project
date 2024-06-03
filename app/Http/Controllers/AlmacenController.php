<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        return view('almacenes.index', compact('almacenes'));
    }

    public function create()
    {
        $sucursales = Sucursal::all();
        return view('almacenes.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'capacidad_almacenamiento' => 'required|numeric|min:1',
            'encargado_almacen' => 'required|string|max:255',
            'id_sucursal' => 'required|integer|exists:sucursales,id',
        ]);

        $almacen = Almacen::create($data);

        return redirect()->route('almacenes.index')->with('success', 'Almacén creado exitosamente.');
    }

    public function show(Almacen $almacen)
    {
        return view('almacenes.show', compact('almacen'));
    }

    public function edit(Almacen $almacen)
    {
        $sucursales = Sucursal::all();
        return view('almacenes.edit', compact('almacen', 'sucursales'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $data = $request->validate([
            'capacidad_almacenamiento' => 'required|numeric|min:1',
            'encargado_almacen' => 'required|string|max:255',
            'id_sucursal' => 'required|integer|exists:sucursales,id',
        ]);

        $almacen->update($data);

        return redirect()->route('almacenes.index')->with('success', 'Almacén actualizado exitosamente.');
    }

    public function destroy(Almacen $almacen)
    {
        $almacen->delete();

        return redirect()->route('almacenes.index')->with('success', 'Almacén eliminado exitosamente.');
    }
}

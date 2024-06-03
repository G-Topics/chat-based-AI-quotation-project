<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\InventarioSucursalAlmacen;
use Illuminate\Http\Request;

class InventarioSucursalAlmacenController extends Controller
{
    public function index()
    {
        $inventarios = InventarioSucursalAlmacen::all();
        return view('inventario_sucursal_almacenes.index', compact('inventarios'));
    }

    public function create()
    {
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('inventario_sucursal_almacenes.create', compact('productos', 'almacenes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cantidad_en_inventario' => 'required|numeric|min:1',
            'fecha_ultima_actualizacion' => 'required|date',
            'id_producto' => 'required|integer|exists:productos,id',
            'id_almacen' => 'required|integer|exists:almacenes,id',
        ]);

        $inventarioSucursalAlmacen = InventarioSucursalAlmacen::create($data);

        return redirect()->route('inventario_sucursal_almacenes.index')->with('success', 'Inventario actualizado exitosamente.');
    }

    public function show(InventarioSucursalAlmacen $inventarioSucursalAlmacen)
    {
        return view('inventario_sucursal_almacenes.show', compact('inventarioSucursalAlmacen'));
    }

    public function edit(InventarioSucursalAlmacen $inventarioSucursalAlmacen)
    {
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('inventario_sucursal_almacenes.edit', compact('inventarioSucursalAlmacen', 'productos', 'almacenes'));
    }

    public function update(Request $request, InventarioSucursalAlmacen $inventarioSucursalAlmacen)
    {
        $data = $request->validate([
            'cantidad_en_inventario' => 'required|numeric|min:1',
            'fecha_ultima_actualizacion' => 'required|date',
            'id_producto' => 'required|integer|exists:productos,id',
            'id_almacen' => 'required|integer|exists:almacenes,id',
        ]);

        $inventarioSucursalAlmacen->update($data);

        return redirect()->route('inventario_sucursal_almacenes.index')->with('success', 'Inventario actualizado exitosamente.');
    }

    public function destroy(InventarioSucursalAlmacen $inventarioSucursalAlmacen)
    {
        $inventarioSucursalAlmacen->delete();

        return redirect()->route('inventario_sucursal_almacenes.index')->with('success', 'Inventario eliminado exitosamente.');
    }
}

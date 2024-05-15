<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            $permission = new Cliente();
            $permission->nombre = 'Ivan';
            $permission->telefono = '+59167874079';
            $permission->save();

            $permission = new Cliente();
            $permission->nombre = 'Facundo';
            $permission->telefono = '+59176653572';
            $permission->save();

            $permission = new Cliente();
            $permission->nombre = 'Marco';
            $permission->telefono = '+59178472821';
            $permission->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al crear el cliente: ' . $e->getMessage());
        }
    }
}

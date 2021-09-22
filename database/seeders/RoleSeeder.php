<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Cadastrando regras
        foreach(['Admin', 'Gestor_Unidade','Gestor_Area','Gestor_Subarea','Usuario'] as $name) {
            Role::create([
                'name' => $name
            ]);
        }
    }
}

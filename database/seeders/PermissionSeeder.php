<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Cadastrando regras de permissão
        foreach (['user_create','user_edit','user_read','user_delete' ] as $name) {
            Permission:: create([
                'name' => $name
            ])->roles()->attach(1);
        }
    }
}

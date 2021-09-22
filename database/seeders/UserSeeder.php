<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        //cadastrando as regras...
        foreach (['Admin', 'Usuario'] as $nome) {
            User::create([
                'name'=>'Fernando Amaral',
                'email'=>'admin@gmail.com',
                'password'=>'123456'
            ])->roles()->attach(1);

            User::create([
                'name'=>'Usuario 1',
                'email'=>'user@gmail.com',
                'password'=>'123456'
            ])->roles()->attach(2);

        }
    }
}

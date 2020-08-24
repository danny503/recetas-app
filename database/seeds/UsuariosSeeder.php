<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Daniel',
            'email' => 'daniel@correo.com',
            'password' => bcrypt('12345678'),
            'url' => 'https://facebook.com',
        ]);
           
        $user2 = User::create([
            'name' => 'Isco',
            'email' => 'isco@correo.com',
            'password' => bcrypt('12345678'),
            'url' => 'https://facebook.com',
        ]);
    }
}

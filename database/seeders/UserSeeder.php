<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('usuarios')->insert([
            'name' => 'Geral',
            'email' => 'geral_366@hotmail.com',
            'password' => Hash::make('123'),
        ]);
    }
}

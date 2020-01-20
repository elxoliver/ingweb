<?php

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff::create([
            'first_name' => 'Javier',
            'last_name' => 'Hernandez',
            'email' => 'javier_administrador@gmail.com',
            'store_id' => 1,
            'active' => true,
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'address_id' => 1,
            'admin' => true
        ]);
    }
}

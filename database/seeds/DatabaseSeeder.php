<?php

use Illuminate\Database\Seeder;
use Database\Seeds\MaterialsTableSeeder;
use Database\Seeds\ServicesTableSeeder;
use Database\Seeds\TruckTypesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(MaterialsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(TruckTypesTableSeeder::class);
    }
}

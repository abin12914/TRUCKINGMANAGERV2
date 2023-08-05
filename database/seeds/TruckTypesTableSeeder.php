<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

class TruckTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('truck_types')->delete();

        \DB::table('truck_types')->insert([
            [
                'id' => 1,
                'name' => 'Nissan',
                'description' => 'Nissan 6 Wheel 100ft class',
                'generic_quantity' => 100,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 2,
                'name' => 'Lorry 6 Wheel',
                'description' => 'Lorry 6 Wheel 250ft class',
                'generic_quantity' => 250,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 3,
                'name' => 'Toress 10 Wheel',
                'description' => 'Toress 10 Wheel 500ft class',
                'generic_quantity' => 500,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 4,
                'name' => 'Toress 12 Wheel',
                'description' => 'Toress 12 Wheel 700ft Class',
                'generic_quantity' => 700,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 5,
                'name' => 'Toress 14 Wheel',
                'description' => 'Toress 14 Wheel 800ft Class',
                'generic_quantity' => 800,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 6,
                'name' => 'Toress 16 Wheel',
                'description' => 'Toress 16 Wheel 900ft Class',
                'generic_quantity' => 900,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 7,
                'name' => 'Toress 18 Wheel',
                'description' => 'Toress 18 Wheel 1000ft Class',
                'generic_quantity' => 1000,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 8,
                'name' => 'Toress 20 Wheel',
                'description' => 'Toress 20 Wheel 1100ft Class',
                'generic_quantity' => 1100,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
            [
                'id' => 9,
                'name' => 'Toress 22 Wheel',
                'description' => 'Toress 22 Wheel 1200ft Class',
                'generic_quantity' => 1200,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ],
        ]);
    }
}

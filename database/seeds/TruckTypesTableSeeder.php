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

        \DB::table('truck_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Toress 10 Wheel',
                'description' => 'Toress 10 Wheel 500 ft class',
                'generic_quantity' => 500,
                'status' => 1,
                'created_at' => '2019-11-09 03:39:34',
                'updated_at' => '2019-11-09 03:39:34',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Lorry 6 Wheel',
                'description' => 'Lorry 6 Wheel 250ft class',
                'generic_quantity' => 250,
                'status' => 1,
                'created_at' => '2019-11-09 03:40:07',
                'updated_at' => '2019-11-09 03:40:07',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Nissan',
                'description' => 'Nissan 6 Wheel 100ft class',
                'generic_quantity' => 100,
                'status' => 1,
                'created_at' => '2020-02-07 22:07:39',
                'updated_at' => '2020-02-07 22:07:39',
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Toress 12 Wheel',
                'description' => 'Toress 12 Wheel 700ft Class',
                'generic_quantity' => 700,
                'status' => 1,
                'created_at' => '2020-02-07 22:08:34',
                'updated_at' => '2020-02-07 22:08:34',
                'deleted_at' => NULL,
            ),
        ));


    }
}

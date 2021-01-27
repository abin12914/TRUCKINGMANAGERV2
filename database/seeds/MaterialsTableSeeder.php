<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

class MaterialsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('materials')->delete();

        \DB::table('materials')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => '6 MM',
                'alternate_name' => NULL,
                'description' => 'G',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => '10 MM',
                'alternate_name' => NULL,
                'description' => 'H',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => '12 MM',
                'alternate_name' => NULL,
                'description' => 'I',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'name' => '20 MM',
                'alternate_name' => NULL,
                'description' => 'J',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'name' => '40 MM',
                'alternate_name' => NULL,
                'description' => 'K',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'name' => '20 - 40 Mixture',
                'alternate_name' => NULL,
                'description' => 'L',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'M-Sand',
                'alternate_name' => NULL,
                'description' => 'D',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'P-Sand',
                'alternate_name' => NULL,
                'description' => 'E',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Dust',
                'alternate_name' => NULL,
                'description' => 'F',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'GSB',
                'alternate_name' => NULL,
                'description' => 'M',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'Rubble',
                'alternate_name' => NULL,
                'description' => 'A',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'Quarry Waste',
                'alternate_name' => NULL,
                'description' => 'C',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'Soil',
                'alternate_name' => NULL,
                'description' => 'B',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'Sand Washed Clay',
                'alternate_name' => NULL,
                'description' => 'N',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'Soil Clay ',
                'alternate_name' => NULL,
                'description' => 'O',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));


    }
}

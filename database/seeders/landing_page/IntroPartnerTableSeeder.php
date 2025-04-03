<?php
namespace Database\Seeders\landing_page;

use Illuminate\Database\Seeder;
use DB;

class IntroPartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('intro_partners')->insert([
            [ 'image'   => '1.png'  ] ,
            [ 'image'  => '3.png' ] ,
            [ 'image'   => '4.png' , ]
        ]);
    }
}

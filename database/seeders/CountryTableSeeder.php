<?php

namespace Database\Seeders;

use App\Services\CountryCities\CountryService;
use Illuminate\Database\Seeder;
use DB;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $flags = (new CountryService())->getFlags();
        DB::table('countries')->insert([
            [
                'name'          => json_encode(['ar' => 'السعودية', 'en' => 'Saudi Arabia'], JSON_UNESCAPED_UNICODE),
                'key'           => '966',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),
            ],
            [
                'name' => json_encode(['ar' => 'مصر', 'en' => 'Egypt'], JSON_UNESCAPED_UNICODE),
                'key'  => '20',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ],
            [
                'name' => json_encode(['ar' => 'الامارات', 'en' => 'UAE'], JSON_UNESCAPED_UNICODE),
                'key'  => '971',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ],
            [
                'name' => json_encode(['ar' => 'البحرين', 'en' => 'El-Bahrean'], JSON_UNESCAPED_UNICODE),
                'key'  => '973',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ],
            [
                'name' => json_encode(['ar' => 'قطر', 'en' => 'Qatar'], JSON_UNESCAPED_UNICODE),
                'key'  => '974',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ],
            [
                'name' => json_encode(['ar' => 'ليبيا', 'en' => 'Libya'], JSON_UNESCAPED_UNICODE),
                'key'  => '218',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ],
            [
                'name' => json_encode(['ar' => 'الكويت', 'en' => 'Kuwait'], JSON_UNESCAPED_UNICODE),
                'key'  => '965',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ],
            [
                'name' => json_encode(['ar' => 'عمان', 'en' => 'Oman'], JSON_UNESCAPED_UNICODE),
                'key'  => '968',
                'flag' => $flags[rand(0, (count($flags) - 1))],
                'created_at'    => \Carbon\Carbon::now()->subMonth(rand(0, 6)),

            ]
        ]);
    }
}

<?php

namespace Database\Seeders\public_sections;

use Illuminate\Database\Seeder;
use App\Enums\ComplaintTypesEnum;
use App\Models\PublicSections\Complaint;
use Modules\Users\App\Models\User;

class ComplaintTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 30; $i++) {
            # code...
            Complaint::create([
                'user_name' => 'ahmed abdullah',
                'phone' => '001332422442',
                'email' => 'aa926626@gmail.com',
                'complaintable_id' => rand(1, 20),
                'complaintable_type' => User::class,
                'type' => rand(ComplaintTypesEnum::Complaint->value, ComplaintTypesEnum::Enquiry->value),
                'complaint' => 'معامله سيئه جدا جدا',
            ]);
        }
    }
}

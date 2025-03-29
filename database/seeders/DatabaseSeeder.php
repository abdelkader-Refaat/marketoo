<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\landing_page\IntroFqsCategoryTableSeeder;
use Database\Seeders\landing_page\IntroFqsTableSeeder;
use Database\Seeders\landing_page\IntroHowWorkTableSeeder;
use Database\Seeders\landing_page\IntroPartnerTableSeeder;
use Database\Seeders\landing_page\IntroServiceTableSeeder;
use Database\Seeders\landing_page\IntroSliderTableSeeder;
use Database\Seeders\landing_page\IntroSocialTableSeeder;
use Database\Seeders\public_sections\ComplaintTableSeeder;
use Database\Seeders\public_sections\FqsTableSeeder;
use Database\Seeders\public_sections\ImageTableSeeder;
use Database\Seeders\public_sections\IntroTableSeeder;
use Database\Seeders\public_settings\PermissionTableSeeder;
use Database\Seeders\public_settings\RolesTableSeeder;
use Database\Seeders\public_settings\SettingSeeder;
use Database\Seeders\public_settings\SmsTableSeeder;
use Database\Seeders\public_settings\SocialTableSeeder;
use Illuminate\Database\Seeder;
use Modules\Admins\Database\Seeders\AdminsDatabaseSeeder;
use Modules\Posts\Database\Seeders\PostsDatabaseSeeder;
use Modules\Users\Database\Seeders\UsersDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SettingSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(AdminsDatabaseSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);

        $this->call(UsersDatabaseSeeder::class);
        $this->call(PostsDatabaseSeeder::class);
        $this->call(IntroHowWorkTableSeeder::class);
        $this->call(IntroSliderTableSeeder::class);
        $this->call(IntroServiceTableSeeder::class);
        $this->call(IntroFqsCategoryTableSeeder::class);
        $this->call(IntroFqsTableSeeder::class);
        $this->call(IntroPartnerTableSeeder::class);
        $this->call(IntroSocialTableSeeder::class);
        $this->call(SocialTableSeeder::class);
        $this->call(ComplaintTableSeeder::class);
        $this->call(FqsTableSeeder::class);
        $this->call(IntroTableSeeder::class);
        $this->call(ImageTableSeeder::class);
        $this->call(SmsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
    }
}

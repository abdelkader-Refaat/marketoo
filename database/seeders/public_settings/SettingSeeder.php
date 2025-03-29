<?php
namespace Database\Seeders\public_settings;

use Illuminate\Database\Seeder;
use App\Services\Core\SettingService;
use Illuminate\Support\Facades\Cache;
use App\Models\PublicSettings\SiteSetting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('settings');

        $data = [
            ['key' => 'is_production', 'value' => 0],
            ['key' => 'name_ar', 'value' => 'اوامر الشبكه'],
            ['key' => 'name_en', 'value' => 'Awamer Elshabka'],
            ['key' => 'email', 'value' => 'aait@gmail.com'],
            ['key' => 'phone', 'value' => '+966555184424'],
            ['key' => 'whatsapp', 'value' => '+966555184424'],
            ['key' => 'logo', 'value' => 'logo.png'],
            ['key' => 'fav_icon', 'value' => 'fav_icon.png'],
            ['key' => 'login_background', 'value' => 'login_background.png'],
            ['key' => 'no_data_icon', 'value' => 'fav.png'],
            ['key' => 'default_user', 'value' => 'default.png'],
            ['key' => 'intro_email', 'value' => 'email@gmail.com'],
            ['key' => 'intro_phone', 'value' => '+966555184424'],
            ['key' => 'intro_address', 'value' => 'الرياض - السعودية'],
            ['key' => 'intro_logo', 'value' => 'intro_logo.png'],
            ['key' => 'intro_loader', 'value' => 'intro_loader.png'],
            ['key' => 'about_image_2', 'value' => 'about_image_2.png'],
            ['key' => 'about_image_1', 'value' => 'about_image_1.png'],
            ['key' => 'intro_name_ar', 'value' => 'اوامر الشبكة'],
            ['key' => 'intro_name_en', 'value' => 'Awamer elshabka'],
            ['key' => 'intro_meta_description', 'value' => 'موقع تعريفي خاص ب اوامر الشبكة'],
            ['key' => 'intro_meta_keywords', 'value' => 'موقع تعريفي خاص ب اوامر الشبكة'],
            ['key' => 'intro_about_ar', 'value' => 'هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساح'],
            ['key' => 'intro_about_en', 'value' => 'This text is an example of text that can be replaced in the same space. This text was generated from the Arabic text generator, where you can generate such text or many other texts. This text is an example of text that can be replaced in the same space. This text is an example of text It can be replaced in the same space. This text was generated from the Arabic text generator, where you can generate such text or many other texts. This text is an example of a text that can be replaced in the same space.'],
            ['key' => 'services_text_ar', 'value' => 'من خلال بناء منتج بديهي يحاكي ويسهل تنفيذ الخدمة العامة ، كان الجواب البسيط هو تزويد المستخدمين بثلاثة أشياء'],
            ['key' => 'services_text_en', 'value' => 'By building an intuitive product that simulates and facilitates the implementation of public service, the simple answer has been to provide users with three things'],
            ['key' => 'how_work_text_ar', 'value' => 'من خلال بناء منتج بديهي يحاكي ويسهل تنفيذ الخدمة العامة ، كان الجواب البسيط هو تزويد المستخدمين بثلاثة أشياء'],
            ['key' => 'how_work_text_en', 'value' => 'By building an intuitive product that simulates and facilitates the implementation of public service, the simple answer has been to provide users with three things'],
            ['key' => 'fqs_text_ar', 'value' => 'من خلال بناء منتج بديهي يحاكي ويسهل تنفيذ الخدمة العامة ، كان الجواب البسيط هو تزويد المستخدمين بثلاثة أشياء'],
            ['key' => 'fqs_text_en', 'value' => 'By building an intuitive product that simulates and facilitates the implementation of public service, the simple answer has been to provide users with three things'],
            ['key' => 'parteners_text_ar', 'value' => 'من خلال بناء منتج بديهي يحاكي ويسهل تنفيذ الخدمة العامة ، كان الجواب البسيط هو تزويد المستخدمين بثلاثة أشياء'],
            ['key' => 'parteners_text_en', 'value' => 'By building an intuitive product that simulates and facilitates the implementation of public service, the simple answer has been to provide users with three things'],
            ['key' => 'contact_text_ar', 'value' => 'من خلال بناء منتج بديهي يحاكي ويسهل تنفيذ الخدمة العامة ، كان الجواب البسيط هو تزويد المستخدمين بثلاثة أشياء'],
            ['key' => 'contact_text_en', 'value' => 'By building an intuitive product that simulates and facilitates the implementation of public service, the simple answer has been to provide users with three things'],
            ['key' => 'color', 'value' => '#10163a'],
            ['key' => 'buttons_color', 'value' => '#7367F0'],
            ['key' => 'hover_color', 'value' => '#262c49'],

            ['key' => 'smtp_user_name', 'value' => 'smtp_user_name'],
            ['key' => 'smtp_password', 'value' => 'smtp_password'],
            ['key' => 'smtp_mail_from', 'value' => 'smtp_mail_from'],
            ['key' => 'smtp_sender_name', 'value' => 'smtp_sender_name'],
            ['key' => 'smtp_port', 'value' => '80'],
            ['key' => 'smtp_host', 'value' => 'send.smtp.com'],
            ['key' => 'smtp_encryption', 'value' => 'LTS'],

            ['key' => 'firebase_key', 'value' => 'AAAAVYoWgDU:APA91bEU9m3M7z5TeNAlKqwl2sI5XU78yNRDCNPt95M2RDjfZG9O5ZGxrH_wcqIClEDY3TWgyMOp9vH56O5ilbm2vYp-8tIN_8dGvnbtea4s5hMlXYyCQZR2h0kM07l3pXB9iiZbgz_q'],
            ['key' => 'firebase_sender_id', 'value' => '662557294717'],

            ['key' => 'google_places', 'value' => 'AIzaSyAXV7nrpIKpuqyaNWNQYr3IP86_rJgcHWc'],
            ['key' => 'google_analytics', 'value' => 'google_analytics'],
            ['key' => 'commission_from_providers', 'value' => 10],
            ['key' => 'vat_ratio', 'value' => 15],
            ['key' => 'days_to_make_settlement_request', 'value' => 5],
            ['key' => 'live_chat', 'value' => '<iframe src="https://chat.socialintents.com/c/yoururl" width="480" height="540" frameborder="0"></iframe>'],
        ];

        SiteSetting::insert($data);

        Cache::rememberForever('settings', function () {
            return SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        });
    }
}

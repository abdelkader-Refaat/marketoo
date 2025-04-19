<?php

namespace App\Services\Core;

use App\Models\Core\Page;
use App\Models\PublicSettings\SiteSetting;
use App\Traits\ReportTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

class SettingService
{
    public function edit($request): array
    {
        Cache::forget('settings');

        foreach ($request->all() as $key => $val) {
            if (is_file($val)) {
                if (str_contains($val->getClientmimeType(), 'image')) {
                    $manager = ImageManager::gd(autoOrientation: false);
                    $img = $manager->read($val);
                    if ($key == 'default_user') {
                        $dir = 'storage/images/users';
                        $this->createDirIfNotExist($dir);
                        $name = 'default.webp';
                        $thumbsPath = $dir.'/default.webp';
                    } else {
                        if ($key == 'no_data') {
                            $dir = 'storage/images';
                            $this->createDirIfNotExist($dir);
                            $name = 'no_data.png';
                            $thumbsPath = $dir.'/no_data.png';
                        } else {
                            $img->orient();
                            $name = time().rand(1000000, 9999999).'.webp';
                            $dir = 'storage/images/settings';
                            $this->createDirIfNotExist($dir);
                            $thumbsPath = $dir.'/'.$name;
                        }
                    }
                    SiteSetting::where('key', $key)->update(['value' => $name]);
                    $img->save($thumbsPath, 90, 'webp');
                } else {
                    return ['key' => 'danger', 'msg' => __('admin.not_valid_image')];
                }
            } else {
                if ($val) {
                    SiteSetting::where('key', $key)->update(['value' => $val]);
                }
            }
        }
        if ($request->is_production) {
            SiteSetting::where('key', 'is_production')->update(['value' => 1]);
        } else {
            SiteSetting::where('key', 'is_production')->update(['value' => 0]);
        }

        Cache::rememberForever('settings', function () {
            return self::appInformations(SiteSetting::pluck('value', 'key'));
        });

        ReportTrait::addToLog('تعديل الاعدادت');

        return ['key' => 'success', 'msg' => __('admin.saved_successfully')];
    }

    protected function createDirIfNotExist($path)
    {
        if (! File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }

    public static function appInformations($app_info)
    {
        $data = [
            'is_production' => $app_info['is_production'],
            'name_ar' => $app_info['name_ar'],
            'name_en' => $app_info['name_en'],
            'email' => $app_info['email'],
            'phone' => $app_info['phone'],
            'whatsapp' => $app_info['whatsapp'],

            'logo' => ('/storage/images/settings/'.$app_info['logo']),
            'fav_icon' => ('/storage/images/settings/'.$app_info['fav_icon']),
            'no_data_icon' => $app_info['no_data_icon'],
            'default_user' => ('/storage/images/users/'.$app_info['default_user']),
            'login_background' => ('/storage/images/settings/'.$app_info['login_background']),
            'intro_logo' => ('/storage/images/settings/'.$app_info['intro_logo']),
            'intro_loader' => ('/storage/images/settings/'.$app_info['intro_loader']),

            'intro_name' => $app_info['intro_name_'.lang()],
            'intro_name_ar' => $app_info['intro_name_ar'],
            'intro_name_en' => $app_info['intro_name_en'],

            'intro_about' => $app_info['intro_about_'.lang()],
            'intro_about_ar' => $app_info['intro_about_ar'],
            'intro_about_en' => $app_info['intro_about_en'],

            'about_image_2' => ('/storage/images/settings/'.$app_info['about_image_2']),
            'about_image_1' => ('/storage/images/settings/'.$app_info['about_image_1']),
            'services_text_ar' => $app_info['services_text_ar'],
            'services_text_en' => $app_info['services_text_en'],
            'services_text' => $app_info['services_text_'.lang()],
            'how_work_text_ar' => $app_info['how_work_text_ar'],
            'how_work_text_en' => $app_info['how_work_text_en'],
            'how_work_text' => $app_info['how_work_text_'.lang()],
            'fqs_text_ar' => $app_info['fqs_text_ar'],
            'fqs_text_en' => $app_info['fqs_text_en'],
            'fqs_text' => $app_info['fqs_text_'.lang()],
            'parteners_text_ar' => $app_info['parteners_text_ar'],
            'parteners_text_en' => $app_info['parteners_text_en'],
            'parteners_text' => $app_info['parteners_text_'.lang()],
            'contact_text_ar' => $app_info['contact_text_ar'],
            'contact_text_en' => $app_info['contact_text_en'],
            'contact_text' => $app_info['contact_text_'.lang()],
            'intro_email' => $app_info['intro_email'],
            'intro_phone' => $app_info['intro_phone'],
            'intro_address' => $app_info['intro_address'],
            'color' => $app_info['color'],
            'buttons_color' => $app_info['buttons_color'],
            'hover_color' => $app_info['hover_color'],
            'intro_meta_description' => $app_info['intro_meta_description'],
            'intro_meta_keywords' => $app_info['intro_meta_keywords'],

            'smtp_user_name' => $app_info['smtp_user_name'],
            'smtp_password' => $app_info['smtp_password'],
            'smtp_mail_from' => $app_info['smtp_mail_from'],
            'smtp_sender_name' => $app_info['smtp_sender_name'],
            'smtp_port' => $app_info['smtp_port'],
            'smtp_host' => $app_info['smtp_host'],
            'smtp_encryption' => $app_info['smtp_encryption'],

            'firebase_key' => $app_info['firebase_key'],
            'firebase_sender_id' => $app_info['firebase_sender_id'],

            'google_places' => $app_info['google_places'],
            'google_analytics' => $app_info['google_analytics'],
            'live_chat' => $app_info['live_chat'],
            'vat_ratio' => $app_info['vat_ratio'],
        ];

        return $data;
    }

    public function getFixedPage($slug): array
    {
        $content = Page::whereSlug($slug)->first()?->content;

        return ['key' => 'success', 'content' => $content, 'msg' => __('apis.success')];
    }

    public function getAppMenu($model): array
    {
        $rows = $model::latest()->get();

        return ['key' => 'success', 'rows' => $rows, 'msg' => __('apis.success')];
    }

    public function get(): array
    {
        return Cache::rememberForever('settings', function () {
            return self::appInformations(SiteSetting::pluck('value', 'key'));
        });
    }

    public function switchLang($request, $user): array
    {
        if ($user) {
            $user->update(['lang' => $request->lang]);
        }
        App::setLocale($request->lang);

        return ['key' => 'success', 'msg' => __('apis.updated')];
    }
}

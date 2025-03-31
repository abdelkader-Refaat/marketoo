<?php

namespace App\Traits;

use Modules\Admins\App\Models\Admin;
use Modules\Users\App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\PublicSections\Fqs;
use App\Models\PublicSettings\Role;
use App\Models\LandingPage\IntroFqs;
use App\Models\PublicSections\Image;
use App\Models\PublicSections\Intro;
use App\Models\PublicSettings\Social;
use App\Models\LandingPage\IntroSlider;
use App\Models\LandingPage\IntroSocial;
use App\Models\LandingPage\IntroHowWork;
use App\Models\LandingPage\IntroPartner;
use App\Models\LandingPage\IntroService;
use App\Models\PublicSections\Complaint;
use App\Models\LandingPage\IntroMessages;
use App\Models\PublicSettings\LogActivity;
use App\Models\LandingPage\IntroFqsCategory;

trait MenuTrait
{
    public function home()
    {
        $menu = [
            [
                'name' => __('routes.admins.index'),
                'count' => Admin::where('type', '!=', 'super_admin')->count(),
                'icon' => 'icon-users',
                'url' => url('admin/admins'),
            ],
            [
                'name' => __('routes.users.index'),
                'count' => User::count(),
                'icon' => 'icon-users',
                'url' => url('admin/clients'),
            ],
            [
                'name' => __('routes.socials.index'),
                'count' => Social::count(),
                'icon' => 'icon-thumbs-up',
                'url' => url('admin/socials'),
            ],
            [
                'name' => __('routes.complaints_and_proposals.index'),
                'count' => Complaint::count(),
                'icon' => 'icon-list',
                'url' => url('admin/all-complaints'),
            ],
            [
                'name' => __('routes.reports.index'),
                'count' => LogActivity::count(),
                'icon' => 'icon-list',
                'url' => url('admin/reports'),
            ],
            [
                'name' => __('routes.countries.index'),
                'count' => Country::count(),
                'icon' => 'icon-list',
                'url' => url('admin/countries'),
            ],
            [
                'name' => __('routes.cities.index'),
                'count' => City::count(),
                'icon' => 'icon-list',
                'url' => url('admin/cities'),
            ],
            [
                'name' => __("routes.questions_sections.index"),
                'count' => Fqs::count(),
                'icon' => 'icon-list',
                'url' => url('admin/fqs'),
            ],
            [
                'name' => __('routes.definition_pages.index'),
                'count' => Intro::count(),
                'icon' => 'icon-list',
                'url' => url('admin/intros'),
            ],
            [
                'name' => __('routes.advertising_banners.index'),
                'count' => Image::count(),
                'icon' => 'icon-list',
                'url' => url('admin/images'),
            ],
            // [
            //     'name'  => __('routes.message_packages.index'),
            //     'count' =>  SMS::count(),
            //     'icon'  => 'icon-list',
            //     'url'   => url('admin/sms'),
            // ],
            [
                'name' => __('routes.roles.index'),
                'count' => Role::count(),
                'icon' => 'icon-eye',
                'url' => url('admin/roles'),
            ],
        ];

        return $menu;
    }

    public function introSiteCards()
    {
        $menu = [
            [
                'name' => __('routes.intro_slider.index'),
                'count' => IntroSlider::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introsliders'),
            ],
            [
                'name' => __('routes.our_services.index'),
                'count' => IntroService::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introservices'),
            ],
            [
                'name' => __('routes.common_questions_sections.index'),
                'count' => IntroFqsCategory::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introfqscategories'),
            ],
            [
                'name' => __('routes.questions_sections.index'),
                'count' => IntroFqs::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introfqs'),
            ],
            [
                'name' => __('routes.success_Partners.index'),
                'count' => IntroPartner::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introparteners'),
            ],
            [
                'name' => __('routes.customer_messages.index'),
                'count' => IntroMessages::count(),
                'icon' => 'icon-users',
                'url' => url('admin/intromessages'),
            ],
            [
                'name' => __('routes.socials.index'),
                'count' => IntroSocial::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introsocials'),
            ],
            [
                'name' => __('routes.how_the_site_works.index'),
                'count' => IntroHowWork::count(),
                'icon' => 'icon-users',
                'url' => url('admin/introhowworks'),
            ],
        ];
        return $menu;
    }
}

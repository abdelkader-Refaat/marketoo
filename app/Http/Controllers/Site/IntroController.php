<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Services\Core\SettingService;
use App\Models\LandingPage\IntroSlider;
use App\Models\LandingPage\IntroSocial;
use App\Models\LandingPage\IntroHowWork;
use App\Models\LandingPage\IntroService;
use App\Models\LandingPage\IntroMessages;
use App\Models\LandingPage\IntroPartner;
use App\Models\PublicSettings\SiteSetting;
use App\Models\LandingPage\IntroFqsCategory;
use App\Http\Requests\Site\sendMessageRequest;

class IntroController extends Controller
{
    public function index()
    {
        view()->share([
            'services'        => IntroService::get() ,
            'sliders'         => IntroSlider::get() ,
            'fqsCategories'   => IntroFqsCategory::get() ,
            'parteners'       => IntroPartner::get() ,
            'howWorks'        => IntroHowWork::get() ,
            'socials'         => IntroSocial::get() ,
            'settings'        => SettingService::appInformations(SiteSetting::pluck('value', 'key')) ,
        ]);
        return view('intro_site.index');
    }

    public function privacyPolicy(){
        view()->share([
            'socials'         => IntroSocial::get() ,
            'settings'        => SettingService::appInformations(SiteSetting::pluck('value', 'key')) ,
        ]);
        return view('intro_site.privacy');
    }


    public function sendMessage(sendMessageRequest $request)
    {
        IntroMessages::create($request->validated());
        return response()->json(['status' => 'done' , 'message' => __('intro_site.message_sent') ]) ;
    }

    /***************** change lang  *****************/
    public function SetLanguage($lang)
    {
        if ( in_array( $lang, [ 'ar', 'en' ] ) ) {

            if ( session() -> has( 'lang' ) )
                session() -> forget( 'lang' );

            session() -> put( 'lang', $lang );

        } else {
            if ( session() -> has( 'lang' ) )
                session() -> forget( 'lang' );

            session() -> put( 'lang', 'ar' );
        }

//        dd(session( 'lang' ));
        return back();
    }

    public function deleteAccount() {
        view()->share([
            'socials'         => IntroSocial::get() ,
            'settings'        => SettingService::appInformations(SiteSetting::pluck('value', 'key')) ,
        ]);
        return view('intro_site.deleteAccount');
    }
}

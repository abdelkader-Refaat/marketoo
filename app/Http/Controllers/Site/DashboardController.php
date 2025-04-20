<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\General\Settings\IntroResource;
use App\Models\LandingPage\IntroSlider;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //    public function dashboard()
    //    {
    //        return Inertia::render('dashboard');
    //    }
    public function dashboard()
    {
        return Inertia::render('dashboard', [
            'introSliders' => IntroResource::collection(IntroSlider::all())->toArray(request()),
            'breadcrumbs' => [
                ['title' => 'dashboard', 'href' => route('site.dashboard')],
            ],
        ]);
    }
}

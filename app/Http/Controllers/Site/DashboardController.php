<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\LandingPage\IntroSlider;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //    public function dashboard()
    //    {
    //        return Inertia::render('dashboard');
    //    }
    // app/Http/Controllers/DashboardController.php
    public function dashboard()
    {
        return Inertia::render('dashboard', [
            'introSliders' => IntroSlider::all()->map(function ($slider) {
                return [
                    'id' => $slider->id,
                    'title' => $slider->title,
                    'description' => $slider->description,
                    'image' => $slider->image,
                ];
            }),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'href' => route('site.dashboard')],
            ],
        ]);
    }
}

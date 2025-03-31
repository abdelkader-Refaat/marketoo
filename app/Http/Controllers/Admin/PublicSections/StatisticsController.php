<?php

namespace App\Http\Controllers\Admin\PublicSections;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Country;
use App\Traits\MenuTrait;
use App\Http\Controllers\Controller;
use Modules\Users\App\Models\User;

class StatisticsController extends Controller
{
    use MenuTrait;

    public function index()
    {
        $countryArray = $this->chartData(new Country);
        $cityArray = $this->chartData(new City);
        $activeUsers = User::where(['active' => true])->count();
        $notActiveUsers = User::where(['active' => false])->count();
        $menus = $this->home();
        $introSiteCards = $this->introSiteCards();
        $colores = ['info', 'danger', 'warning', 'success', 'primary'];

        return view('admin.statistics.index', get_defined_vars());
    }


    public function chartData($model)
    {
        $users = $model::select('id', 'created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });

        $usermcount = [];
        $userArr = [];

        foreach ($users as $key => $value) {
            $usermcount[(int) $key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($usermcount[$i])) {
                $userArr[] = $usermcount[$i];
            } else {
                $userArr[] = 0;
            }
        }

        return $userArr;
    }
}

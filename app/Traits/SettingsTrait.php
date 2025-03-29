<?php

namespace App\Traits;

use App\Models\SiteSetting;

trait SettingsTrait
{
  public function getKeyFromSetting($keySetting, $hasLang = false)
  {
    $key = ($hasLang ? $keySetting . '_' . lang() : $keySetting);
    return SiteSetting::where(['key' => $key])->first()?->value;
  }
}

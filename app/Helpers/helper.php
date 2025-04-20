<?php

use App\Models\PublicSettings\SiteSetting;
use App\Models\Seo;
use Illuminate\Support\Facades\Http;

function seo($key)
{
    return Seo::where('key', $key)->first();
}

function appInformations()
{
    $result = SiteSetting::pluck('value', 'key');

    return $result;
}

function convert2english($string)
{
    $newNumbers = range(0, 9);
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $string = str_replace($arabic, $newNumbers, $string);

    return $string;
}

function fixPhone($string = null)
{
    if (! $string) {
        return null;
    }

    $result = convert2english($string);
    $result = ltrim($result, '00');
    $result = ltrim($result, '0');
    $result = ltrim($result, '+');

    return $result;
}

function getYoutubeVideoId($youtubeUrl)
{
    preg_match(
        "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
        $youtubeUrl,
        $videoId
    );

    return $youtubeVideoId = isset($videoId[1]) ? $videoId[1] : '';
}

function lang(): string
{
    return App()->getLocale();
}

function generateRandomCode(): int
{
    return random_int(1111, 4444);
}

if (! function_exists('languages')) {
    function languages()
    {
        return ['ar', 'en'];
    }
}

if (! function_exists('defaultLang')) {
    function defaultLang()
    {
        return 'ar';
    }
}

if (! function_exists('calculateDistance')) {
    function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2): array
    {
        if ($latitude1 == $latitude2 && $longitude1 == $longitude2) {
            return ['value' => '0 ', 'text' => '0 m', 'duration' => '0 min', 'start_address' => ''];
        }
        $distance = Http::get('https://maps.googleapis.com/maps/api/directions/json?origin='.
            $latitude1.','.$longitude1.'&destination='.$latitude2.','.$longitude2.'&key='.
            config('app.google_api_key'));
        if ($distance->object()->routes != []) {
            return [
                'value' => $distance->object()->routes[0]->legs[0]->distance->text,
                'text' => $distance->object()->routes[0]->legs[0]->distance->text,
                'duration' => $distance->object()->routes[0]->legs[0]->duration->text,
                'start_address' => explode(',', $distance->object()->routes[0]->legs[0]->start_address)[0],
            ];
        }

        return ['value' => '0 ', 'text' => '0 m', 'duration' => '0 min', 'start_address' => ''];
    }
}

if (! function_exists('getDeliveryPrice')) {
    function getDeliveryPrice($user_lat, $user_lng, $provider_lat, $provider_lng): array
    {
        $distance = floatval(preg_replace('/[^0-9.]/', '',
            calculateDistance($user_lat, $user_lng, $provider_lat, $provider_lng)['value']));

        return ['price' => $distance * SiteSetting::where('key', 'price_per_kilometer')->first()->value];
    }
}

if (! function_exists('newNumberFormat')) {
    function newNumberFormat($number)
    {
        $formatter = new NumberFormatter('en', NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $formattedNumber = $formatter->format($number);

        return $formattedNumber;
    }

    if (! function_exists('timeAgo')) {
        function timeAgo($timestamp)
        {
            $time = \Carbon\Carbon::parse($timestamp);

            $diffInSeconds = $time->diffInSeconds();
            $diffInMinutes = $time->diffInMinutes();
            $diffInHours = $time->diffInHours();
            $diffInDays = $time->diffInDays();
            $diffInWeeks = $time->diffInWeeks();
            $diffInMonths = $time->diffInMonths();
            $diffInYears = $time->diffInYears();

            return match (true) {
                $diffInSeconds < 60 => formatArabicTime($diffInSeconds, 'second'),
                $diffInMinutes < 60 => formatArabicTime($diffInMinutes, 'minute'),
                $diffInHours < 24 => formatArabicTime($diffInHours, 'hour'),
                $diffInDays < 7 => formatArabicTime($diffInDays, 'day'),
                $diffInWeeks < 4 => formatArabicTime($diffInWeeks, 'week'),
                $diffInMonths < 12 => formatArabicTime($diffInMonths, 'month'),
                default => formatArabicTime($diffInYears, 'year'),
            };
        }
    }

    /**
     * Format time in Arabic based on the value and type.
     */
    function formatArabicTime($value, $type)
    {
        $unit = arabicUnit($value, $type);

        return $value == 2
            ? __('apis.time_ago.just_unit', ['unit' => $unit])
            : __('apis.time_ago.full', ['value' => $value, 'unit' => $unit]);
    }

    /**
     * Determine the correct Arabic unit based on value.
     */
    function arabicUnit($value, $type)
    {
        return match ($type) {
            'second' => match (true) {
                $value == 1 => 'ثانية',
                $value == 2 => 'ثانيتان',
                $value >= 3 && $value <= 10 => 'ثوانٍ',
                default => 'ثانية',
            },
            'minute' => match (true) {
                $value == 1 => 'دقيقة',
                $value == 2 => 'دقيقتان',
                $value >= 3 && $value <= 10 => 'دقائق',
                default => 'دقيقة',
            },
            'hour' => match (true) {
                $value == 1 => 'ساعة',
                $value == 2 => 'ساعتان',
                $value >= 3 && $value <= 10 => 'ساعات',
                default => 'ساعة',
            },
            'day' => match (true) {
                $value == 1 => 'يوم',
                $value == 2 => 'يومان',
                $value >= 3 && $value <= 10 => 'أيام',
                default => 'يوم',
            },
            'week' => match (true) {
                $value == 1 => 'أسبوع',
                $value == 2 => 'أسبوعان',
                $value >= 3 && $value <= 10 => 'أسابيع',
                default => 'أسبوع',
            },
            'month' => match (true) {
                $value == 1 => 'شهر',
                $value == 2 => 'شهران',
                $value >= 3 && $value <= 10 => 'أشهر',
                default => 'شهر',
            },
            'year' => match (true) {
                $value == 1 => 'سنة',
                $value == 2 => 'سنتان',
                $value >= 3 && $value <= 10 => 'سنوات',
                default => 'سنة',
            },
            default => '',
        };
    }
}

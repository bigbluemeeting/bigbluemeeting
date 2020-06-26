<?php


namespace App\Helpers;





use App\Files;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Self_;
use Whoops\Exception\ErrorException;

class Helper
{




     public static function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public static function get_local_time()
    {

             $ip = file_get_contents("http://ipecho.net/plain");
             $url = 'http://ip-api.com/json/'.$ip;
             $tz = file_get_contents($url);
             $tz = json_decode($tz,true)['timezone'];
             return $tz;

    }






    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1000);
            $suffixes = array(' B', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }


}
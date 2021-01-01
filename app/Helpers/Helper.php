<?php


namespace App\Helpers;

use BigBlueButton\BigBlueButton;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;



class Helper
{




     public static function paginate($items, $perPage = null, $page = null, $options = [])
    {
        try{
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            $items = $items instanceof Collection ? $items : Collection::make($items);
            return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }


    public static function get_user_local_timezone()
    {
        // refactor to use
        // https://5balloons.info/dealing-with-user-timezone-in-laravel/
        return "Etc/UTC";
    }






    public static function formatBytes($size, $precision = 2)
    {
        try
        {
            if ($size > 0) {
                $size = (int) $size;
                $base = log($size) / log(1000);
                $suffixes = array(' B', ' KB', ' MB', ' GB', ' TB');

                return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
            } else {
                return $size;
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }


}

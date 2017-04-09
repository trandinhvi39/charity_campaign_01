<?php

use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('distanceGeoPoints')) {
    function distanceGeoPoints ($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 3958.75;
        $dLat = deg2rad($lat2-$lat1);
        $dLng = deg2rad($lng2-$lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
           cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
           sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $dist = $earthRadius * $c;
        // from miles
        $meterConversion = 1609;
        $geopointDistance = $dist * $meterConversion;
        return $geopointDistance;
    }
}

if (!function_exists('paginateCollection')) {
    function paginateCollection($collection, $perPage, $pageName = 'page', $fragment = null)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $query,
                'fragment' => $fragment
            ]
        );

        return $paginator;
    }
}

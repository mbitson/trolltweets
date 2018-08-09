<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FilterableController extends Controller
{
    /**
     * @param $key
     * @return string
     */
    public function determineCacheKey($key, $excluded = [])
    {
        $suffix = $this->determineCacheSuffix($excluded);

        if($suffix){
            $key .= $suffix;
        }

        return $key;
    }

    /**
     * @return bool|string
     */
    public function determineCacheSuffix($excluded = [])
    {
        $suffix = '_';
        if(is_array(Session::get('filters')) && !empty(Session::get('filters'))){
            foreach(Session::get('filters') as $filterType => $filters)
            {
                if(in_array($filterType, $excluded)) continue;
                foreach($filters as $filter)
                {
                    $suffix .= $filterType.'-'.$filter.'_';
                }
            }
        }

        if($suffix === '_')
        {
            return false;
        }

        return rtrim($suffix, '_');
    }
}

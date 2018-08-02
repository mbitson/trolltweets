<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HashtagController extends Controller
{
    public function top($limit){
        $cacheKey = 'top-'.$limit.'-hashtags';

        if(!$hashtags = Cache::get($cacheKey)){
            $hashtags = Hashtag::select('hashtag')->groupBy('hashtag')->orderByRaw('COUNT(*) DESC')->limit($limit)->get();
            Cache::forever($cacheKey, $hashtags);
        }

        return response()->json($hashtags);
    }
}

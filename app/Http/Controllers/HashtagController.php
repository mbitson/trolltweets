<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HashtagController extends Controller
{
    public function top($limit){
        $cacheKey = 'top-'.$limit.'-hashtags';

        if(!$hashtags = Cache::get($cacheKey)){
            $hashtags = Hashtag::select(DB::raw('hashtag, COUNT(hashtag) AS count'))->groupBy('hashtag')->orderByRaw('COUNT(*) DESC')->limit($limit)->get();
            Cache::forever($cacheKey, $hashtags);
        }

        return response()->json($hashtags);
    }

    public function summary(){
        $cacheKeyCategorized = 'hashtags-by-account-type';
        $cacheKey = 'hashtags-by-month';

        if(!$hashtags = Cache::get($cacheKey)){
            $hashtags = Hashtag::select(DB::raw('MONTH(used_on) month, YEAR(used_on) year, COUNT(*) as count'))->groupBy(['year', 'month'])->get();
            Cache::forever($cacheKey, $hashtags);
        }

        if(!$hashtagsByCategory = Cache::get($cacheKeyCategorized)){
            $hashtagsByCategory = Hashtag::select(DB::raw('tweets.account_category, MONTH(used_on) month, YEAR(used_on) year, COUNT(*) as count'))
                ->join('tweets', 'hashtags.tweet_id', '=', 'tweets.id')
                ->groupBy(['tweets.account_category', 'year', 'month'])
                ->get();
            Cache::forever($cacheKeyCategorized, $hashtagsByCategory);
        }

        return response()->json(['by_month'=>$hashtags, 'by_category'=>$hashtagsByCategory]);
    }

    public function count(){
        $cacheKey = 'hashtags-count';
        if(!$count = Cache::get($cacheKey)){
            $count = Hashtag::count();
            Cache::forever($cacheKey, $count);
        }
        return response()->json($count);
    }
}

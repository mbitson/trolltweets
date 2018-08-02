<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TweetController extends Controller
{
    public function top($limit){
        $cacheKey = 'top-'.$limit.'-tweets';

        if(!$tweets = Cache::get($cacheKey)){
            $tweets = Tweet::select(DB::raw('author, COUNT(author) AS count'))->groupBy('author')->orderByRaw('COUNT(*) DESC')->limit($limit)->get();
            Cache::forever($cacheKey, $tweets);
        }

        return response()->json($tweets);
    }

    public function summary(){
        $cacheKeyCategorized = 'tweets-by-account-type';
        $cacheKey = 'tweets-by-month';

        if(!$tweets = Cache::get($cacheKey)){
            $tweets = Tweet::select(DB::raw('MONTH(publish_date) month, YEAR(publish_date) year, COUNT(*) as count'))->groupBy(['year', 'month'])->get();
            Cache::forever($cacheKey, $tweets);
        }

        if(!$tweetsByCategory = Cache::get($cacheKeyCategorized)){
            $tweetsByCategory = Tweet::select(DB::raw('account_category, MONTH(publish_date) month, YEAR(publish_date) year, COUNT(*) as count'))
                ->groupBy(['account_category', 'year', 'month'])
                ->get();
            Cache::forever($cacheKeyCategorized, $tweetsByCategory);
        }

        return response()->json(['by_month'=>$tweets, 'by_category'=>$tweetsByCategory]);
    }

    public function count(){
        $cacheKey = 'tweets-count';
        if(!$count = Cache::get($cacheKey)){
            $count = Tweet::count();
            Cache::forever($cacheKey, $count);
        }
        return response()->json($count);
    }
}

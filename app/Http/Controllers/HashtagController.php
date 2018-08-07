<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HashtagController extends FilterableController
{

    /**
     * @param $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function top($limit)
    {
        $cacheKey = $this->determineCacheKey('top-'.$limit.'-hashtags', ['hashtag']);

        if(!$hashtags = Cache::get($cacheKey))
        {
            $hashtagsQuery = Hashtag::select(DB::raw('hashtag, COUNT(hashtag) AS count'))
                ->leftJoin('tweets', 'hashtags.tweet_id', '=', 'tweets.id')
                ->groupBy('hashtag')
                ->orderByRaw('COUNT(*) DESC')
                ->limit($limit);

            $this->addFiltersToQuery($hashtagsQuery, ['hashtag']);

            $hashtags = $hashtagsQuery->get();

            Cache::forever($cacheKey, $hashtags);
        }

        return response()->json($hashtags);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary()
    {
        $cacheKeyCategorized = $this->determineCacheKey('hashtags-by-account-type');
        $cacheKey = $this->determineCacheKey('hashtags-by-month');

        if(!$hashtags = Cache::get($cacheKey))
        {
            $hashtagsQuery = Hashtag::select(DB::raw('MONTH(used_on) month, YEAR(used_on) year, COUNT(*) as count'))
                ->leftJoin('tweets', 'hashtags.tweet_id', '=', 'tweets.id')
                ->groupBy(['year', 'month']);

            $this->addFiltersToQuery($hashtagsQuery);

            $hashtags = $hashtagsQuery->get();

            Cache::forever($cacheKey, $hashtags);
        }

        if(!$hashtagsByCategory = Cache::get($cacheKeyCategorized))
        {
            $hashtagsByCategoryQuery = Hashtag::select(DB::raw('tweets.account_category, MONTH(used_on) month, YEAR(used_on) year, COUNT(*) as count'))
                ->leftJoin('tweets', 'hashtags.tweet_id', '=', 'tweets.id')
                ->groupBy(['tweets.account_category', 'year', 'month']);

            $this->addFiltersToQuery($hashtagsByCategoryQuery);

            $hashtagsByCategory = $hashtagsByCategoryQuery->get();

            Cache::forever($cacheKeyCategorized, $hashtagsByCategory);
        }

        return response()->json(['by_month'=>$hashtags, 'by_category'=>$hashtagsByCategory]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryTotals()
    {
        $cacheKey = $this->determineCacheKey('hashtags-by-account-type-totals');

        if(!$categoryTotals = Cache::get($cacheKey))
        {
            $categoryTotalsQuery = Hashtag::select(DB::raw('tweets.account_category, COUNT(*) as count'))
                ->leftJoin('tweets', 'hashtags.tweet_id', '=', 'tweets.id')
                ->groupBy(['tweets.account_category']);

            $this->addFiltersToQuery($categoryTotalsQuery);

            $categoryTotals = $categoryTotalsQuery->get();

            Cache::forever($cacheKey, $categoryTotals);
        }

        return response()->json($categoryTotals);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        $cacheKey = 'hashtags-count';
        if(!$count = Cache::get($cacheKey))
        {
            $count = Hashtag::count();
            Cache::forever($cacheKey, $count);
        }

        return response()->json($count);
    }
}

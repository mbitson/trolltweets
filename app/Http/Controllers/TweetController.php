<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TweetController extends FilterableController
{
    /**
     * @param $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function top($limit)
    {
        $cacheKey = $this->determineCacheKey('top-'.$limit.'-tweets', ['author']);

        if(!$tweets = Cache::get($cacheKey))
        {
            $tweetsQuery = Tweet::select(DB::raw('author, COUNT(author) AS count'))
                ->join('hashtags', 'tweets.id', '=', 'hashtags.tweet_id')
                ->groupBy('author')
                ->orderByRaw('COUNT(*) DESC')
                ->limit($limit);

            $this->addFiltersToQuery($tweetsQuery, ['author']);

            $tweets = $tweetsQuery->get();

            Cache::forever($cacheKey, $tweets);
        }

        return response()->json($tweets);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary()
    {
        $cacheKeyCategorized = $this->determineCacheKey('tweets-by-account-type');
        $cacheKey = $this->determineCacheKey('tweets-by-month');

        if(!$tweets = Cache::get($cacheKey))
        {
            $tweetQuery = Tweet::select(DB::raw('MONTH(publish_date) month, YEAR(publish_date) year, COUNT(*) as count'))
                ->join('hashtags', 'tweets.id', '=', 'hashtags.tweet_id')
                ->groupBy(['year', 'month']);

            $this->addFiltersToQuery($tweetQuery);

            $tweets = $tweetQuery->get();

            Cache::forever($cacheKey, $tweets);
        }

        if(!$tweetsByCategory = Cache::get($cacheKeyCategorized))
        {
            $tweetsByCategoryQuery = Tweet::select(DB::raw('account_category, MONTH(publish_date) month, YEAR(publish_date) year, COUNT(*) as count'))
                ->join('hashtags', 'tweets.id', '=', 'hashtags.tweet_id')
                ->groupBy(['account_category', 'year', 'month']);

            $this->addFiltersToQuery($tweetsByCategoryQuery);

            $tweetsByCategory = $tweetsByCategoryQuery->get();

            Cache::forever($cacheKeyCategorized, $tweetsByCategory);
        }

        return response()->json(['by_month'=>$tweets, 'by_category'=>$tweetsByCategory]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        $cacheKey = 'tweets-count';
        if(!$count = Cache::get($cacheKey))
        {
            $count = Tweet::count();
            Cache::forever($cacheKey, $count);
        }

        return response()->json($count);
    }
}

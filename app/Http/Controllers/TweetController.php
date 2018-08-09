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

        if(!$tweetsByCategory = Cache::get($cacheKeyCategorized))
        {
            $tweetsByCategoryQuery = Tweet::select(DB::raw('account_category, publish_date_month as month, publish_date_year as year, COUNT(account_category) as count'))
                ->groupBy(['account_category', 'year', 'month']);

            $this->addFiltersToQuery($tweetsByCategoryQuery);

            $tweetsByCategory = $tweetsByCategoryQuery->get();

            Cache::forever($cacheKeyCategorized, $tweetsByCategory);
        }

        return response()->json(['by_category'=>$tweetsByCategory]);
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryTotals()
    {
        $cacheKey = $this->determineCacheKey('tweets-by-account-type-totals');

        if(!$categoryTotals = Cache::get($cacheKey))
        {
            $categoryTotalsQuery = Tweet::select(DB::raw('account_category, COUNT(account_category) as count'))
                ->groupBy(['account_category']);

            $this->addFiltersToQuery($categoryTotalsQuery);

            $categoryTotals = $categoryTotalsQuery->get();

            Cache::forever($cacheKey, $categoryTotals);
        }

        return response()->json($categoryTotals);
    }

    /**
     * @param $query
     * @param array $excluded
     * @return bool
     */
    public function addFiltersToQuery(&$query, $excluded = [])
    {
        if(is_array(Session::get('filters')) && !empty(Session::get('filters')))
        {
            foreach (Session::get('filters') as $filterType => $filters)
            {
                if (in_array($filterType, $excluded)) continue;

                foreach ($filters as $filter) {
                    if (!$filter) continue;
                    if ($filterType === 'hashtag') {
                        $query->where('tweets.hashtags', 'like', '%' . $filter . '%');
                    } elseif ($filterType === 'hashtagText') {
                        $query->where('tweets.hashtags', 'like', '%' . $filter . '%');
                    } elseif ($filterType === 'author') {
                        $query->where('tweets.author', '=', $filter);
                    } elseif ($filterType === 'authorText') {
                        $query->where('tweets.author', 'like', '%' . $filter . '%');
                    } elseif ($filterType === 'tweetText') {
                        $query->where('tweets.content', 'like', '%' . $filter . '%');
                    }
                }
            }
        }
        return true;
    }
}

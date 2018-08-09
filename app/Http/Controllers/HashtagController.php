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

        if(!$hashtagsByCategory = Cache::get($cacheKeyCategorized))
        {
            $hashtagsByCategoryQuery = Hashtag::select(DB::raw('account_category, publish_date_month as month, publish_date_year as year, COUNT(account_category) as count'))
                ->groupBy(['account_category', 'year', 'month']);

            $this->addFiltersToQuery($hashtagsByCategoryQuery);

            $hashtagsByCategory = $hashtagsByCategoryQuery->get();

            Cache::forever($cacheKeyCategorized, $hashtagsByCategory);
        }

        return response()->json(['by_category'=>$hashtagsByCategory]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryTotals()
    {
        $cacheKey = $this->determineCacheKey('hashtags-by-account-type-totals');

        if(!$categoryTotals = Cache::get($cacheKey))
        {
            $categoryTotalsQuery = Hashtag::select(DB::raw('account_category, COUNT(account_category) as count'))
                ->groupBy(['account_category']);

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
                        $query->where('hashtags.hashtag', '=', $filter);
                    } elseif ($filterType === 'hashtagText') {
                        $query->where('hashtags.hashtag', 'like', '%' . $filter . '%');
                    } elseif ($filterType === 'author') {
                        $query->where('hashtags.author', '=', $filter);
                    } elseif ($filterType === 'authorText') {
                        $query->where('hashtags.author', 'like', '%' . $filter . '%');
                    } elseif ($filterType === 'tweetText') {
                        $query->where('hashtags.content', 'like', '%' . $filter . '%');
                    }
                }
            }
        }
        return true;
    }
}

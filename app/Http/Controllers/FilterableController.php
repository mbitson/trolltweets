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
        foreach(Session::get('filters') as $filterType => $filters)
        {
            if(in_array($filterType, $excluded)) continue;
            foreach($filters as $filter)
            {
                $suffix .= $filterType.'-'.$filter.'_';
            }
        }

        if($suffix === '_')
        {
            return false;
        }

        return rtrim($suffix, '_');
    }

    /**
     * @param $query
     * @param array $excluded
     * @return bool
     */
    public function addFiltersToQuery(&$query, $excluded = [])
    {
        foreach(Session::get('filters') as $filterType => $filters)
        {
            if(in_array($filterType, $excluded)) continue;
            foreach($filters as $filter)
            {
                if(!$filter) continue;
                if($filterType === 'hashtag')
                {
//                    echo "adding filter where hashtags.hashtag = $filter";
                    $query->where('hashtags.hashtag', '=', $filter);
                }
                elseif($filterType === 'hashtagText')
                {
//                    echo "adding filter where hashtags.hashtag LIKE %$filter%";
                    $query->where('hashtags.hashtag', 'like', '%'.$filter.'%');
                }
                elseif($filterType === 'author')
                {
//                    echo "adding filter where tweets.author = $filter";
                    $query->where('tweets.author', '=', $filter);
                }
                elseif($filterType === 'authorText')
                {
//                    echo "adding filter where tweets.author LIKE %$filter%";
                    $query->where('tweets.author', 'like', '%'.$filter.'%');
                }
                elseif($filterType === 'tweetText')
                {
//                    echo "adding filter where tweets.content LIKE %$filter%";
                    $query->where('tweets.content', 'like', '%'.$filter.'%');
                }
            }
        }
        return true;
    }
}

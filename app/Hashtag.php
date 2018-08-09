<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    /**
     * Which attributes can be filled into the hashtag model
     * @var array
     */
    protected $fillable = [
        'external_author_id',
        'author',
        'content',
        'region',
        'language',
        'publish_date',
        'publish_date_month',
        'publish_date_year',
        'harvested_date',
        'following',
        'followers',
        'updates',
        'post_type',
        'account_type',
        'retweet',
        'account_category',
        'new_june_2018',
        'hashtag'
    ];
}

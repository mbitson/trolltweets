<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [
        'external_author_id',
        'author',
        'content',
        'region',
        'language',
        'publish_date',
        'harvested_date',
        'following',
        'followers',
        'updates',
        'post_type',
        'account_type',
        'retweet',
        'account_category',
        'new_june_2018'
    ];
}

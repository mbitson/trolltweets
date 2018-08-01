<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    /**
     * Which attributes can be filled in this tweet model
     * @var array
     */
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

    /**
     * Get the hastags for this tweet.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hashtags(){
        return $this->hasMany('App/Hashtag');
    }
}

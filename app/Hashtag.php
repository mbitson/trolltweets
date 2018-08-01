<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    /**
     * Which attributes can be filled into the hashtag model
     * @var array
     */
    protected $fillable = ['tweet_id', 'hashtag', 'used_on'];

    /**
     * Get the tweet that owns this hashtag.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tweet(){
        return $this->belongsTo('App\Tweet');
    }
}

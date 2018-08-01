<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * Which attributes can be filled into the link model
     * @var array
     */
    protected $fillable = ['tweet_id', 'url', 'used_on'];

    /**
     * Get the tweet that owns this link.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tweet(){
        return $this->belongsTo('App\Tweet');
    }
}

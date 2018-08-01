<?php

namespace App\Console\Commands;

use App\Hashtag;
use App\Tweet;
use Illuminate\Console\Command;

class ExtractHashtags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trolls:hashtags 
                            {--batches=0 : How many batches should be run}
                            {--batch-size=10 : How big should each batch be}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extracts hashtags from the Tweet content into it\'s own, related model.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $batches = $this->option('batches');
        $batchSize = $this->option('batch-size');
        $batchesCompleted = 0;
        Tweet::chunk($batchSize, function($tweets) use ($batches, &$batchesCompleted){
            foreach($tweets as $tweet){
                preg_match_all("/(#\w+)/", $tweet->content, $hashtagPieces);
                foreach($hashtagPieces[0] as $hashtagString){
                    $hashtag = new Hashtag;
                    $hashtag->fill([
                        'tweet_id' => $tweet->id,
                        'hashtag' => $hashtagString,
                        'used_on' => $tweet->publish_date
                    ]);
                    $hashtag->save();
                }
            }
            $batchesCompleted++;
            if($batches > 0 && $batchesCompleted >= $batches){
                echo "Processed $batches batches of tweets. \n";
                return false;
            }
        });

        if($batches === 0){
            echo "Processed all tweets.";
        }

        return true;
    }
}

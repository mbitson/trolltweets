<?php

namespace App\Console\Commands;

use App\Hashtag;
use App\Tweet;
use Carbon\Carbon;
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
                            {--batch-size=5000 : How big should each batch be}';

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
        $curTime = microtime(true);

        $batches = $this->option('batches');
        $batchSize = $this->option('batch-size');
        $batchesCompleted = 0;
        Tweet::chunk($batchSize, function($tweets) use ($batches, &$batchesCompleted){
            $hashtagsToInsert = [];
            foreach($tweets as $tweet){
                preg_match_all("/(#\w+)/", $tweet->content, $hashtagPieces);
                foreach($hashtagPieces[0] as $hashtagString){
                    $hashtagsToInsert[] = [
                        'tweet_id' => $tweet->id,
                        'hashtag' => $hashtagString,
                        'used_on' => $tweet->publish_date
                    ];
                }
            }
            Hashtag::insert($hashtagsToInsert);
            $batchesCompleted++;
            if($batches > 0 && $batchesCompleted >= $batches){
                echo "Extracted all hashtags from $batchesCompleted batches of tweets. \n";
                return false;
            }
        });

        if($batches === 0){
            echo "Extracted all hashtags from all tweets using $batchesCompleted batches. \n";
        }

        $timeConsumed = round(microtime(true) - $curTime,3)*1000;
        echo "Hashtags took $timeConsumed milliseconds to complete \n";

        return true;
    }
}

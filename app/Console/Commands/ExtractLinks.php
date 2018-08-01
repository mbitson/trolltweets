<?php

namespace App\Console\Commands;

use App\Link;
use App\Tweet;
use Illuminate\Console\Command;

class ExtractLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trolls:links 
                            {--batches=0 : How many batches should be run}
                            {--batch-size=10 : How big should each batch be}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extracts links from the Tweet content into it\'s own, related model.';

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
                preg_match_all('~[a-z]+://\S+~', $tweet->content, $linkPieces);
                foreach($linkPieces[0] as $url){
                    $link = new Link;
                    $link->fill([
                        'tweet_id' => $tweet->id,
                        'url' => $url,
                        'used_on' => $tweet->posted_on
                    ]);
                    $link->save();
                }
            }
            $batchesCompleted++;
            if($batches > 0 && $batchesCompleted >= $batches){
                echo "Extracted all links from $batchesCompleted batches of tweets. \n";
                return false;
            }
        });

        if($batches === 0){
            echo "Extracted all links from all tweets using $batchesCompleted batches.";
        }

        return true;
    }
}

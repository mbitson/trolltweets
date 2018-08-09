<?php

use App\Hashtag;
use App\Tweet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use League\Csv\CharsetConverter;

class TweetTableSeeder extends Seeder
{
    /**
     * The number of rows from the CSVs to insert per query.
     * @var int
     */
    protected $recordsPerInsert = 5;

    /**
     * Insert App\Tweet records from the russian-troll-tweets CSVs.
     * @return void
     */
    public function run()
    {
        // Notify the user we're beginning
        $this->command->getOutput()->writeln('Importing tweets & hashtags...');

        // Load CSV data from russian-troll-tweets
        $csvFiles = File::files(__DIR__.'/russian-troll-tweets/');
        foreach($csvFiles as $csvFile)
        {
            // Ensure the file is a CSV
            $file = pathinfo($csvFile);
            if($file['extension'] === 'csv')
            {
                // Load the CSV, set no header offset
                $csv = Reader::createFromPath($file['dirname'].'/'.$file['basename'], 'r');
                $csv->setHeaderOffset(0);

                // Setting default charsets for additional support
                $input_bom = $csv->getInputBOM();
                if ($input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE) {
                    CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
                }

                // Build batches to insert
                $tweetsToInsert = [];
                $hashtagsToInsert = [];
                foreach($csv as $key => $record)
                {
                    // Format this record
                    $this->formatRecord($record);

                    // Add it to the batch
                    $tweetsToInsert[] = $record;

                    // Add hashtags to their batch
                    $this->addHashtags($record, $hashtagsToInsert);

                    // If our batch is big enough to insert..
                    if($key % $this->recordsPerInsert === 0) { $this->insert($tweetsToInsert, $hashtagsToInsert); }
                }

                // If the file finished and there are items still in the batch, insert them
                if(count($tweetsToInsert)>0) { $this->insert($tweetsToInsert, $hashtagsToInsert); }

                // Announce the end of this file
                $this->command->getOutput()->writeln('Finished importing: '.$file['basename']);
            }
        }

        // Notify the user we've finished.
        $this->command->getOutput()->writeln('Done importing tweets.');
    }

    /**
     * @param $record
     */
    private function formatRecord(&$record)
    {
        $record['harvested_date'] = new Carbon($record['harvested_date']);
        $record['publish_date'] = new Carbon($record['publish_date']);
        $record['publish_date_month'] = $record['publish_date']->month;
        $record['publish_date_year'] = $record['publish_date']->year;
        if ($record['external_author_id'] === '') $record['external_author_id'] = null;

        // Derive additional data
        $hashtagPieces = $hashtagsToInsert = [];
        preg_match_all("/(#\w+)/", $record['content'], $hashtagPieces);
        foreach ($hashtagPieces[0] as $hashtagString) {
            $hashtagsToInsert[] = $hashtagString;
        }
        if (count($hashtagsToInsert) > 0) {
            $record['hashtags'] = json_encode($hashtagsToInsert);
            $record['hashtagCount'] = count($hashtagsToInsert);
        } else {
            $record['hashtags'] = null;
            $record['hashtagCount'] = null;
        }
    }

    public function addHashtags($record, &$hashtagsToInsert)
    {
        if($record['hashtags'] === null) return false;
        foreach(json_decode($record['hashtags']) as $hashtag){
            $hashtagRecord = $record;
            $hashtagRecord['hashtag'] = $hashtag;
            unset($hashtagRecord['hashtagCount'], $hashtagRecord['hashtags']);
            $hashtagsToInsert[] = $hashtagRecord;
        }
        return true;
    }

    /**
     * @param $tweetsToInsert
     * @param $exception
     */
    private function insert(&$tweetsToInsert, &$hashtagsToInsert)
    {
        // Attempt a batch insert of tweets
        try {
            Tweet::insert($tweetsToInsert);
        } catch (Exception $exception) {
            $this->command->getOutput()->writeln($exception->getMessage());
        }

        // Attempt a batch insert of hashtags
        try {
            Hashtag::insert($hashtagsToInsert);
        } catch (Exception $exception) {
            $this->command->getOutput()->writeln($exception->getMessage());
        }

        // Reset our batch
        $tweetsToInsert = [];
        $hashtagsToInsert = [];
    }
}

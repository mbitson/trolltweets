<?php

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
        $this->command->getOutput()->writeln('Importing tweets...');

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
                foreach($csv as $key => $record)
                {
                    // Format the record, add it to the batch
                    $record['harvested_date'] = new Carbon($record['harvested_date']);
                    $record['publish_date'] = new Carbon($record['publish_date']);
                    if($record['external_author_id'] === '') $record['external_author_id'] = null;
                    $tweetsToInsert[] = $record;

                    // If our batch is big enough to insert..
                    if($key % $this->recordsPerInsert === 0)
                    {
                        // Attempt a batch insert
                        try{
                            Tweet::insert($tweetsToInsert);
                        }catch(Exception $exception){
                            $this->command->getOutput()->writeln($exception->getMessage());
                        }

                        // Reset our batch
                        $tweetsToInsert = [];
                    }
                }

                // If the file finished and there are items still in the batch, insert them
                if(count($tweetsToInsert)>0)
                {
                    // Attempt a batch insert
                    try{
                        Tweet::insert($tweetsToInsert);
                    }catch(Exception $exception){
                        $this->command->getOutput()->writeln($exception->getMessage());
                    }
                }

                $this->command->getOutput()->writeln('Finished importing: '.$file['basename']);
            }
        }

        // Notify the user we've finished.
        $this->command->getOutput()->writeln('Done importing tweets.');
    }
}

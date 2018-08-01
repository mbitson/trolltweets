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
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Load CSV data from russian-troll-tweets
        $this->command->getOutput()->writeln('Importing tweets...');
        $csvFiles = File::files(__DIR__.'/russian-troll-tweets/');
        foreach($csvFiles as $csvFile)
        {
            $file = pathinfo($csvFile);
            if($file['extension'] === 'csv')
            {
                $csv = Reader::createFromPath($file['dirname'].'/'.$file['basename'], 'r');
                $csv->setHeaderOffset(0);

                $input_bom = $csv->getInputBOM();

                if ($input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE) {
                    CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
                }

                foreach($csv as $record){
                    try{
                        $record['harvested_date'] = new Carbon($record['harvested_date']);
                        $record['publish_date'] = new Carbon($record['publish_date']);
                        $tweet = new Tweet;
                        $tweet->fill($record);
                        $tweet->save();
                    }catch(Exception $exception){
                        $this->command->getOutput()->writeln($exception->getMessage());
                    }
                }
            }
        }
        $this->command->getOutput()->writeln('Done importing tweets.');

        // Save as tweets
    }
}

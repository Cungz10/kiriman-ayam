<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RiwayatInput;

class FixImportedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-imported-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix imported old data to new format (JSON array for data_input, sum for rata_rata)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $records = RiwayatInput::all();
        $count = 0;

        foreach ($records as $record) {
            // Because of our custom mutator, $record->data_input is already an array here
            // whether it was stored as CSV or JSON array.
            $nilai = $record->data_input;
            
            // Recalculate sum (since rata_rata now represents sum in our new logic)
            $sum = round(array_sum($nilai), 2);
            
            // Assign back. The mutator will ensure it gets saved as JSON array
            $record->data_input = $nilai;
            $record->rata_rata = $sum;
            
            $record->save();
            $count++;
        }

        $this->info("Successfully fixed {$count} records.");
    }
}

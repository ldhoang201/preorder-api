<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class DeleteOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes old records from the products table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ageInDays = 1;

        Product::where('updated_at', '<=', now()->subDays($ageInDays))
            ->where('status', 0)->delete();

        $this->info('Old records deleted successfully.');
    }
}

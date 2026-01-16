<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductQuery;
use App\Enums\InquiryStatus;
use Carbon\Carbon;

class ExpireInquiries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inquiries:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-expire inquiries that are older than 1 month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = ProductQuery::expired()
            ->update(['status' => InquiryStatus::Expired]);

        $this->info("Expired {$expiredCount} inquiries that were older than 1 month.");
        
        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands\Imports\Orders;

use League\Csv\Reader;
use App\Models\Orders\Order;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class ImportOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import orders from a CSV file using league/csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return Command::FAILURE;
        }

        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0); // Use first row as header

        foreach ($csv->getRecords() as $row) {

            $externalId = trim($row['order_id']);
            $date = trim($row['date']);
            $time = trim($row['time']);

            $datetime = Carbon::parse("$date $time");

            Order::updateOrCreate([
                'reference_id' => $externalId,
            ], [
                'order_at' => $datetime,
            ]);

            $this->info("✅ Imported order [$externalId] at $datetime");
        }

        $this->info("🎉 Done importing orders.");
        return Command::SUCCESS;
    }
}

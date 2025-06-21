<?php

namespace App\Console\Commands\Imports\Orders;

use League\Csv\Reader;
use App\Models\Orders\Order;
use Illuminate\Console\Command;
use App\Models\Products\ProductVariant;

class ImportOrderItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:order-items {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import order items from a CSV file using league/csv';

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
        $csv->setHeaderOffset(0); // Use the first row as headers

        foreach ($csv->getRecords() as $row) {
            $externalOrderId = trim($row['order_id']);
            $variantId = trim($row['pizza_id']);
            $qty = (int) $row['quantity'];

            // Lookup order and variant
            $order = Order::where('reference_id', $externalOrderId)->first();
            $variant = ProductVariant::where('product_variant_id', $variantId)->first();

            if (!$order) {
                $this->warn("Order [$externalOrderId] not found. Skipping.");
                continue;
            }

            if (!$variant) {
                $this->warn("Product variant [$variantId] not found. Skipping.");
                continue;
            }

            $order->productVariants()->attach($variant->product_variant_id, ['quantity' => $qty]);

            $this->info("✅ Imported: Order {$externalOrderId} → {$variantId} x {$qty}");
        }

        $this->info("🎉 Done importing order items.");
        return Command::SUCCESS;
    }
}

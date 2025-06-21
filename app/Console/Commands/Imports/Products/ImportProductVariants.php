<?php

namespace App\Console\Commands\Imports\Products;

use League\Csv\Reader;
use App\Models\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Variants\VariantLabel;

class ImportProductVariants extends Command
{
    protected $signature = 'import:product-variants {file}';
    protected $description = 'Import product variants from a CSV file using league/csv';

    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File not found at: $path");
            return Command::FAILURE;
        }

        try {
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0); // First row is the header

            DB::beginTransaction();

            foreach ($csv->getRecords() as $row) {
                $variantId = trim($row['pizza_id']);
                $productId = trim($row['pizza_type_id']);
                $sizeLabel = trim($row['size']);
                $price     = trim($row['price']);

                $product = Product::where('product_id', $productId)->first();

                if (!$product) {
                    $this->warn("Product not found for code: [$productId]. Skipping.");
                    continue;
                }

                $label = VariantLabel::firstOrCreate([
                    'label' => $sizeLabel,
                ]);

                $product->variants()->updateOrCreate([
                    'product_variant_id' => $variantId,
                ],[
                    'variant_label_id' => $label->id,
                    'price' => $price,
                ]);

                $this->info("✅ Imported variant: $variantId → Product: $productId | Size: $sizeLabel | ₱$price");
            }

            DB::commit();
            $this->info("🎉 Variant import completed successfully.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("🚫 Import failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

<?php

namespace App\Console\Commands\Imports\Products;

use League\Csv\Reader;
use Illuminate\Console\Command;
use App\Models\Categories\Category;
use App\Models\Ingredients\Ingredient;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products and ingredients from CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('file');
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // first row as headers

        foreach ($csv as $row) {

            $category = Category::firstOrCreate([
                'name' => trim($row['category']),
            ]);

            $product = $category->products()->create([
                'product_id' => $row['pizza_type_id'],
                'name' => $this->cleanText(trim($row['name'])),
            ]);

            $ingredients = array_map('trim', explode(',', $row['ingredients']));

            foreach ($ingredients as $ingredientName) {
                $ingredient = Ingredient::firstOrCreate(['name' => $this->cleanText(trim($ingredientName))]);

                $ingredient->products()->attach($product->product_id);
            }

            $this->info("Imported: " . $product->name);
        }

        $this->info("Import complete.");
    }

    private function cleanText($text)
    {
        $replace = [
            "\x91" => "'", // Left single quote
            "\x92" => "'", // Right single quote
            "\x93" => '"', // Left double quote
            "\x94" => '"', // Right double quote
            "\x96" => '-', // En dash
            "\x97" => '-', // Em dash
            "\x85" => '...', // Ellipsis
            "\xA0" => ' ', // Non-breaking space
            "\xC2\xA0" => ' ', // Non-breaking space (UTF-8)
            "\u{2018}" => "'", // Unicode curly left quote
            "\u{2019}" => "'", // Unicode curly right quote
        ];
        return strtr($text, $replace);
    }
}

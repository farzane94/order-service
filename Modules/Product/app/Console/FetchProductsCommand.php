<?php

namespace Modules\Product\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\Product\Models\Product;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FetchProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'products:fetch';

    /**
     * The console command description.
     */
    protected $description = 'Fetch products from dummyjson.com and save them to the database';

    protected string $endpoint = 'https://dummyjson.com/products';

    const LIMIT = 10;
    const SKIP = 10;


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->info('Fetching products...');

        $response = Http::get($this->endpoint, [
            'limit' => self::LIMIT,
            'skip' => self::SKIP,
            'select' => 'title,price,stock',
        ]);

        if ($response->failed()) {
            $this->error('API request failed');
            return 1;
        }

        $this->syncData($response['products']);

        $this->info('Products saved successfully ✅');
        return 0;
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    private function syncData(array $products)
    {
        foreach ($products as $item) {
            Product::updateOrCreate(
                ['title' => $item['title']],
                [
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                ]
            );
        }
    }
}

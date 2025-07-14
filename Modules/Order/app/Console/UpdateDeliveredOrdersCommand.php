<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\Order\Models\Order;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateDeliveredOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:update-delivered';

    /**
     * The console command description.
     */
    protected $description = 'Mark shipped orders older than 24 hours as delivered';

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
        $this->info('Checking for shipped orders older than 24 hours...');

        $cutoff = Carbon::now()->subHours(24);

        $affected = Order::where('status', Order::STATUS_SHIPPED)
            ->where('created_at', '<=', $cutoff)
            ->update(['status' => Order::STATUS_DELIVERED]);

        $this->info("$affected orders updated to delivered.");
        return self::SUCCESS;
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
}

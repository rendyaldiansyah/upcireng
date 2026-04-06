<?php

namespace App\Console\Commands;

use App\Services\DailyRecapService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyRecapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recap:daily {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily recap of orders and send to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting daily recap generation...');

        try {
            $service = app(DailyRecapService::class);
            $recap = $service->send();

            $this->info('✓ Daily recap completed successfully!');
            $this->line('');
            $this->line($recap);
            Log::info('Daily recap command executed successfully', ['recap' => $recap]);

        } catch (\Exception $e) {
            $this->error('✗ Error generating daily recap: ' . $e->getMessage());
            Log::error('Daily recap command error: ' . $e->getMessage());
        }
    }
}

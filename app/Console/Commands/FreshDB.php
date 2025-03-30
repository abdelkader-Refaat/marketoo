<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FreshDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear caches and perform a fresh database migration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs');
        $files = File::files($logPath);

        foreach ($files as $file) {
            File::delete($file);
            $this->info('Deleted: '.$file->getFilename());
        }

        $this->info('Log files deleted successfully.');
        $this->info('Clearing configuration cache...');
        $this->call('config:clear');

        $this->info('Clearing application cache...');
        $this->call('cache:clear');
        $this->call('config:cache');

        $this->info('Clearing view cache...');
        $this->call('view:clear');

        $this->info('optimize clear...');
        $this->call('o:c');

        $this->info('Running fresh migration...');
        $this->call('migrate:fresh', ['--seed' => true, '--force' => true]);

        $this->info('Database refreshed successfully.');

        $this->info('config cache...');
        $this->call('config:cache');
    }

}

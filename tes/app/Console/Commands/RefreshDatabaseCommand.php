<?php

namespace App\Console\Commands;

use App\Models\Admin\Tag;
use Illuminate\Support\Str;
use App\Models\Admin\Category;
use Illuminate\Console\Command;

class RefreshDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronJobs:refreshDatabseCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh cache';

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
     *
     * @return int
     */
    public function handle()
    {
        // $this->call('migrate');
        // $this->call('db:seed');
        // $this->info('Data berhasil di refresh semuanya');
        $this->call('config:cache');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:cache');
        $this->call('storage:link');
        $this->call('key:generate');
    }
}

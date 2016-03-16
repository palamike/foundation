<?php

namespace Palamike\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FoundationPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'foundation:publish
                            {--tag=* : publish tag}
                            {--force : force file to republish}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Foundation Files (like vendor publish but only for the foundation specific)';

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
     * @return mixed
     */
    public function handle()
    {
        $options = [
            '-vvv' => true,
            '--provider' => 'Palamike\Foundation\Providers\FoundationServiceProvider'
        ];

        $tag = $this->option('tag');
        if(!empty($tag)){
            $options['--tag'] = $tag;
        }//if

        $force = $this->option('force');
        if(!empty($force)){
            $options['--force'] = true;
        }//if

        Artisan::call('vendor:publish', $options);

        $output = 'Foundation publish completed !';

        $this->info($output);
    }
}

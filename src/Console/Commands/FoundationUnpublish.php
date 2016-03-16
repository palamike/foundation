<?php

namespace Palamike\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class FoundationUnpublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'foundation:unpublish
                            {--tag=* : unpublish tag}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish the published file';

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
        $tags = $this->option('tag');

        $tags = $tags ?: [null];

        foreach($tags as $tag){
            $this->unpublish($tag);
        }//foreach
    }

    public function unpublish($tag){

        $shared = App::make('foundation.command.shared');
        $paths = $shared->publishPaths;

        if(array_key_exists($tag,$paths)){
            foreach($paths[$tag] as $path){
                $this->unpublishFile($path);
            }//foreach
        }//if
        else {
            //else unpublish for all published file.
            foreach($paths as $tag){
                foreach($tag as $path){
                    $this->unpublishFile($path);
                }//foreach path
            }//foreach tags
        }
    }

    public function unpublishFile($path){
        if(File::isDirectory($path)){
            File::deleteDirectory($path);
        }
        else{
            File::delete($path);
        }

        $this->info('unpublished file/directory from path '.$path);
    }
}

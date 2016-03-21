<?php

namespace App\Console\Commands\Resource\Generator;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class Lang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'res:lang
                            {table : table to get columns name aka field name}
                            {--prefix= : prefix of the langguage key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the resource key from table';

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
        $table = $this->argument('table');
        $prefix = $this->option('prefix');
        $columns = Schema::getColumnListing($table);

        if(empty($columns)){
            $this->error("   Table $table does not exist.   ");
            return;
        }//if

        $this->info('Start generate the resources key for '.$table);

        $languages = available_languages();



        foreach($languages as $lang){
            $fileName = substr($prefix,0,strpos($prefix,'.'));
            $file = resource_path('lang/'.$lang.'/'.$fileName.'.php');

            $messages = [];
            if(file_exists($file)){
                $messages = include($file);
            }//if

            foreach($columns as $column){
                $prefixKey = substr($prefix,strpos($prefix,'.')+1);
                $key = "$prefixKey.field.$column";

                if(!array_key_exists($key,$messages)){
                    $messages[$key] = $key;
                }//if

            }//foreach columns

            $var = var_export($messages,true);
            $content = "<?php \n\n return $var; ";
            file_put_contents($file,$content);

        }//foreach

        $this->info('Finish generate the resources key for '.$table);
    }
}

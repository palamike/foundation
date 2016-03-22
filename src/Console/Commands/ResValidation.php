<?php

namespace Palamike\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

class ResValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'res:validation
                            {controller : the controller class name with namespace}
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
        $controllerName = $this->argument('controller');
        $prefix = $this->option('prefix');
        $controller = App::make("App\\Http\\Controllers\\".$controllerName);
        $rules = $controller->rules;

        $this->info('Start generate the validation resources key for '.$controllerName);

        $languages = available_languages();



        foreach($languages as $lang){
            $fileName = substr($prefix,0,strpos($prefix,'.'));
            $file = resource_path('lang/'.$lang.'/'.$fileName.'.php');

            $messages = [];
            if(file_exists($file)){
                $messages = include($file);
            }//if

            foreach($rules as $field => $allRules){

                $validations = explode('|',$allRules);
                foreach($validations as $validation){
                    if(strpos($validation,':') !== false){
                        $validation = substr($validation,0,strpos($validation,':'));
                    }//if

                    $prefixKey = substr($prefix,strpos($prefix,'.')+1);
                    $key = "$prefixKey.field.validation.$field.$validation";

                    if(!array_key_exists($key,$messages) && ($validation != 'sometimes')){
                        $messages[$key] = $key;
                    }//if
                }
            }//foreach columns

            $var = var_export($messages,true);
            $content = "<?php \n\n return $var; ";
            file_put_contents($file,$content);

        }//foreach

        $this->info('Finish generate the validation resources key for '.$controllerName);
    }
}

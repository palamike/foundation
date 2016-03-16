<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/15/2016 AD
 * Time: 2:33 PM
 */

namespace Palamike\Foundation\Providers;


use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider{

    protected $package_name = 'foundation';
    protected $resource_path = __DIR__.'/../../resources';
    protected $config_path = __DIR__.'/../../config';
    protected $database_path = __DIR__.'/../../database';
    protected $shared = null;

    public function boot(){

        if(App::runningInConsole()){

            /**
             * Create shared object to share with command foundation:unpublish
             */
            $this->app->singleton('foundation.command.shared',function(){
                $shared = new \stdClass();
                $shared->publishPaths = [];
                return $shared;
            });

            /**
             * This variable hold the container foundation.shared by object references.
             */
            $this->shared = $this->app->make('foundation.command.shared');

            /**
             * Register View Files
             */
            $this->loadViewsFrom($this->resource_path.'/views', $this->package_name);
            $this->vendorRegisters([
                $this->resource_path.'/views' => resource_path('views/vendor/'.$this->package_name),
            ],'view');

            /**
             * Register Translation Files
             */
            $this->loadTranslationsFrom($this->resource_path.'/lang', $this->package_name);
            $this->vendorRegisters([
                $this->resource_path.'/lang' => resource_path('lang/vendor/'.$this->package_name),
            ],'lang');

            /**
             * Register and merge package configuration File with the application configuration file.
             */
            $this->vendorRegisters([
                $this->config_path.'/'.$this->package_name.'.php' => config_path($this->package_name.'.php'),
            ],'config');
            $this->mergeConfigFrom(
                $this->config_path.'/'.$this->package_name.'.php', $this->package_name
            );

            /**
             * Migration Files
             */
            $this->vendorRegisters($this->getMigrations(),'migration');

            /**
             * Register Commands
             */
            $this->registerCommands();
        }//if Running In Console
        else{

        }//else Running in web

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    private function getMigrations(){
        $migration_path = $this->database_path.'/migrations';
        $files = File::files($migration_path);

        $output = [];

        foreach($files as $file){
            $dt = Carbon::now()->format('Y_m_d');
            $migration = str_replace('migration',$dt,File::name($file));
            $output[$file] = database_path('migrations/'.$migration.'.php');
        }

        return $output;
    }//private function getMigrations

    private function vendorRegisters($publishes,$group = null){

        $this->publishes($publishes,$group);

        foreach($publishes as $publish){

            if(empty($group)){
                $group = 'all';
            }//if no group specify, assign group = 'all'

            if(!array_key_exists($group,$this->shared->publishPaths)){
                $this->shared->publishPaths[$group] = [];
            }//if

            array_push($this->shared->publishPaths[$group],$publish);
        }//foreach
    }

    public function registerCommands(){
        $this->app->singleton('command.foundation.publish', function ($app) {
            return $app['Palamike\Foundation\Console\Commands\FoundationPublish'];
        });
        $this->commands('command.foundation.publish');

        $this->app->singleton('command.foundation.unpublish', function ($app) {
            return $app['Palamike\Foundation\Console\Commands\FoundationUnpublish'];
        });
        $this->commands('command.foundation.unpublish');
    }
}
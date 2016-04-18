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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Palamike\Foundation\Events\DataQuery;
use Palamike\Foundation\Events\DataStore;
use Palamike\Foundation\Events\Debug;
use Palamike\Foundation\Events\RouteNavigation;
use Palamike\Foundation\Events\UserAccess;
use Palamike\Foundation\Listeners\FoundationLogListener;
use Palamike\Foundation\Models\Auth\Permission;
use Palamike\Foundation\Models\Auth\User;
use Palamike\Foundation\Services\System\LanguageService;
use Palamike\Foundation\Services\System\SettingService;
use Palamike\Foundation\Services\UI\AssetService;
use Palamike\Foundation\Services\UI\MenuService;

class FoundationServiceProvider extends ServiceProvider {

    protected $package_name = 'foundation';
    protected $resource_path = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources';
    protected $config_path = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config';
    protected $database_path = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database';
    protected $shared = null;

    public function boot(GateContract $gate,DispatcherContract $events){

        parent::boot($events);

        $logConfig = (boolean)config('foundation.log');
        $appDebug = (boolean)config('app.debug');
        
        if($logConfig['global'] && $logConfig['access']){
            $events->listen(UserAccess::class,FoundationLogListener::class);    
        }//if
        
        if($logConfig['global'] && $logConfig['store']){
            $events->listen(DataStore::class,FoundationLogListener::class);    
        }
        
        if($logConfig['global'] && $logConfig['navigation']){
            $events->listen(RouteNavigation::class,FoundationLogListener::class);
        }

        if($logConfig['global'] && $logConfig['query']){
            $events->listen(DataQuery::class,FoundationLogListener::class);
            DB::listen(function($query){
                Event::fire(new DataQuery($query));
            });
        }//if

        if($appDebug && $logConfig['global'] && $logConfig['query']){
            $events->listen(Debug::class,FoundationLogListener::class);
        }

        if(App::runningInConsole()){
            /**
             * This variable hold the container foundation.shared by object references.
             */
            $this->shared = $this->app->make('foundation.command.shared');

            /**
             * Register View Files
             */
            $this->loadViewsFrom($this->resource_path.DIRECTORY_SEPARATOR.'views', $this->package_name);
            $this->vendorRegisters([
                $this->resource_path.DIRECTORY_SEPARATOR.'views' => resource_path('views/vendor/'.$this->package_name),
            ],'view');

            /**
             * Register Translation Files
             */
            $this->loadTranslationsFrom($this->resource_path.DIRECTORY_SEPARATOR.'lang', $this->package_name);
            $this->vendorRegisters([
                $this->resource_path.DIRECTORY_SEPARATOR.'lang' => resource_path('lang/vendor/'.$this->package_name),
                $this->resource_path.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'menu.php' => resource_path('lang/th/menu.php'),
                $this->resource_path.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'auth.php' => resource_path('lang/th/auth.php'),
                $this->resource_path.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'pagination.php' => resource_path('lang/th/pagination.php'),
                $this->resource_path.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'passwords.php' => resource_path('lang/th/passwords.php'),
                $this->resource_path.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'validation.php' => resource_path('lang/th/validation.php')
            ],'lang');

            /**
             * Register and merge package configuration File with the application configuration file.
             */
            $this->vendorRegisters([
                $this->config_path.DIRECTORY_SEPARATOR.$this->package_name.'.php' => config_path($this->package_name.'.php'),
                $this->config_path.DIRECTORY_SEPARATOR.'assets.php' => config_path('assets.php')
            ],'config');
            
            $this->mergeConfigFrom(
                $this->config_path.DIRECTORY_SEPARATOR.$this->package_name.'.php', $this->package_name
            );
            
            $this->mergeConfigFrom(
                $this->config_path.DIRECTORY_SEPARATOR.'assets.php','assets'
            );
            

            /**
             * Migration Files
             */
            $this->vendorRegisters($this->getMigrations(),'migration');

            /**
             * Assets
             */
            $this->vendorRegisters([
                $this->resource_path.DIRECTORY_SEPARATOR.'assets' => resource_path('assets/vendor/'.$this->package_name)
            ],'asset');

            /**
             * Gulp File (use --force function to overwrite)
             */
            $this->publishes([
                    $this->resource_path.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'gulp' => base_path('/')
                ],'gulp');

            /**
             * Initial Menu File
             */
            $this->vendorRegisters([
                $this->resource_path.DIRECTORY_SEPARATOR.'menu'.DIRECTORY_SEPARATOR.'menu.yaml' => resource_path('menu/menu.yaml')
            ],'menu');
            
            /**
             * Register Commands
             */
            $this->registerCommands();
        }//if Running In Console
        else{

            $this->loadViewsFrom($this->resource_path.DIRECTORY_SEPARATOR.'views', $this->package_name);

            $this->loadTranslationsFrom($this->resource_path.DIRECTORY_SEPARATOR.'lang', $this->package_name);

            $this->mergeConfigFrom(
                $this->config_path.DIRECTORY_SEPARATOR.$this->package_name.'.php', $this->package_name
            );
            
            $this->mergeConfigFrom(
                $this->config_path.DIRECTORY_SEPARATOR.'assets.php','assets'
            );

            $this->registerPermissions($gate);
        }//else Running in web

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('foundation.settings', function () {
            return new SettingService();
        });

        $this->app->singleton('foundation.assets', function () {
            return new AssetService();
        });

        $this->app->singleton('foundation.languages', function () {
            return new LanguageService();
        });

        $this->app->singleton('foundation.menu', function () {
            return new MenuService();
        });

        $this->app->singleton('foundation.media', function () {
            return new MediaService();
        });

        /**
         * Create shared object to share with command foundation:unpublish
         */
        $this->app->singleton('foundation.command.shared',function(){
            $shared = new \stdClass();
            $shared->publishPaths = [];
            return $shared;
        });
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

        $this->app->singleton('command.foundation.dbt.fillable', function ($app) {
            return $app['Palamike\Foundation\Console\Commands\DBTFillable'];
        });
        $this->commands('command.foundation.dbt.fillable');

        $this->app->singleton('command.foundation.dbt.master', function ($app) {
            return $app['Palamike\Foundation\Console\Commands\DBTMaster'];
        });
        $this->commands('command.foundation.dbt.master');

        $this->app->singleton('command.foundation.res.table', function ($app) {
            return $app['Palamike\Foundation\Console\Commands\ResTable'];
        });
        $this->commands('command.foundation.res.table');

        $this->app->singleton('command.foundation.res.validation', function ($app) {
            return $app['Palamike\Foundation\Console\Commands\ResValidation'];
        });
        $this->commands('command.foundation.res.validation');
    }

    public function registerPermissions(GateContract $gate){
        foreach($this->getPermissions() as $permission){
            $gate->define($permission->name,function(User $user) use ($permission){
                return $user->hasRole($permission->roles);
            });
        }
    }

    private function getPermissions(){
        return Permission::with('roles')->get();
    }
}
<?php

namespace App\Console\Commands\Database\Tools;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class Fillable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbt:fillable {table : table to get fillable columns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $columns = Schema::getColumnListing($table);

        $fillable = '';

        $this->info('=====================');
        $this->info('[Columns]');
        $this->info('=====================');
        foreach ($columns as $column) {
            $this->line($column);

            switch($column){
                case 'created_at':
                case 'updated_at':
                case 'deleted_at':
                case 'id' : break;
                default : $fillable .= "'$column',";
            }
        }//foreach

        $this->info('=====================');
        $this->info('[Fillables]');
        $this->info('=====================');
        $this->line($fillable);
    }
}

<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/15/2016 AD
 * Time: 4:27 PM
 */

namespace Palamike\Foundation\Extended;


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ExtendedMigration extends Migration {


    protected function addUserStamps(Blueprint $table,$constrain = true){
        $table->unsignedInteger('created_by');//keep user_id
        $table->unsignedInteger('updated_by')->nullable();//keep user_id
        $table->string('created_by_name')->nullable();//keep user_name
        $table->string('updated_by_name')->nullable();//keep user_name

        if($constrain){
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('restrict');

            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('restrict');
        }//if
    }

    protected function dropUserStamps(Blueprint $table,$constrain = true){

        if($constrain){
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        }//if

        $table->dropColumn('created_by');
        $table->dropColumn('updated_by');
        $table->dropColumn('created_by_name');
        $table->dropColumn('updated_by_name');
    }
}
<?php

use Illuminate\Database\Schema\Blueprint;
use Palamike\Foundation\Extended\ExtendedMigration;

class ExtendUsersTable extends ExtendedMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',function(Blueprint $table){
            $table->string('username')->unique();
            $table->string('status')->default('inactive');
            $this->addUserStamps($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users',function(Blueprint $table){
            $table->dropColumn('username');
            $table->dropColumn('status');
            $this->dropUserStamps($table);
        });
    }
}

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
            $this->addUserStamps($table,false);
        });

        Schema::create('signon_sessions', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('signon_sessions');

        Schema::table('users',function(Blueprint $table){
            $table->dropColumn('username');
            $table->dropColumn('status');
            $this->dropUserStamps($table,false);
        });
    }
}

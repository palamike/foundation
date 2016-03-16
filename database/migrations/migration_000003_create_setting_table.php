<?php

use Illuminate\Database\Schema\Blueprint;
use Palamike\Foundation\Extended\ExtendedMigration;

class CreateSettingTable extends ExtendedMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('setting_groups', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description');

            $table->timestamps();
            $this->addUserStamps($table);
        });

        Schema::create('settings', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description');
            $table->text('type'); //boolean, string , integer, double
            $table->text('input'); //text, select, checkbox, textarea, email, password
            $table->text('default');
            $table->text('value');
            $table->longText('choices')->nullable(); // multiple value of choice or checkbox in format key:value, key2:value2
            $table->longText('validation')->nullable();
            $table->longText('config')->nullable();

            $table->integer('group_id')->unsigned();

            $table->timestamps();
            $this->addUserStamps($table);

            $table->foreign('group_id')
                ->references('id')
                ->on('setting_groups')
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
        Schema::drop('settings');
        Schema::drop('setting_groups');
    }

}

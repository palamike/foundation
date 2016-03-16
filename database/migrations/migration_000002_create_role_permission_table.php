<?php

use Illuminate\Database\Schema\Blueprint;
use Palamike\Foundation\Extended\ExtendedMigration;

class CreateRolePermissionTable extends ExtendedMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('permission_groups', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('label');

            $table->timestamps();
            $this->addUserStamps($table);

        });

        Schema::create('permissions', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('label');
            $table->unsignedInteger('group_id');

            $table->timestamps();
            $this->addUserStamps($table);

            $table->foreign('group_id')
                ->references('id')
                ->on('permission_groups')
                ->onDelete('cascade');
        });

        Schema::create('roles', function(Blueprint $table){
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('redirect'); //redirect page after login in format '/dashboard'

            $table->timestamps();
            $this->addUserStamps($table);
        });

        Schema::create('permission_role', function(Blueprint $table){
            $table->engine = 'InnoDB';

            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id','role_id']);
        });

        Schema::create('role_user', function(Blueprint $table){
            $table->engine = 'InnoDB';

            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['role_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
        Schema::drop('permission_role');
        Schema::drop('roles');
        Schema::drop('permissions');
        Schema::drop('permission_groups');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Palamike\Foundation\Extended\ExtendedMigration;

class CreateMediaTable extends ExtendedMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('category');
            $table->string('file_name');
            $table->string('extension');
            $table->string('mime');
            $table->string('path');
            $table->string('web_path');
            $table->string('thumb_path');
            $table->text('meta_data')->nullable();

            $table->timestamps();
            $this->addUserStamps($table);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('avatar_id')->nullable()->after('status');
            $table->foreign('avatar_id')
                ->references('id')
                ->on('medias')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_avatar_id_foreign');
            $table->dropColumn('avatar_id');
        });

        Schema::drop('medias');
    }
}

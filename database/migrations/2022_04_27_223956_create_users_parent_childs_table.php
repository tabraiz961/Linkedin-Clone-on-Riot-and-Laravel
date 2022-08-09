<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersParentChildsTable extends Migration {

	public function up()
	{
		Schema::create('users_parent_childs', function(Blueprint $table) {
			$table->integer('parent_id')->index();asd
			$table->integer('child_id');
		});
		
        Schema::table('users_parent_childs', function(Blueprint $table) {
            $table->foreign('parent_id')->references('user_id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

			$table->foreign('child_id')->references('user_id')->on('users')
				->onDelete('cascade')
				->onUpdate('cascade');
        });
	}

	public function down()
	{
		Schema::drop('users_parent_childs');
	}
}
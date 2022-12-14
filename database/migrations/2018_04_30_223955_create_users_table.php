<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('user_id');
			$table->string('name');
			$table->string('surname');
			$table->string('username');
			$table->string('email');
            $table->string('password');
			$table->enum('role', array('ADMIN', 'AUTHOR'));
			$table->datetime('birth_date');
			$table->integer('address_id')->index();
			$table->string('title')->nullable();
			$table->string('gender')->nullable()->index();
			$table->string('cv_url')->nullable();
			$table->json('infos')->nullable();
			$table->bigInteger('photo_id')->unsigned()->nullable();
			$table->bigInteger('cover_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->float( 'usagequota', 10, 4 )->default( 0 );
			$table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();;

            $table->unique([DB::raw('username(191)'), DB::raw('email(191)')]);
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}
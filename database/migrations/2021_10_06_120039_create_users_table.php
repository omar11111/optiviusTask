<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->json('name');
			$table->string('phone', 255);
			$table->string('email', 255);
			$table->string('password',255);
			$table->integer('code');
			$table->string('api_token',255);
			$table->datetime('email_verified_at');
			$table->datetime('phone_verfied_at');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}
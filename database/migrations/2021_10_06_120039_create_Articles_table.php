<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	public function up()
	{
		Schema::create('Articles', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->json('title');
			$table->json('content');
			$table->integer('user_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('Articles');
	}
}
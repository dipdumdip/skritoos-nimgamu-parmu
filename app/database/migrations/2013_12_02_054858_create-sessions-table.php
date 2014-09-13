<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration {

	/**
	 * Run the migrations. php artisan migrate:make create-sessions-table
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create('sessions', function($table)
			{
			    $table->string('id')->unique();
			    $table->text('payload');
			    $table->integer('last_activity');
			});
	}

	/**
	 * Reverse the migrations.  php artisan migrate
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('users');
	}

}

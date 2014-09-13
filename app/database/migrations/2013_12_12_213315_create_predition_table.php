<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreditionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prediction', function($table)
			{
			    $table->increments('id')->unique();
			    $table->string('buy_level');
			    $table->string('sell_level');
			    $table->integer('accuracy')->default(1);
			    $table->timestamp('updated')->default(DB::raw('CURRENT_TIMESTAMP'));
			    $table->string('host', 22);
				$table->integer('comp_id_fk');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('prediction');
	}

}

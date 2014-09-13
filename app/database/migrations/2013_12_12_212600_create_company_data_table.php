<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_data', function($table)
			{
			    $table->increments('id')->unique();
			    $table->integer('comp_id_fk');
			    $table->integer('accuracy')->default(1);
			    $table->text('csvdata');
			    $table->timestamp('updated')->default(DB::raw('CURRENT_TIMESTAMP'));
			});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('company_data');
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company', function($table)
			{
			    $table->increments('id')->unique();
			    $table->string('company_symbol', 30);
			    $table->string('company_name', 30)->nullable();
			    $table->text('address');
			    $table->text('description');
			    $table->integer('view_count')->default(1);
			    $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
			});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('company');
	}

}

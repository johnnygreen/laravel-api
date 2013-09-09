<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTokensTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tokens', function(Blueprint $table) {
      $table->engine = 'MyISAM';
      $table->increments('id');
      $table->integer('user_id');
      $table->string('user_model');
      $table->string('access_token');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('tokens');
  }

}
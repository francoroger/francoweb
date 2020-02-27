<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTanqueCiclosTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('tanque_ciclos', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('tanque_id')->unsigned();
      $table->timestamp('data_servico', 0);
      $table->decimal('peso');
      $table->string('status', 1);
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
    Schema::dropIfExists('tanque_ciclos');
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassagemPecasTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('passagem_pecas', function (Blueprint $table) {
      $table->increments('id');
      $table->date('data_servico');
      $table->integer('cliente_id')->unsigned()->nullable();
      $table->integer('tiposervico_id')->unsigned()->nullable();
      $table->integer('material_id')->unsigned()->nullable();
      $table->integer('cor_id')->unsigned()->nullable();
      $table->integer('milesimos')->unsigned()->nullable();
      $table->decimal('peso');
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
    Schema::dropIfExists('passagem_pecas');
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeparacoesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('separacoes', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('cliente_id')->unsigned()->nullable();
      $table->string('status', 1);
      $table->timestamps();

      $table->foreign('cliente_id')->references('id')->on('cliente')->onUpdate('cascade');
    });

    Schema::create('separacoes_recebimentos', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('separacao_id')->unsigned();
      $table->integer('recebimento_id')->unsigned();
      $table->timestamps();

      $table->foreign('separacao_id')->references('id')->on('separacoes')->onUpdate('cascade')->onDelete('cascade');
      $table->foreign('recebimento_id')->references('id')->on('recebimento_pecas')->onUpdate('cascade')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('separacoes_recebimentos');
    Schema::dropIfExists('separacoes');
  }
}

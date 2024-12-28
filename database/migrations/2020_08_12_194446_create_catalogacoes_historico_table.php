<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogacoesHistoricoTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('catalogacoes_historico', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('catalogacao_id')->unsigned();
      $table->dateTime('data_inicio')->nullable();
      $table->dateTime('data_fim')->nullable();
      $table->string('status', 1)->nullable();
      $table->timestamps();
      $table->foreign('catalogacao_id')->references('id')->on('triagem')->onUpdate('cascade')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('catalogacoes_historico');
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogacoesEdicoesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('catalogacoes_edicoes', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('iditemtriagem')->unsigned()->nullable();
      $table->integer('idfornec')->unsigned()->nullable();
      $table->integer('peso')->nullable();
      $table->string('referencia')->nullable();
      $table->string('quantidade')->nullable();
      $table->timestamps();

      $table->foreign('iditemtriagem')->references('id')->on('itemtriagem')->onUpdate('cascade')->onDelete('cascade');
      $table->foreign('idfornec')->references('id')->on('fornecedor')->onUpdate('cascade')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('catalogacoes_edicoes');
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCampoTipoFalhaNaTabelaRetrabalhosItens extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('retrabalhos_itens', function (Blueprint $table) {
      $table->integer('tipo_falha_id')->unsigned()->nullable();
      $table->foreign('tipo_falha_id')->references('id')->on('tipos_falha')->onUpdate('cascade')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('retrabalhos_itens', function (Blueprint $table) {
      $table->dropForeign('retrabalhos_itens_tipo_falha_id_foreign');
      $table->dropColumn('tipo_falha_id');
    });
  }
}

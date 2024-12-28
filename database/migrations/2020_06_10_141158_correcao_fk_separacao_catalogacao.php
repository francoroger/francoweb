<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrecaoFkSeparacaoCatalogacao extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->dropForeign('separacoes_catalogacao_id_foreign');
    });

    Schema::table('separacoes', function (Blueprint $table) {
      $table->foreign('catalogacao_id')->references('id')->on('triagem')->onUpdate('cascade')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //
  }
}

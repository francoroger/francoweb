<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCampoArquivadoNaTabelaRecebimentos extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('recebimento_pecas', function (Blueprint $table) {
      $table->boolean('arquivado')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('recebimento_pecas', function (Blueprint $table) {
      $table->dropColumn('arquivado');
    });
  }
}

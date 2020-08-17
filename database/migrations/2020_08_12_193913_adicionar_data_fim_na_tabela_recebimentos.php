<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarDataFimNaTabelaRecebimentos extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('recebimento_pecas', function (Blueprint $table) {
      $table->dateTime('data_fim')->nullable();
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
      $table->dropColumn('data_fim');
    });
  }
}

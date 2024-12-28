<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCamposTempoRetrabalhoNaTabelaSeparacoes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->dateTime('data_inicio_retrabalho')->nullable();
      $table->dateTime('data_fim_retrabalho')->nullable();
      $table->string('status_retrabalho', 1)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->dropColumn('data_inicio_retrabalho');
      $table->dropColumn('data_fim_retrabalho');
      $table->dropColumn('status_retrabalho');
    });
  }
}

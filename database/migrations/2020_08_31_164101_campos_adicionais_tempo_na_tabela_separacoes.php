<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CamposAdicionaisTempoNaTabelaSeparacoes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->dateTime('data_fim_separacao')->nullable()->after('data_fim_recebimento');
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
      $table->dropColumn('data_fim_separacao');
    });
  }
}

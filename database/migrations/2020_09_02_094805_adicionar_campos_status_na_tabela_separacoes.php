<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCamposStatusNaTabelaSeparacoes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->string('status_separacao', 1)->nullable();
      $table->string('status_banho', 1)->nullable();
      $table->string('status_expedicao', 1)->nullable();
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
      $table->dropColumn('status_separacao');
      $table->dropColumn('status_banho');
      $table->dropColumn('status_expedicao');
    });
  }
}

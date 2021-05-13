<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCampoStatusPreparacaoNaTabelaSeparacoes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->string('status_preparacao', 1)->nullable();
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
      $table->dropColumn('status_preparacao');
    });
  }
}

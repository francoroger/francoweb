<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCampoTipoReforcoNaTabelaReforcos extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('reforcos', function (Blueprint $table) {
      $table->string('tipo', 1)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('reforcos', function (Blueprint $table) {
      $table->dropColumn('tipo');
    });
  }
}

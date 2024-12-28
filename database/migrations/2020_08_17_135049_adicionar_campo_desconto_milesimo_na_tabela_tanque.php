<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCampoDescontoMilesimoNaTabelaTanque extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tanque', function (Blueprint $table) {
      $table->smallInteger('desconto_milesimo')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('tanque', function (Blueprint $table) {
      $table->dropColumn('desconto_milesimo');
    });
  }
}

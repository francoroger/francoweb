<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterarCampoDescontoMilesimoNaTabelaTanque extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tanque', function (Blueprint $table) {
      $table->decimal('desconto_milesimo')->nullable()->change();
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

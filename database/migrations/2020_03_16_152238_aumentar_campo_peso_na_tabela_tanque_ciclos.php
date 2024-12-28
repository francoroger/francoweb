<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AumentarCampoPesoNaTabelaTanqueCiclos extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('tanque_ciclos', function (Blueprint $table) {
      $table->decimal('peso', 16, 2)->change();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('tanque_ciclos', function (Blueprint $table) {
      //
    });
  }
}

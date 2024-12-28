<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteracaoCheckNaTabelaItemtriagem extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('itemtriagem', function (Blueprint $table) {
      $table->dropColumn('check');
    });

    Schema::table('itemtriagem', function (Blueprint $table) {
      $table->string('status_check', 1)->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    //
  }
}

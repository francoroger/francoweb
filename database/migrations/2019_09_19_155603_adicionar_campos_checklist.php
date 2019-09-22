<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCamposChecklist extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('itemtriagem', function (Blueprint $table) {
      $table->boolean('check')->nullable();
      $table->string('obs_check')->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('itemtriagem', function (Blueprint $table) {
      $table->dropColumn('check');
      $table->dropColumn('obs_check');
    });
  }
}

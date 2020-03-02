<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCamposNaTabelaTanqueCiclos extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('tanque_ciclos', function (Blueprint $table) {
      $table->integer('cliente_id')->unsigned()->nullable()->after('data_servico');
      $table->integer('tiposervico_id')->unsigned()->nullable()->after('cliente_id');
      $table->integer('material_id')->unsigned()->nullable()->after('tiposervico_id');
      $table->integer('cor_id')->unsigned()->nullable()->after('material_id');
      $table->integer('milesimos')->unsigned()->nullable()->after('cor_id');
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
      $table->dropColumn('cliente_id');
      $table->dropColumn('tiposervico_id');
      $table->dropColumn('material_id');
      $table->dropColumn('cor_id');
      $table->dropColumn('milesimos');
    });
  }
}

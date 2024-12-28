<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrecaoFormatoHoraServico extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tanque_ciclos', function (Blueprint $table) {
      $table->dateTime('data_servico')->change();
    });

    Schema::table('passagem_pecas', function (Blueprint $table) {
      $table->dateTime('data_servico')->change();
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
      $table->dateTime('data_servico')->change();
    });

    Schema::table('passagem_pecas', function (Blueprint $table) {
      $table->dateTime('data_servico')->change();
    });
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCamposTempoNaTabelaSeparacoes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('separacoes', function (Blueprint $table) {
      $table->dateTime('data_inicio_recebimento')->nullable();
      $table->dateTime('data_fim_recebimento')->nullable();
      $table->dateTime('data_inicio_catalogacao')->nullable();
      $table->dateTime('data_fim_catalogacao')->nullable();
      $table->dateTime('data_inicio_preparacao')->nullable();
      $table->dateTime('data_fim_preparacao')->nullable();
      $table->dateTime('data_inicio_banho')->nullable();
      $table->dateTime('data_fim_banho')->nullable();
      $table->dateTime('data_inicio_revisao')->nullable();
      $table->dateTime('data_fim_revisao')->nullable();
      $table->dateTime('data_inicio_expedicao')->nullable();
      $table->dateTime('data_fim_expedicao')->nullable();
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
      //
    });
  }
}

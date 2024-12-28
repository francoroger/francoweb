<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CamposNovosNasTabelasReforco extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tanque_ciclos', function (Blueprint $table) {
      $table->decimal('peso_antes', 16, 3)->nullable();
      $table->decimal('peso_depois', 16, 3)->nullable();
      $table->decimal('peso_peca')->nullable();
      $table->boolean('retroativo')->nullable();
      $table->softDeletes();
    });

    Schema::table('reforcos', function (Blueprint $table) {
      $table->decimal('peso_antes', 16, 3)->nullable();
      $table->decimal('peso')->nullable();
      $table->decimal('peso_depois', 16, 3)->nullable();
      $table->text('motivo_reforco')->nullable();
      $table->softDeletes();
    });

    Schema::table('passagem_pecas', function (Blueprint $table) {
      $table->softDeletes();
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
      $table->dropColumn('peso_antes');
      $table->dropColumn('peso_depois');
      $table->dropColumn('peso_peca');
      $table->dropColumn('retroativo');
    });

    Schema::table('reforcos', function (Blueprint $table) {
      $table->dropColumn('peso_antes');
      $table->dropColumn('peso');
      $table->dropColumn('peso_depois');
      $table->dropColumn('motivo_reforco');
    });
  }
}

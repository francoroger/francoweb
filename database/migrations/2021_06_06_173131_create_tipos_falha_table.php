<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposFalhaTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tipos_falha', function (Blueprint $table) {
      $table->increments('id');
      $table->string('descricao')->nullable();
      $table->timestamps();
    });

    Schema::table('retrabalhos', function (Blueprint $table) {
      $table->integer('tipo_falha_id')->unsigned()->nullable();
      $table->foreign('tipo_falha_id')->references('id')->on('tipos_falha')->onUpdate('cascade')->onDelete('set null');
    });

    Schema::table('itemtriagem', function (Blueprint $table) {
      $table->integer('tipo_falha_id')->unsigned()->nullable();
      $table->foreign('tipo_falha_id')->references('id')->on('tipos_falha')->onUpdate('cascade')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('retrabalhos', function (Blueprint $table) {
      $table->dropForeign('retrabalhos_tipo_falha_id_foreign');
      $table->dropColumn('tipo_falha_id');
    });

    Schema::table('itemtriagem', function (Blueprint $table) {
      $table->dropForeign('triagem_tipo_falha_id_foreign');
      $table->dropColumn('tipo_falha_id');
    });

    Schema::dropIfExists('tipos_falha');
  }
}

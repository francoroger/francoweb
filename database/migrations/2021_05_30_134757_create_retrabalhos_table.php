<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetrabalhosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('retrabalhos', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('cliente_id')->unsigned()->nullable();
      $table->text('observacoes')->nullable();
      $table->string('status', 1)->nullable();

      $table->timestamps();
      $table->foreign('cliente_id')->references('id')->on('cliente')->onUpdate('cascade');
    });

    Schema::create('retrabalhos_itens', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('retrabalho_id')->unsigned();
      $table->integer('tiposervico_id')->unsigned()->nullable();
      $table->integer('material_id')->unsigned()->nullable();
      $table->integer('cor_id')->unsigned()->nullable();
      $table->integer('milesimos')->unsigned()->nullable();
      $table->decimal('peso');
      $table->timestamps();

      $table->foreign('retrabalho_id')->references('id')->on('retrabalhos')->onUpdate('cascade')->onDelete('cascade');
    });

    Schema::table('separacoes', function (Blueprint $table) {
      $table->integer('retrabalho_id')->unsigned()->nullable();
      $table->foreign('retrabalho_id')->references('id')->on('retrabalhos')->onUpdate('cascade')->onDelete('cascade');
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
      $table->dropForeign('separacoes_retrabalho_id_foreign');
      $table->dropColumn('retrabalho_id');
    });

    Schema::dropIfExists('retrabalhos_itens');
    Schema::dropIfExists('retrabalhos');
  }
}

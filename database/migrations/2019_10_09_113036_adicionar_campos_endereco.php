<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarCamposEndereco extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('cliente', function (Blueprint $table) {
      $table->string('numero')->nullable()->after('endereco');
      $table->string('compl')->nullable()->after('numero');
      $table->string('numero_entrega')->nullable()->after('endereco_entrega');
      $table->string('compl_entrega')->nullable()->after('numero_entrega');
    });

    Schema::table('fornecedor', function (Blueprint $table) {
      $table->string('numero')->nullable()->after('endereco');
      $table->string('compl')->nullable()->after('numero');
    });

    Schema::table('guia', function (Blueprint $table) {
      $table->string('numero')->nullable()->after('endereco');
      $table->string('compl')->nullable()->after('numero');
      $table->string('uf', 2)->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('cliente', function (Blueprint $table) {
      $table->dropColumn('numero');
      $table->dropColumn('compl');
      $table->dropColumn('numero_entrega');
      $table->dropColumn('compl_entrega');
    });

    Schema::table('fornecedor', function (Blueprint $table) {
      $table->dropColumn('numero');
      $table->dropColumn('compl');
    });

    Schema::table('guia', function (Blueprint $table) {
      $table->dropColumn('numero');
      $table->dropColumn('compl');
      $table->dropColumn('uf');
    });
  }
}

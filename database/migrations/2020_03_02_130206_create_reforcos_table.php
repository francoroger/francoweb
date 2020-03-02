<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReforcosTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('reforcos', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('tanque_id')->unsigned();
      $table->timestamps();
    });

    Schema::table('tanque_ciclos', function (Blueprint $table) {
      $table->integer('reforco_id')->unsigned()->nullable()->after('status');
      $table->boolean('excedente')->nullable()->after('reforco_id');
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
      $table->dropColumn('reforco_id');
      $table->dropColumn('excedente');
    });

    Schema::dropIfExists('reforcos');
  }
}

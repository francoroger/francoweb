<?php

use App\Tanque;
use Illuminate\Database\Seeder;

class TipoConsumoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $tanques = Tanque::whereNotNull('ciclo_reforco')->get();
    foreach ($tanques as $tanque) {
      $tanque->tipo_consumo = 'P';
      $tanque->save();
    }
  }
}

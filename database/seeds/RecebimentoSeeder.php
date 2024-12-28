<?php

use App\Recebimento;
use Illuminate\Database\Seeder;

class RecebimentoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $recebimentos = Recebimento::whereNull('status')->where('data_receb', '>=', date('Y-m-d', strtotime('2020-03-01')) )->get();
    foreach ($recebimentos as $recebimento) {
      $recebimento->status = 'R';
      $recebimento->save();
    }
  }
}

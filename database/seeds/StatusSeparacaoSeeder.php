<?php

use App\Separacao;
use Illuminate\Database\Seeder;

class StatusSeparacaoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $separacoes = Separacao::where('status', 'S')->get();
    foreach ($separacoes as $separacao) {
      $separacao->status_separacao = 'A';
      $separacao->save();
    }

    $banhos = Separacao::where('status', 'F')->get();
    foreach ($banhos as $banho) {
      $banho->status_banho = 'G';
      $banho->save();
    }

    $expedicoes = Separacao::where('status', 'C')->get();
    foreach ($expedicoes as $expedicao) {
      $expedicao->status_expedicao = 'G';
      $expedicao->save();
    }
  }
}

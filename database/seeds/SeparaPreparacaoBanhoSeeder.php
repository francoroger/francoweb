<?php

use App\Catalogacao;
use App\Separacao;
use Illuminate\Database\Seeder;

class SeparaPreparacaoBanhoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $ordens = Separacao::where('status', 'F')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();
    foreach ($ordens as $separacao) {
      if ($separacao->status_banho === 'A') {
        $banho = Separacao::findOrFail($separacao->id);
        $banho->status = 'B';
        $banho->save();

        $catalogacao = Catalogacao::findOrFail($separacao->catalogacao_id);
        $catalogacao->status = 'B';
        $catalogacao->save();
      }
    }
  }
}

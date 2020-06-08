<?php

use App\Catalogacao;
use App\Separacao;
use Illuminate\Database\Seeder;

class SeparacaoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $catalogacoes = Catalogacao::all();
    foreach ($catalogacoes as $catalogacao) {
      if (!$catalogacao->separacao) {
        //Criando a separação
        $separacao = new Separacao();
        $separacao->cliente_id = $catalogacao->idcliente;
        $separacao->status = $catalogacao->status;
        $separacao->catalogacao_id = $catalogacao->id;

        //data / hora
        //$data_cad = date('d/m/Y H:i:s', strtotime($catalogacao->datacad . ' ' . $catalogacao->horacad));

        //$separacao->created_at = $data_cad;
        $separacao->save();
      }
    }
  }
}

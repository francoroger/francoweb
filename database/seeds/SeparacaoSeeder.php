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
      //Removendo as sem status
      if (!$catalogacao->status) {
        $this->command->info("Excluindo catalogação {$catalogacao->id} - sem status");
        $c = Catalogacao::findOrFail($catalogacao->id);
        $c->delete();
      } else {
        $s = Separacao::where('catalogacao_id', $catalogacao->id)->get();
        if ($s->count() == 0) {
          //Criando a separação
          $separacao = new Separacao();
          $separacao->cliente_id = $catalogacao->idcliente;
          $separacao->status = $catalogacao->status;
          $separacao->catalogacao_id = $catalogacao->id;
          $separacao->save();
          $this->command->info("Separação {$separacao->id} criada para catalogação {$catalogacao->id}");
        } else if ($s->count() == 1) {
          //Atualizando a separação
          $separacao = Separacao::findOrFail($s->first()->id);
          $separacao->cliente_id = $catalogacao->idcliente;
          $separacao->status = $catalogacao->status;
          $separacao->catalogacao_id = $catalogacao->id;
          $separacao->save();
        } else if ($s->count() > 1) {
          $this->command->info("Catalogação {$catalogacao->id} continha mais de uma separação...");
          foreach ($s as $sep) {
            $separacao = Separacao::findOrFail($sep->id);
            $separacao->delete();
          }
          //Criando a separação
          $separacao = new Separacao();
          $separacao->cliente_id = $catalogacao->idcliente;
          $separacao->status = $catalogacao->status;
          $separacao->catalogacao_id = $catalogacao->id;
          $separacao->save();
          $this->command->info("Separação {$separacao->id} criada para catalogação {$catalogacao->id}");
        }
      }
    }
  }
}

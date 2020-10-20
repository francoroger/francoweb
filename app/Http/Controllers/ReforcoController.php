<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Material;
use App\PassagemPeca;
use App\ProcessoTanque;
use App\Reforco;
use App\Tanque;
use App\TanqueCiclo;
use App\TipoServico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReforcoController extends Controller
{
  public function index()
  {
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();

    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();

    $tiposServico = TipoServico::whereHas('processos_tanque')->orderBy('descricao')->get();
    $materiais = Material::whereHas('processos_tanque')->orderBy('pos')->get();
    return view('controle_reforco.index')->with([
      'tanques' => $tanques,
      'tiposServico' => $tiposServico,
      'materiais' => $materiais,
      'clientes' => $clientes,
    ]);
  }

  public function tanques(Request $request)
  {
    $processos = ProcessoTanque::orderBy('id');

    if ($request->get('idtiposervico')) {
      $processos->where('tiposervico_id', $request->get('idtiposervico'));
    }

    if ($request->get('idmaterial')) {
      $processos->where('material_id', $request->get('idmaterial'));
    }

    if ($request->get('idcor')) {
      $processos->where('cor_id', $request->get('idcor'));
    }

    if ($request->get('milesimos')) {
      $processos->where('mil_ini', '<=', $request->get('milesimos'))
                ->where('mil_fim', '>=', $request->get('milesimos'));
    }

    $processos = $processos->get();

    $data = [];

    foreach ($processos as $proc) {
      $data[] = [
        'id' => $proc->tanque_id,
        'descricao' => $proc->tanque->descricao,
      ];
    }

    return response()->json($data);
  }

  public function registra_ciclo(Request $request)
  {
    $data_servico = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $request->get('data_servico') . ' ' . $request->get('hora_servico'));
    $passagem = new PassagemPeca;
    $passagem->data_servico = $data_servico;
    $passagem->cliente_id = $request->get('idcliente');
    $passagem->tiposervico_id = $request->get('idtiposervico');
    $passagem->material_id = $request->get('idmaterial');
    $passagem->cor_id = $request->get('idcor');
    $passagem->milesimos = $request->get('milesimos');
    $passagem->peso = str_replace(',', '.', $request->get('peso'));
    $passagem->save();

    $processos = ProcessoTanque::orderBy('id');

    if ($request->get('idtiposervico')) {
      $processos->where('tiposervico_id', $request->get('idtiposervico'));
    }

    if ($request->get('idmaterial')) {
      $processos->where('material_id', $request->get('idmaterial'));
    }

    if ($request->get('idcor')) {
      $processos->where('cor_id', $request->get('idcor'));
    }

    if ($request->get('milesimos')) {
      $processos->where('mil_ini', '<=', $request->get('milesimos'))
                ->where('mil_fim', '>=', $request->get('milesimos'));
    }

    $processos = $processos->get();

    foreach ($processos as $proc) {
      $ciclo = new TanqueCiclo;

      $NDesconto = (double)$proc->tanque->desconto_milesimo ?? 0;
      
      if ($proc->tanque->tipo_consumo == 'M') {
        $NPeso = str_replace(',', '.', $request->get('peso'));
        $NMilesimos = $request->get('milesimos');
        $peso_consumido = (($NPeso * $NMilesimos) / 1000) - (($NPeso * $NDesconto) / 1000);
        $ciclo->peso = $peso_consumido;
      } else {
        $fat = $proc->fator ?? 1;
        $ciclo->peso = str_replace(',', '.', $request->get('peso')) * $fat;
      }

      $ciclo->tanque_id = $proc->tanque_id;
      $ciclo->cliente_id = $request->get('idcliente');
      $ciclo->tiposervico_id = $request->get('idtiposervico');
      $ciclo->material_id = $request->get('idmaterial');
      $ciclo->cor_id = $request->get('idcor');
      $ciclo->milesimos = $request->get('milesimos');
      $ciclo->data_servico = $data_servico;
      $ciclo->status = 'P';
      $ciclo->peso_peca = str_replace(',', '.', $request->get('peso'));
      $ciclo->peso_antes = $proc->tanque->ciclos->where('status', 'P')->sum('peso');
      $ciclo->peso_depois = $ciclo->peso_antes + $ciclo->peso;

      //Verifica se já teve passagem ou reforço após essaa passagem, caso seja retroativa
      $tanque = Tanque::findOrFail($proc->tanque_id);      
      $reforcos_apos = $tanque->reforcos->where('created_at', '>=', $data_servico)->sortBy('created_at');

      //Obtem as passagens após esse serviço para atualizar os dados dela
      $passagens_apos = $tanque->ciclos->where('data_servico', '>=', $data_servico)->sortBy('data_servico');

      if ($reforcos_apos->count() > 0 || $passagens_apos->count() > 0) {
        //Flag para indentificar que foi lançado retroativo pós reforco
        $ciclo->retroativo = true;
      }
      
      if ($reforcos_apos->count() > 0) {
        //Lança essa passagem já reforçada
        $ciclo->status = 'R';
        $ciclo->reforco_id = $reforcos_apos->first()->id ?? null;

        //Percorre os reforços atualizando os valores dos ponteiros
        foreach ($reforcos_apos as $r) {
          $reforco_apos = Reforco::findOrFail($r->id);
          $reforco_apos->peso_antes += $ciclo->peso;
          $reforco_apos->peso_depois += $ciclo->peso;
          $reforco_apos->save();
        }
      }

      //Atualiza as passagens feitas após esse lancamento caso seja retroativo
      $idx = 0; //pra controlar qual é a primeira passagem
      foreach ($passagens_apos as $p) {
        if ($idx == 0) {
          $ciclo->peso_antes = $p->peso_antes;
          $ciclo->peso_depois = $ciclo->peso_antes + $ciclo->peso;
        }
        $ciclo_apos = TanqueCiclo::findOrFail($p->id);
        $ciclo_apos->peso_antes += $ciclo->peso;
        $ciclo_apos->peso_depois += $ciclo->peso;
        if ($ciclo_apos->excedente == true) {
          $ciclo_apos->peso += $ciclo->peso;
          $ciclo_apos->peso_peca += $ciclo->peso_peca;
        }
        $ciclo_apos->save();
        $idx++;
      }

      $ciclo->save();
    }

    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    $data = [];

    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);

  }

  public function reset_ciclo(Request $request)
  {
    $tanque = Tanque::findOrFail($request->get('id'));
    $peso_antes = $tanque->ciclos->where('status', 'P')->sum('peso');

    //Pega o excedente para criar uma nova transação após o reforço
    $exd = 0;
    if ($tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco) {
      $exd = $tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco;
    }

    //Obter a quantidade mínima necessária pra zerar, o que sobra é o negativo
    $neg = 0;
    if ($tanque->ciclos->where('status', 'P')->sum('peso') < $tanque->ciclo_reforco) {
      $neg = $tanque->ciclo_reforco - $tanque->ciclos->where('status', 'P')->sum('peso');
    }

    //Gera um registro para o reforço que será associado com o item
    $reforco = new Reforco;
    $reforco->tanque_id = $request->get('id');
    $reforco->peso_antes = $peso_antes;
    $reforco->peso = $tanque->ciclo_reforco;
    $reforco->peso_depois = $peso_antes - $tanque->ciclo_reforco;
    $reforco->save();

    //Atualiza o item com o status de R (realizado) e a id do reforço
    $affected = DB::table('tanque_ciclos')
                  ->where('tanque_id', $request->get('id'))
                  ->where('status', 'P')
                  ->update([
                    'status' => 'R',
                    'reforco_id' => $reforco->id,
                  ]);

    //Cria a transação do excedente
    if ($exd > 0) {
      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $request->get('id');
      $ciclo->data_servico = \Carbon\Carbon::now();
      $ciclo->peso = $exd;
      $ciclo->status = 'P';
      $ciclo->excedente = true;
      //$ciclo->reforco_id = $reforco->id;
      $ciclo->peso_peca = $exd;
      $ciclo->peso_antes = $reforco->peso_depois;
      $ciclo->peso_depois = $ciclo->peso;
      $ciclo->save();
    }

    //Cria a transação do negativo
    if ($neg > 0) {
      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $request->get('id');
      $ciclo->data_servico = \Carbon\Carbon::now();
      $ciclo->peso = - $neg;
      $ciclo->status = 'P';
      $ciclo->excedente = true;
      $ciclo->reforco_id = $reforco->id;
      $ciclo->peso_peca = $exd;
      $ciclo->peso_antes = $reforco->peso_depois;
      $ciclo->peso_depois = $ciclo->peso;
      $ciclo->save();
    }

    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function reset_tanque(Request $request)
  {
    //Pega o excedente para criar uma nova transação após o reforço
    $tanque = Tanque::findOrFail($request->get('id'));
    
    DB::table('tanque_ciclos')->where('tanque_id', $request->get('id'))->delete();

    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function reforco_analise(Request $request)
  {
    $tanque = Tanque::findOrFail($request->get('id'));
    $peso_antes = $tanque->ciclos->where('status', 'P')->sum('peso');

    //Gera um registro para o reforço que será associado com o item
    $reforco = new Reforco;
    $reforco->tanque_id = $request->get('id');
    $reforco->tipo = 'A';
    $reforco->peso_antes = $peso_antes;
    $reforco->peso = $tanque->ciclo_reforco;
    $reforco->peso_depois = $request->get('reforco_analise_valor');
    $reforco->motivo_reforco = $request->get('reforco_analise_motivo');
    $reforco->save();

    $current_val = $tanque->ciclos->where('status', 'P')->sum('peso');
    $reforco_val = $request->get('reforco_analise_valor');

    $diff = 0;
    if ($reforco_val > $current_val) {
      $diff = $reforco_val - $current_val;
    } else {
      $diff = ($current_val - $reforco_val) * (-1);
    }

    $ciclo = new TanqueCiclo;
    $ciclo->tanque_id = $request->get('id');
    $ciclo->data_servico = \Carbon\Carbon::now();
    $ciclo->peso = $diff;
    $ciclo->status = 'P';
    $ciclo->excedente = true;
    $ciclo->reforco_id = $reforco->id;
    $ciclo->save();
    
    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function undo_reforco(Request $request)
  {
    $tanque = Tanque::findOrFail($request->get('id'));
    $reforco = $tanque->reforcos->sortByDesc('created_at')->first();
    $reforco_id = $reforco->id;
    
    //Exclui os excedentes
    $excedentes = TanqueCiclo::where('tanque_id', $request->get('id'))->where('reforco_id', null)->where('status', 'P')->where('excedente', true)->get();
    foreach ($excedentes as $excedente) {
      $excedente->delete();
    }

    //Obtem as passagens após esse reforço para atualizar os dados dela
    $passagens_apos = $tanque->ciclos->where('data_servico', '>=', $reforco->created_at)->sortBy('data_servico');
    //Atualiza as passagens feitas após esse lancamento caso seja retroativo
    foreach ($passagens_apos as $p) {
      $ciclo_apos = TanqueCiclo::findOrFail($p->id);
      $ciclo_apos->peso_antes += $reforco->tanque->ciclo_reforco;
      $ciclo_apos->peso_depois += $reforco->tanque->ciclo_reforco;
      $ciclo_apos->save();
    }
  
    //Atualiza o item com o status de R (realizado) e a id do reforço
    $updt = DB::table('tanque_ciclos')
              ->where('tanque_id', $request->get('id'))
              ->where('reforco_id', $reforco_id)
              ->update([
                'status' => 'P',
                'reforco_id' => null,
              ]);

    //Exclui o reforço
    $reforco->delete();

    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function consulta(Request $request)
  {
    $passagens = PassagemPeca::latest('created_at');
    $passagens = $passagens->paginate(10);

    if ($request->ajax()) {
      return response()->json([
        'view' => view('consulta_reforco.data', compact('passagens'))->render()
      ]);
    } else {
      return view('consulta_reforco.index')->with([
        'passagens' => $passagens
      ]);
    }
  }

  public function destroy($id)
  {
    $passagem = PassagemPeca::findOrFail($id);

    $processos = ProcessoTanque::orderBy('id');

    if ($passagem->tiposervico_id) {
      $processos->where('tiposervico_id', $passagem->tiposervico_id);
    }

    if ($passagem->material_id) {
      $processos->where('material_id', $passagem->material_id);
    }

    if ($passagem->cor_id) {
      $processos->where('cor_id', $passagem->cor_id);
    }

    if ($passagem->milesimos) {
      $processos->where('mil_ini', '<=', $passagem->milesimos)
                ->where('mil_fim', '>=', $passagem->milesimos);
    }

    $processos = $processos->get();

    foreach ($processos as $proc) {
      $NDesconto = (double)$proc->tanque->desconto_milesimo ?? 0;
      
      if ($proc->tanque->tipo_consumo == 'M') {
        $NPeso = $passagem->peso;
        $NMilesimos = $passagem->milesimos;
        $peso_consumido = (($NPeso * $NMilesimos) / 1000) - (($NPeso * $NDesconto) / 1000);
        $peso = $peso_consumido;
      } else {
        $fat = $proc->fator ?? 1;
        $peso = $passagem->peso * $fat;
      }

      $ciclo = TanqueCiclo::where('tanque_id', $proc->tanque_id)
                          ->where('cliente_id', $passagem->cliente_id)
                          ->where('tiposervico_id', $passagem->tiposervico_id)
                          ->where('material_id', $passagem->material_id)
                          ->where('peso', $peso)
                          ->get()
                          ->first();

      if ($ciclo) {
        //Verifica se teve passagem ou reforço após essa passagem
        $tanque = Tanque::findOrFail($proc->tanque_id);      
        $reforcos_apos = $tanque->reforcos->where('created_at', '>=', $passagem->data_servico)->sortBy('created_at');

        //Obtem as passagens após esse serviço para atualizar os dados dela
        $passagens_apos = $tanque->ciclos->where('data_servico', '>=', $passagem->data_servico)->sortBy('data_servico');

        if ($reforcos_apos->count() > 0) {
          //Percorre os reforços atualizando os valores dos ponteiros
          foreach ($reforcos_apos as $r) {
            $reforco_apos = Reforco::findOrFail($r->id);
            $reforco_apos->peso_antes -= $ciclo->peso;
            $reforco_apos->peso_depois -= $ciclo->peso;
            $reforco_apos->save();
          }
        }

        //Atualiza as passagens feitas após esse lancamento caso seja retroativo
        foreach ($passagens_apos as $p) {
          $ciclo_apos = TanqueCiclo::findOrFail($p->id);
          $ciclo_apos->peso_antes -= $ciclo->peso;
          $ciclo_apos->peso_depois -= $ciclo->peso;
          if ($ciclo_apos->excedente == true) {
            $ciclo_apos->peso -= $ciclo->peso;
            $ciclo_apos->peso_peca -= $ciclo->peso_peca;
          }
          $ciclo_apos->save();
        }

        //Exclui o ciclo
        $toDel = TanqueCiclo::findOrFail($ciclo->id);
        $toDel->delete();
      }
    }

    //Exclui a passagem
    if ($passagem->delete()) {
      return response(200);
    }
  }
}

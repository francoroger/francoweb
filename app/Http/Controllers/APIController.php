<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogacao;
use App\OrdemServico;
use App\Recebimento;
use App\PassagemPeca;
use App\ProcessoTanque;
use App\Reforco;
use App\TanqueCiclo;
use App\Tanque;
use DB;

class APIController extends Controller
{
  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function recebimento(Request $request)
  {
    $data = [];
    $recebimentos = Recebimento::whereNull('status')
                               ->orderBy('data_receb', 'desc')
                               ->take(10)
                               ->get();

    foreach ($recebimentos as $recebimento) {
      $data[] = [
        'id' => 'REC-' . $recebimento->id,
        'title' => $recebimento->cliente->nome ?? $recebimento->nome_cliente,
        'codigo' => $recebimento->id,
        'cliente' => $recebimento->cliente->rzsc ?? ($recebimento->cliente->nome ?? $recebimento->nome_cliente),
        'data_evento' => date('d/m/Y', strtotime($recebimento->data_receb)),
      ];
    }

    return response()->json($data);
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function separacao(Request $request)
  {
    $data = [];

    return response()->json($data);
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function catalogacao(Request $request)
  {
    $data = [];

    $catalogacoes = Catalogacao::whereIn('status', ['A', 'F'])
                               ->whereNotNull('idcliente')
                               ->orderBy('datacad', 'desc')
                               ->take(10)
                               ->get();

    foreach ($catalogacoes as $catalogacao) {
      $data[] = [
        'id' => 'CAT-' . $catalogacao->id,
        'title' => $catalogacao->cliente->nome ?? '',
        'codigo' => $catalogacao->id,
        'cliente' => $catalogacao->cliente->rzsc ?? $catalogacao->cliente->nome,
        'data_evento' => date('d/m/Y', strtotime($catalogacao->datacad)),
      ];
    }

    return response()->json($data);
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function os(Request $request)
  {
    $data = [];
    $ordens = OrdemServico::whereNotNull('idcliente')
                          ->orderBy('datavenda', 'desc')
                          ->take(10)
                          ->get();

    foreach ($ordens as $ordem) {
      $data[] = [
        'id' => 'OS-' . $ordem->id,
        'title' => $ordem->cliente->nome ?? '',
        'codigo' => $ordem->id,
        'cliente' => $ordem->cliente->rzsc ?? $ordem->cliente->nome,
        'data_evento' => date('d/m/Y', strtotime($ordem->datavenda)),
      ];
    }

    return response()->json($data);
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function revisao(Request $request)
  {
    $data = [];
    $catalogacoes = Catalogacao::where('status', 'P')
                               ->whereNotNull('idcliente')
                               ->orderBy('datacad', 'desc')
                               ->take(10)
                               ->get();

    foreach ($catalogacoes as $catalogacao) {
      $data[] = [
        'id' => 'REV-' . $catalogacao->id,
        'title' => $catalogacao->cliente->nome ?? '',
        'codigo' => $catalogacao->id,
        'cliente' => $catalogacao->cliente->rzsc ?? $catalogacao->cliente->nome,
        'data_evento' => date('d/m/Y', strtotime($catalogacao->datacad)),
      ];
    }

    return response()->json($data);
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function expedicao(Request $request)
  {
    $data = [];
    $catalogacoes = Catalogacao::where('status', 'C')
                               ->whereNotNull('idcliente')
                               ->orderBy('datacad', 'desc')
                               ->take(10)
                               ->get();

    foreach ($catalogacoes as $catalogacao) {
      $data[] = [
        'id' => 'EXP-' . $catalogacao->id,
        'title' => $catalogacao->cliente->nome ?? '',
        'codigo' => $catalogacao->id,
        'cliente' => $catalogacao->cliente->rzsc ?? $catalogacao->cliente->nome,
        'data_evento' => date('d/m/Y', strtotime($catalogacao->datacad)),
      ];
    }

    return response()->json($data);
  }

  public function updateCatalogacao(Request $request)
  {
    $catalogacao = Catalogacao::findOrFail($request->id);
    $catalogacao->status = $request->status;

    if ($catalogacao->save()) {
      return response(200);
    }
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
    $passagem = new PassagemPeca;
    $passagem->data_servico = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('data_servico') . ' ' . $request->get('hora_servico'));
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
      $fat = $proc->fator ?? 1;

      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $proc->tanque_id;
      $ciclo->cliente_id = $request->get('idcliente');
      $ciclo->tiposervico_id = $request->get('idtiposervico');
      $ciclo->material_id = $request->get('idmaterial');
      $ciclo->cor_id = $request->get('idcor');
      $ciclo->milesimos = $request->get('milesimos');
      $ciclo->data_servico = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('data_servico') . ' ' . $request->get('hora_servico'));
      $ciclo->peso = str_replace(',', '.', $request->get('peso')) * $fat;
      $ciclo->status = 'P';
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
    //Pega o excedente para criar uma nova transação após o reforço
    $tanque = Tanque::findOrFail($request->get('id'));
    $exd = $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? $tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco : 0;

    //Gera um registro para o reforço que será associado com o item
    $reforco = new Reforco;
    $reforco->tanque_id = $request->get('id');
    $reforco->save();

    //Atualiza o item com o status de R (realizado) e a id do reforço
    $affected = DB::table('tanque_ciclos')
                  ->where('tanque_id', $request->get('id'))
                  ->where('status', 'P')
                  ->update([
                    'status' => 'R',
                    'reforco_id' => $reforco->id,
                  ]);

    if ($exd > 0) {
      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $request->get('id');
      $ciclo->data_servico = \Carbon\Carbon::now();
      $ciclo->peso = $exd;
      $ciclo->status = 'P';
      $ciclo->excedente = true;
      $ciclo->reforco_id = $reforco->id;
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

  public function undo_reforco(Request $request)
  {
    $tanque = Tanque::findOrFail($request->get('id'));
    $reforco = $tanque->reforcos->sortByDesc('created_at')->first();
    $reforco_id = $reforco->id;

    //Exclui os excedentes
    $exced = DB::table('tanque_ciclos')
               ->where('tanque_id', $request->get('id'))
               ->where('reforco_id', $reforco_id)
               ->where('status', 'P')
               ->where('excedente', true)
               ->delete();

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

}

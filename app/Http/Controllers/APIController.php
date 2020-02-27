<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogacao;
use App\OrdemServico;
use App\Recebimento;
use App\ProcessoTanque;
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
    $processos = ProcessoTanque::where('tiposervico_id', $request->get('idtiposervico'))
                               ->where('material_id', $request->get('idmaterial'))
                               ->where('cor_id', $request->get('idcor'))
                               ->where('mil_ini', '<=', $request->get('milesimos'))
                               ->where('mil_fim', '>=', $request->get('milesimos'))
                               ->get();

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
    $processos = ProcessoTanque::where('tiposervico_id', $request->get('idtiposervico'))
                               ->where('material_id', $request->get('idmaterial'))
                               ->where('cor_id', $request->get('idcor'))
                               ->where('mil_ini', '<=', $request->get('milesimos'))
                               ->where('mil_fim', '>=', $request->get('milesimos'))
                               ->get();

    foreach ($processos as $proc) {
      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $proc->tanque_id;
      $ciclo->data_servico = \Carbon\Carbon::now();
      $ciclo->peso = $request->get('peso');
      $ciclo->status = 'P';
      $ciclo->save();
    }

    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    $data = [];

    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => '#tanque-' . $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
      ];
    }

    return response()->json($data);

  }

  public function reset_ciclo(Request $request)
  {
    $affected = DB::table('tanque_ciclos')
                  ->where('tanque_id', $request->get('id'))
                  ->update(['status' => 'R']);

    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();

    return response(200);
  }


}

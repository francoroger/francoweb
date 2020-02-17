<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogacao;
use App\OrdemServico;
use App\Recebimento;

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
}

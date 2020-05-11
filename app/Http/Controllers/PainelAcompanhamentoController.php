<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\OrdemServico;
use App\Recebimento;
use Illuminate\Http\Request;

class PainelAcompanhamentoController extends Controller
{
  public function index()
  {
    $catalogacoes = Catalogacao::where('status', 'A')
                                    ->whereNotNull('idcliente')
                                    ->orderBy('datacad', 'desc')
                                    ->get();

    $ordens = Catalogacao::where('status', 'F')
                              ->whereNotNull('idcliente')
                              ->orderBy('datacad', 'desc')
                              ->get();

    $revisoes = Catalogacao::whereIn('status', ['P', 'G'])
                                ->whereNotNull('idcliente')
                                ->orderBy('datacad', 'desc')
                                ->get();

    $expedicoes = Catalogacao::where('status', 'C')
                                 ->whereNotNull('idcliente')
                                 ->orderBy('datacad', 'desc')
                                 ->take(30)
                                 ->get();

    $concluidos = Catalogacao::where('status', 'L')
                                ->whereNotNull('idcliente')
                                ->orderBy('datacad', 'desc')
                                ->take(30)
                                ->get();

    return view('painel_acompanhamento.index')->with([
      'catalogacoes' => $catalogacoes,
      'ordens' => $ordens,
      'revisoes' => $revisoes,
      'expedicoes' => $expedicoes,
      'concluidos' => $concluidos,
    ]);
  }

  public function updateCatalogacao(Request $request)
  {
    $catalogacao = Catalogacao::findOrFail($request->id);
    $catalogacao->status = $request->status;

    if ($catalogacao->save()) {
      return response(200);
    }
  }

}

<?php

namespace App\Http\Controllers;

use App\Separacao;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class DashboardTempoController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    return view('dashboard_tempo.main');
  }

  public function search(Request $request)
  {
    $servicos = Separacao::with('recebimentos')->whereHas('cliente')->select();

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      $servicos->where(function ($query)  use ($dtini, $dtfim) {
        $query->whereBetween('created_at', [$dtini, $dtfim])
          ->whereNotNull('data_fim_expedicao')
          ->orWhereHas('recebimentos', function ($query) use ($dtini, $dtfim) {
            $query->whereBetween('data_receb', [$dtini, $dtfim]);
          })
          ->orWhereHas('catalogacao', function ($query) use ($dtini, $dtfim) {
            $query->whereBetween('datacad', [$dtini, $dtfim]);
          });
      });
    }

    $servicos = $servicos->get();
    $total_peso = ceil($servicos->sum('peso_recebimentos') / 1000);
    $total_servicos = $servicos->count();
    $total_itens = $servicos->sum('total_itens_catalogacao');
    $dados_tempo_exec = [
      ['Recebimento', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_recebimento') / 3600 / $servicos->count()) : 0],
      ['Separação', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_separacao') / 3600 / $servicos->count()) : 0],
      ['Catalogação', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_catalogacao') / 3600 / $servicos->count()) : 0],
      ['Preparação', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_preparacao') / 3600 / $servicos->count()) : 0],
      ['Banho', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_banho') / 3600 / $servicos->count()) : 0],
      ['Revisão', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_revisao') / 3600 / $servicos->count()) : 0],
      ['Expedição', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_expedicao') / 3600 / $servicos->count()) : 0],
    ];
    $dados_tempo_espera = [
      ['Recebimento - Separação', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_recebimento_separacao') / 3600 / $servicos->count()) : 0],
      ['Separação - Catalogação', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_separacao_catalogacao') / 3600 / $servicos->count()) : 0],
      ['Catalogação - Preparação', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_catalogacao_preparacao') / 3600 / $servicos->count()) : 0],
      ['Preparação - Banho', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_preparacao_banho') / 3600 / $servicos->count()) : 0],
      ['Banho - Revisão', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_banho_revisao') / 3600 / $servicos->count()) : 0],
      ['Revisão - Expedição', $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_revisao_expedicao') / 3600 / $servicos->count()) : 0],
    ];

    $dados_tempo_exec_kg = [
      ['Recebimento', $total_peso > 0 ? ceil($servicos->sum('seconds_between_recebimento') / 3600 / $total_peso) : 0],
      ['Separação', $total_peso > 0 ? ceil($servicos->sum('seconds_between_separacao') / 3600 / $total_peso) : 0],
      ['Catalogação', $total_peso > 0 ? ceil($servicos->sum('seconds_between_catalogacao') / 3600 / $total_peso) : 0],
      ['Preparação', $total_peso > 0 ? ceil($servicos->sum('seconds_between_preparacao') / 3600 / $total_peso) : 0],
      ['Banho', $total_peso > 0 ? ceil($servicos->sum('seconds_between_banho') / 3600 / $total_peso) : 0],
      ['Revisão', $total_peso > 0 ? ceil($servicos->sum('seconds_between_revisao') / 3600 / $total_peso) : 0],
      ['Expedição', $total_peso > 0 ? ceil($servicos->sum('seconds_between_expedicao') / 3600 / $total_peso) : 0],
    ];

    $dados_tempo_exec_util = [
      ['Recebimento', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_recebimento') / 3600 / $servicos->count()) : 0],
      ['Separação', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_separacao') / 3600 / $servicos->count()) : 0],
      ['Catalogação', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_catalogacao') / 3600 / $servicos->count()) : 0],
      ['Preparação', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_preparacao') / 3600 / $servicos->count()) : 0],
      ['Banho', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_banho') / 3600 / $servicos->count()) : 0],
      ['Revisão', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_revisao') / 3600 / $servicos->count()) : 0],
      ['Expedição', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_expedicao') / 3600 / $servicos->count()) : 0],
    ];
    $dados_tempo_espera_util = [
      ['Recebimento - Separação', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_recebimento_separacao') / 3600 / $servicos->count()) : 0],
      ['Separação - Catalogação', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_separacao_catalogacao') / 3600 / $servicos->count()) : 0],
      ['Catalogação - Preparação', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_catalogacao_preparacao') / 3600 / $servicos->count()) : 0],
      ['Preparação - Banho', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_preparacao_banho') / 3600 / $servicos->count()) : 0],
      ['Banho - Revisão', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_banho_revisao') / 3600 / $servicos->count()) : 0],
      ['Revisão - Expedição', $servicos->count() > 0 ? ceil($servicos->sum('business_time_seconds_between_revisao_expedicao') / 3600 / $servicos->count()) : 0],
    ];

    $dados_tempo_exec_kg_util = [
      ['Recebimento', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_recebimento') / 3600 / $total_peso) : 0],
      ['Separação', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_separacao') / 3600 / $total_peso) : 0],
      ['Catalogação', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_catalogacao') / 3600 / $total_peso) : 0],
      ['Preparação', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_preparacao') / 3600 / $total_peso) : 0],
      ['Banho', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_banho') / 3600 / $total_peso) : 0],
      ['Revisão', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_revisao') / 3600 / $total_peso) : 0],
      ['Expedição', $total_peso > 0 ? ceil($servicos->sum('business_time_seconds_between_expedicao') / 3600 / $total_peso) : 0],
    ];

    $result = [
      'total_servicos' => $total_servicos,
      'total_peso' => $total_peso,
      'total_itens' => $total_itens,
      'tempo_total' => round($servicos->sum('total_seconds_tempo_execucao') / 3600),
      'tempo_medio_serv' => $servicos->count() > 0 ? round($servicos->sum('total_seconds_tempo_execucao') / 3600 / $servicos->count()) : 0,
      'tempo_medio_item' => $servicos->count() > 0 ? round($servicos->sum('total_seconds_tempo_execucao') / 3600 / $servicos->sum('total_itens_catalogacao')) : 0,
      'dados_tempo_exec' => $dados_tempo_exec,
      'dados_tempo_espera' => $dados_tempo_espera,
      'dados_tempo_exec_kg' => $dados_tempo_exec_kg,
      'dados_tempo_exec_util' => $dados_tempo_exec_util,
      'dados_tempo_espera_util' => $dados_tempo_espera_util,
      'dados_tempo_exec_kg_util' => $dados_tempo_exec_kg_util,
      'view' => view('dashboard_tempo._dados', compact('servicos'))->render(),
    ];

    return response()->json($result);
  }
}

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
    $tempo_execucao_total = $servicos->sum('total_seconds_tempo_execucao') / 3600;

    $tempo_recebimento = $servicos->sum('seconds_between_recebimento') / 3600;
    $tempo_separacao = $servicos->sum('seconds_between_separacao') / 3600;
    $tempo_catalogacao = $servicos->sum('seconds_between_catalogacao') / 3600;
    $tempo_preparacao = $servicos->sum('seconds_between_preparacao') / 3600;
    $tempo_banho = $servicos->sum('seconds_between_banho') / 3600;
    $tempo_revisao = $servicos->sum('seconds_between_revisao') / 3600;
    $tempo_expedicao = $servicos->sum('seconds_between_expedicao') / 3600;

    $tempo_recebimento_separacao = $servicos->sum('seconds_between_recebimento_separacao') / 3600;
    $tempo_separacao_catalogacao = $servicos->sum('seconds_between_separacao_catalogacao') / 3600;
    $tempo_catalogacao_preparacao = $servicos->sum('seconds_between_catalogacao_preparacao') / 3600;
    $tempo_preparacao_banho = $servicos->sum('seconds_between_preparacao_banho') / 3600;
    $tempo_banho_revisao = $servicos->sum('seconds_between_banho_revisao') / 3600;
    $tempo_revisao_expedicao = $servicos->sum('seconds_between_revisao_expedicao') / 3600;

    $tempo_util_recebimento = $servicos->sum('business_time_seconds_between_recebimento') / 3600;
    $tempo_util_separacao = $servicos->sum('business_time_seconds_between_separacao') / 3600;
    $tempo_util_catalogacao = $servicos->sum('business_time_seconds_between_catalogacao') / 3600;
    $tempo_util_preparacao = $servicos->sum('business_time_seconds_between_preparacao') / 3600;
    $tempo_util_banho = $servicos->sum('business_time_seconds_between_banho') / 3600;
    $tempo_util_revisao = $servicos->sum('business_time_seconds_between_revisao') / 3600;
    $tempo_util_expedicao = $servicos->sum('business_time_seconds_between_expedicao') / 3600;

    $tempo_util_recebimento_separacao = $servicos->sum('business_time_seconds_between_recebimento_separacao') / 3600;
    $tempo_util_separacao_catalogacao = $servicos->sum('business_time_seconds_between_separacao_catalogacao') / 3600;
    $tempo_util_catalogacao_preparacao = $servicos->sum('business_time_seconds_between_catalogacao_preparacao') / 3600;
    $tempo_util_preparacao_banho = $servicos->sum('business_time_seconds_between_preparacao_banho') / 3600;
    $tempo_util_banho_revisao = $servicos->sum('business_time_seconds_between_banho_revisao') / 3600;
    $tempo_util_revisao_expedicao = $servicos->sum('business_time_seconds_between_revisao_expedicao') / 3600;

    $dados_tempo_exec = [
      ['Recebimento', $total_servicos > 0 ? ceil($tempo_recebimento / $total_servicos) : 0],
      ['Separação', $total_servicos > 0 ? ceil($tempo_separacao / $total_servicos) : 0],
      ['Catalogação', $total_servicos > 0 ? ceil($tempo_catalogacao / $total_servicos) : 0],
      ['Preparação', $total_servicos > 0 ? ceil($tempo_preparacao / $total_servicos) : 0],
      ['Banho', $total_servicos > 0 ? ceil($tempo_banho / $total_servicos) : 0],
      ['Revisão', $total_servicos > 0 ? ceil($tempo_revisao / $total_servicos) : 0],
      ['Expedição', $total_servicos > 0 ? ceil($tempo_expedicao / $total_servicos) : 0],
    ];
    $dados_tempo_espera = [
      ['Recebimento - Separação', $total_servicos > 0 ? ceil($tempo_recebimento_separacao / $total_servicos) : 0],
      ['Separação - Catalogação', $total_servicos > 0 ? ceil($tempo_separacao_catalogacao / $total_servicos) : 0],
      ['Catalogação - Preparação', $total_servicos > 0 ? ceil($tempo_catalogacao_preparacao / $total_servicos) : 0],
      ['Preparação - Banho', $total_servicos > 0 ? ceil($tempo_preparacao_banho / $total_servicos) : 0],
      ['Banho - Revisão', $total_servicos > 0 ? ceil($tempo_banho_revisao / $total_servicos) : 0],
      ['Revisão - Expedição', $total_servicos > 0 ? ceil($tempo_revisao_expedicao / $total_servicos) : 0],
    ];

    $dados_tempo_exec_kg = [
      ['Recebimento', $total_peso > 0 ? ceil($tempo_recebimento / $total_peso) : 0],
      ['Separação', $total_peso > 0 ? ceil($tempo_separacao / $total_peso) : 0],
      ['Catalogação', $total_peso > 0 ? ceil($tempo_catalogacao / $total_peso) : 0],
      ['Preparação', $total_peso > 0 ? ceil($tempo_preparacao / $total_peso) : 0],
      ['Banho', $total_peso > 0 ? ceil($tempo_banho / $total_peso) : 0],
      ['Revisão', $total_peso > 0 ? ceil($tempo_revisao / $total_peso) : 0],
      ['Expedição', $total_peso > 0 ? ceil($tempo_expedicao / $total_peso) : 0],
    ];

    $dados_tempo_exec_util = [
      ['Recebimento', $total_servicos > 0 ? ceil($tempo_util_recebimento / $total_servicos) : 0],
      ['Separação', $total_servicos > 0 ? ceil($tempo_util_separacao / $total_servicos) : 0],
      ['Catalogação', $total_servicos > 0 ? ceil($tempo_util_catalogacao / $total_servicos) : 0],
      ['Preparação', $total_servicos > 0 ? ceil($tempo_util_preparacao / $total_servicos) : 0],
      ['Banho', $total_servicos > 0 ? ceil($tempo_util_banho / $total_servicos) : 0],
      ['Revisão', $total_servicos > 0 ? ceil($tempo_util_revisao / $total_servicos) : 0],
      ['Expedição', $total_servicos > 0 ? ceil($tempo_util_expedicao / $total_servicos) : 0],
    ];
    $dados_tempo_espera_util = [
      ['Recebimento - Separação', $total_servicos > 0 ? ceil($tempo_util_recebimento_separacao / $total_servicos) : 0],
      ['Separação - Catalogação', $total_servicos > 0 ? ceil($tempo_util_separacao_catalogacao / $total_servicos) : 0],
      ['Catalogação - Preparação', $total_servicos > 0 ? ceil($tempo_util_catalogacao_preparacao / $total_servicos) : 0],
      ['Preparação - Banho', $total_servicos > 0 ? ceil($tempo_util_preparacao_banho / $total_servicos) : 0],
      ['Banho - Revisão', $total_servicos > 0 ? ceil($tempo_util_banho_revisao / $total_servicos) : 0],
      ['Revisão - Expedição', $total_servicos > 0 ? ceil($tempo_util_revisao_expedicao / $total_servicos) : 0],
    ];

    $dados_tempo_exec_kg_util = [
      ['Recebimento', $total_peso > 0 ? ceil($tempo_util_recebimento / $total_peso) : 0],
      ['Separação', $total_peso > 0 ? ceil($tempo_util_separacao / $total_peso) : 0],
      ['Catalogação', $total_peso > 0 ? ceil($tempo_util_catalogacao / $total_peso) : 0],
      ['Preparação', $total_peso > 0 ? ceil($tempo_util_preparacao / $total_peso) : 0],
      ['Banho', $total_peso > 0 ? ceil($tempo_util_banho / $total_peso) : 0],
      ['Revisão', $total_peso > 0 ? ceil($tempo_util_revisao / $total_peso) : 0],
      ['Expedição', $total_peso > 0 ? ceil($tempo_util_expedicao / $total_peso) : 0],
    ];

    $result = [
      'total_servicos' => $total_servicos,
      'total_peso' => $total_peso,
      'total_itens' => $total_itens,
      'tempo_total' => round($tempo_execucao_total),
      'tempo_medio_serv' => $total_servicos > 0 ? round($tempo_execucao_total / $total_servicos) : 0,
      'tempo_medio_item' => $total_itens > 0 ? round($tempo_execucao_total / $total_itens) : 0,
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

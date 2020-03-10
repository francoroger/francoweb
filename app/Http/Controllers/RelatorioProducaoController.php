<?php

namespace App\Http\Controllers;

use App\Reforco;
use App\Tanque;
use App\TanqueCiclo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use PDF;
use Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RelatorioProducaoController extends Controller
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
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return view('relatorios.producao.index');
  }

  /**
  * Do search according to request criteria.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return Illuminate\Support\Collection
  */
  private function search(Request $request)
  {
    $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini)->startOfDay();
    $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim)->endOfDay();

    //Com Cliente
    $ciclos = DB::table('passagem_pecas')->select([
      DB::raw('date(passagem_pecas.data_servico) as data_serv'),
      DB::raw('cliente.nome as cliente'),
      DB::raw('tiposervico.descricao as tipo_servico'),
      DB::raw('material.descricao as material'),
      DB::raw('cores.descricao as variacao_cor'),
      DB::raw('passagem_pecas.milesimos'),
      DB::raw('sum(passagem_pecas.peso) as total_peso'),
    ])->leftJoin('cliente', 'passagem_pecas.cliente_id', '=', 'cliente.id')
      ->leftJoin('tiposervico', 'passagem_pecas.tiposervico_id', '=', 'tiposervico.id')
      ->leftJoin('material', 'passagem_pecas.material_id', '=', 'material.id')
      ->leftJoin('cores', 'passagem_pecas.cor_id', '=', 'cores.id')
      ->groupBy(DB::raw('date(passagem_pecas.data_servico), cliente.nome, tiposervico.descricao, material.descricao, cores.descricao, passagem_pecas.milesimos'))
      ->orderByRaw('date(passagem_pecas.data_servico), cliente.nome, tiposervico.descricao, material.descricao, cores.descricao, passagem_pecas.milesimos');
    $ciclos->whereBetween('data_servico', [$dtini, $dtfim]);
    $ciclos = $ciclos->get();

    //Sem Cliente
    $cicSemCli = DB::table('passagem_pecas')->select([
      DB::raw('date(passagem_pecas.data_servico) as data_serv'),
      DB::raw('tiposervico.descricao as tipo_servico'),
      DB::raw('material.descricao as material'),
      DB::raw('cores.descricao as variacao_cor'),
      DB::raw('passagem_pecas.milesimos'),
      DB::raw('sum(passagem_pecas.peso) as total_peso'),
    ])->leftJoin('tiposervico', 'passagem_pecas.tiposervico_id', '=', 'tiposervico.id')
      ->leftJoin('material', 'passagem_pecas.material_id', '=', 'material.id')
      ->leftJoin('cores', 'passagem_pecas.cor_id', '=', 'cores.id')
      ->groupBy(DB::raw('date(passagem_pecas.data_servico), tiposervico.descricao, material.descricao, cores.descricao, passagem_pecas.milesimos'))
      ->orderByRaw('date(passagem_pecas.data_servico), tiposervico.descricao, material.descricao, cores.descricao, passagem_pecas.milesimos');
    $cicSemCli->whereBetween('data_servico', [$dtini, $dtfim]);
    $cicSemCli = $cicSemCli->get();

    $result = [];
    $result['ComCliente'] = $ciclos;
    $result['SemCliente'] = $cicSemCli;

    $result = collect($result);

    return $result;
  }

  private function paginate($items, $perPage = 10, $page = null, $options = [])
  {
	  $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
	  $items = $items instanceof Collection ? $items : Collection::make($items);
	  return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
  }

  /**
  * Post preview.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function preview(Request $request)
  {
    $itens = $this->search($request);

    $itens = $this->paginate($itens);

    return response()->json(['view' => view('relatorios.producao.preview', ['itens' => $itens])->render()]);
  }

  /**
  * Export to PDF.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function print(Request $request)
  {
    $itens = $this->search($request);

    $pdf = \App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('a4', 'portrait');
    $pdf->loadView('relatorios.producao.print', [
      'itens' => $itens,
    ]);

    return $pdf->stream('relatorio_producao.pdf');

  }
}

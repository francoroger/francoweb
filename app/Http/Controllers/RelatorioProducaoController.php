<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Cor;
use App\Material;
use App\TipoServico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
    $clientes = Cliente::select(['id','nome'])->orderBy('nome')->get();
    $tipos = TipoServico::whereHas('processos_tanque')->orderBy('descricao')->get();
    $materiais = Material::select(['id','descricao'])->orderBy('descricao')->get();
    $cores = Cor::select(['id','descricao'])->orderBy('descricao')->get();
    
    return view('relatorios.producao.index')->with([
      'clientes' => $clientes,
      'tipos' => $tipos,
      'materiais' => $materiais,
      'cores' => $cores,
    ]);
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

    if ($request->idcliente) {
      $ciclos->whereIn('cliente_id', $request->idcliente);
    }

    if ($request->idtiposervico) {
      $ciclos->whereIn('tiposervico_id', $request->idtiposervico);
    }

    if ($request->idmaterial) {
      $ciclos->whereIn('material_id', $request->idmaterial);
    }

    if ($request->idcor) {
      $ciclos->whereIn('cor_id', $request->idcor);
    }

    if ($request->milini && $request->milfim) {
      $ciclos->whereBetween('milesimos', [$request->milini, $request->milfim]);
    }

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

    if ($request->idcliente) {
      $cicSemCli->whereIn('cliente_id', $request->idcliente);
    }

    if ($request->idtiposervico) {
      $cicSemCli->whereIn('tiposervico_id', $request->idtiposervico);
    }

    if ($request->idmaterial) {
      $cicSemCli->whereIn('material_id', $request->idmaterial);
    }

    if ($request->idcor) {
      $cicSemCli->whereIn('cor_id', $request->idcor);
    }

    if ($request->milini && $request->milfim) {
      $cicSemCli->whereBetween('milesimos', [$request->milini, $request->milfim]);
    }

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

    //return view('relatorios.producao.print', compact('itens'));

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('a4', 'portrait');
    $pdf->loadView('relatorios.producao.print', [
      'itens' => $itens,
    ]);

    return $pdf->stream('relatorio_producao.pdf');

  }
}

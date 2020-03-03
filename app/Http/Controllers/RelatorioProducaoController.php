<?php

namespace App\Http\Controllers;

use App\Reforco;
use App\Tanque;
use App\TanqueCiclo;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
  * @return \App\TanqueCiclo
  */
  private function search(Request $request)
  {
    $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini)->startOfDay();
    $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim)->endOfDay();

    $ciclos = TanqueCiclo::select(['data_servico', 'peso', 'cliente_id', 'tiposervico_id', 'material_id', 'cor_id', 'milesimos'])->distinct()->whereBetween('data_servico', [$dtini, $dtfim]);

    if ($request->idtanque) {
      $ciclos->where('tanque_id', $request->idtanque);
    }

    $ciclos->orderBy('data_servico');

    $ciclos = $ciclos->get();

    $result = [];
    foreach ($ciclos as $ciclo) {
      $result[] = (object) [
        'data_servico' => date('d/m/Y', strtotime($ciclo->data_servico)),
        'cliente' => $ciclo->cliente->nome ?? '',
        'tipo_servico' => $ciclo->tipo_servico->descricao ?? '',
        'material' => $ciclo->material->descricao ?? '',
        'cor' => $ciclo->cor->descricao ?? '',
        'milesimos' => $ciclo->milesimos,
        'peso' => $ciclo->peso
      ];
    }

    $result = collect($result);
    $result = $result->sortBy('data_servico');

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
    $itens = $itens->groupBy('data_servico');


    $pdf = \App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('a4', 'portrait');
    $pdf->loadView('relatorios.producao.print', [
      'itens' => $itens,
    ]);

    return $pdf->stream('relatorio_producao.pdf');

  }
}

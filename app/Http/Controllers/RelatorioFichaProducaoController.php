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

class RelatorioFichaProducaoController extends Controller
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
    $tanques = Tanque::select(['id','descricao'])->orderBy('descricao')->get();
    return view('relatorios.ficha_producao.index')->with([
      'tanques' => $tanques,
    ]);
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

    $ciclos = TanqueCiclo::whereBetween('data_servico', [$dtini, $dtfim]);
    $reforcos = Reforco::whereBetween('created_at', [$dtini, $dtfim]);

    if ($request->idtanque) {
      $ciclos->where('tanque_id', $request->idtanque);
      $reforcos->where('tanque_id', $request->idtanque);
    }

    $ciclos->orderBy('data_servico');
    $reforcos->orderBy('created_at');

    $ciclos = $ciclos->get();
    $reforcos = $reforcos->get();

    $result = [];
    foreach ($ciclos as $ciclo) {
      $result[] = (object) [
        'tipo' => 'S',
        'data' => $ciclo->data_servico,
        'peso' => $ciclo->peso,
      ];
    }

    foreach ($reforcos as $reforco) {
      $result[] = (object) [
        'tipo' => 'R',
        'data' => \Carbon\Carbon::parse($reforco->created_at)->subSeconds(1),
        'peso' => 0,
      ];
    }

    $result = collect($result);
    $result = $result->sortBy('data');

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

    return response()->json(['view' => view('relatorios.ficha_producao.preview', ['itens' => $itens])->render()]);
  }

  /**
  * Export to PDF.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function print(Request $request)
  {
    $tanque = Tanque::findOrFail($request->idtanque);

    $itens = $this->search($request);
    $itens = $itens->split(2);

    $pdf = \App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('a4', 'portrait');
    $pdf->loadView('relatorios.ficha_producao.print', [
      'tanque' => $tanque->descricao,
      'ciclo' => $tanque->ciclo_reforco,
      'itens' => $itens,
    ]);

    return $pdf->stream('relatorio_ficha_producao.pdf');

  }
}

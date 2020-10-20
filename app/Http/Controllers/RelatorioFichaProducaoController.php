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
use Illuminate\Support\Facades\App;

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

    $ciclos = TanqueCiclo::whereBetween('data_servico', [$dtini, $dtfim])->withTrashed();
    $reforcos = Reforco::whereBetween('created_at', [$dtini, $dtfim])->withTrashed();

    $ciclos->where('tanque_id', $request->idtanque);
    $reforcos->where('tanque_id', $request->idtanque);
    $tanque = Tanque::findOrFail($request->idtanque);

    $ciclos->orderBy('data_servico');
    $reforcos->orderBy('created_at');

    $ciclos = $ciclos->get();
    $reforcos = $reforcos->get();

    $result = [];
    foreach ($ciclos as $ciclo) {

      $formula = null;
      if ($tanque->tipo_consumo == 'M') {
        $NDesconto = $tanque->desconto_milesimo ? number_format($tanque->desconto_milesimo, 0, ',', '.') : 0;
        $NPeso = number_format($ciclo->peso_peca, 0, ',', '.');
        $NMilesimos = $ciclo->milesimos;
        $formula = "($NPeso * $NMilesimos / 1000) - ($NPeso * $NDesconto / 1000)";
      } 

      $result[] = (object) [
        'tipo' => 'S',
        'data' => $ciclo->data_servico,
        'peso' => $ciclo->peso,
        'excedente' => $ciclo->excedente,
        'peso_antes' => $ciclo->peso_antes,
        'peso_depois' => $ciclo->peso_depois,
        'peso_peca' => $ciclo->peso_peca ?? null,
        'deleted_at' => $ciclo->deleted_at,
        'motivo' => null,
        'formula' => $formula,
      ];
    }

    foreach ($reforcos as $reforco) {
      $result[] = (object) [
        'tipo' => $reforco->tipo == 'A' ? 'A' : 'R',
        'data' => \Carbon\Carbon::parse($reforco->created_at)->subSeconds(1),
        'peso' => $reforco->tanque->ciclo_reforco,
        'excedente' => false,
        'peso_antes' => $reforco->peso_antes,
        'peso_depois' => $reforco->peso_depois,
        'peso_peca' => 0,
        'deleted_at' => $reforco->deleted_at,
        'motivo' => $reforco->motivo_reforco ?? null,
        'formula' => null,
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
    
    $pdf = App::make('dompdf.wrapper');
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

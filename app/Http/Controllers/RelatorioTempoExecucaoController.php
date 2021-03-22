<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Separacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RelatorioTempoExecucaoController extends Controller
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
    
    return view('relatorios.tempo_execucao.index')->with([
      'clientes' => $clientes,
    ]);
  }

  /**
  * Post preview.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function preview(Request $request)
  {
    $lista_etapas = ['Recebimento','Separação','Catalogação','Banho','Revisão','Expedição'];
    $etapas = array_slice($lista_etapas, array_search($request->etapaini, $lista_etapas), array_search($request->etapafim, $lista_etapas) - array_search($request->etapaini, $lista_etapas) + 1);
    
    $servicos = Separacao::with('recebimentos')->whereHas('cliente')->select();

    if ($request->idcliente) {
      $servicos->whereIn('cliente_id', explode(',', $request->idcliente));
    }

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      $servicos->where(function($query)  use ($dtini, $dtfim, $etapas) {
        $query->whereBetween('created_at', [$dtini, $dtfim]);
        if (in_array('Recebimento', $etapas)) {
          $query->orWhereHas('recebimentos', function($query) use ($dtini, $dtfim) {
            $query->whereBetween('data_receb', [$dtini, $dtfim]);
          });
        }
        if (in_array('Catalogação', $etapas) || in_array('Banho', $etapas) || in_array('Revisão', $etapas) || in_array('Expedição', $etapas)) {
          $query->orWhereHas('catalogacao', function($query) use ($dtini, $dtfim) {
            $query->whereBetween('datacad', [$dtini, $dtfim]);
          });
        }
      });
    }
    
    $servicos = $servicos->paginate(10);

    return response()->json(['view' => view('relatorios.tempo_execucao.preview', [
      'servicos' => $servicos,
      'etapas' => $etapas,
    ])->render()]);
  }

  /**
  * Export to PDF.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function print(Request $request)
  {
    $e = explode(';', $request->etapas);
    $lista_etapas = ['Recebimento','Separação','Catalogação','Banho','Revisão','Expedição'];
    $etapas = array_slice($lista_etapas, array_search($e[0], $lista_etapas), array_search($e[1], $lista_etapas) - array_search($e[0], $lista_etapas) + 1);
    
    $servicos = Separacao::with('recebimentos')->select();

    if ($request->idcliente) {
      $servicos->whereIn('cliente_id', $request->idcliente);
    }

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      $servicos->where(function($query)  use ($dtini, $dtfim, $etapas) {
        $query->whereBetween('created_at', [$dtini, $dtfim]);
        if (in_array('Recebimento', $etapas)) {
          $query->orWhereHas('recebimentos', function($query) use ($dtini, $dtfim) {
            $query->whereBetween('data_receb', [$dtini, $dtfim]);
          });
        }
        if (in_array('Catalogação', $etapas) || in_array('Banho', $etapas) || in_array('Revisão', $etapas) || in_array('Expedição', $etapas)) {
          $query->orWhereHas('catalogacao', function($query) use ($dtini, $dtfim) {
            $query->whereBetween('datacad', [$dtini, $dtfim]);
          });
        }
      });
    }
    
    $servicos = $servicos->get();

    switch ($request->output) {
      case 'pdf':
        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('a4', 'landscape');
        $pdf->loadView('relatorios.tempo_execucao.print', [
          'servicos' => $servicos,
          'etapas' => $etapas,
        ]);

        return $pdf->stream('relatorio_tempo_execucao.pdf');
      break;
      case 'print':
        return view('relatorios.tempo_execucao.print', [
          'servicos' => $servicos,
          'etapas' => $etapas,
        ]);
      break;
      default:
        abort(404, 'Opção inválida');
      break;
    }
  }
  
}

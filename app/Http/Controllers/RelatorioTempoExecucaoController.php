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
    $servicos = Separacao::with('recebimentos')->select();

    if ($request->idcliente) {
      $servicos->whereIn('cliente_id', explode(',', $request->idcliente));
    }

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      $servicos->whereBetween('created_at', [$dtini, $dtfim]);
    }
    
    $servicos = $servicos->paginate(10);

    return response()->json(['view' => view('relatorios.tempo_execucao.preview', [
      'servicos' => $servicos,
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
    $servicos = Separacao::with('recebimentos')->select();

    if ($request->idcliente) {
      $servicos->whereIn('cliente_id', $request->idcliente);
    }

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      $servicos->whereBetween('created_at', [$dtini, $dtfim]);
    }
    
    $servicos = $servicos->get();

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('a4', 'landscape');
    $pdf->loadView('relatorios.tempo_execucao.print', [
      'servicos' => $servicos,
    ]);

    return $pdf->stream('relatorio_tempo_execucao.pdf');
  }
  
}

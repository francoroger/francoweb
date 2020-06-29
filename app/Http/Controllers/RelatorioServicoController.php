<?php

namespace App\Http\Controllers;

use App\Cor;
use App\Cliente;
use App\Guia;
use App\Material;
use App\TipoServico;
use App\Servico;
use App\ServicoItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RelatorioServicoController extends Controller
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
    $guias = Guia::select(['id','nome'])->orderBy('nome')->get();
    $tipos = TipoServico::select(['id','descricao'])->orderBy('descricao')->get();
    $cores = Cor::select(['id','descricao'])->orderBy('descricao')->get();
    $materiais = Material::select(['id','descricao'])->orderBy('descricao')->get();
    $camadas = ServicoItem::select(['milesimos'])->distinct()->get();
    $milesimos = collect([
      $camadas->min('milesimos'),
      $camadas->max('milesimos'),
    ]);

    return view('relatorios.servicos.index')->with([
      'clientes' => $clientes,
      'guias' => $guias,
      'tipos' => $tipos,
      'cores' => $cores,
      'materiais' => $materiais,
      'milesimos' => $milesimos,
    ]);
  }

  /**
  * Do search according to request criteria.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \App\ServicoItem
  */
  private function searchDetalhado(Request $request)
  {
    $itens = ServicoItem::select()
                        ->leftJoin('servico', 'idservico', '=', 'servico.id')
                        ->leftJoin('cliente', 'servico.idcliente', '=', 'cliente.id')
                        ->leftJoin('guia', 'servico.idguia', '=', 'guia.id')
                        ->leftJoin('tiposervico', 'idtiposervico', '=', 'tiposervico.id')
                        ->leftJoin('material', 'idmaterial', '=', 'material.id')
                        ->leftJoin('cores', 'idcor', '=', 'cores.id');

    $itens->whereHas('servico', function($query) use ($request) {
      if ($request->dataini && $request->datafim) {
        $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
        $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
        $query->whereBetween('datavenda', [$dtini, $dtfim]);
      }
      if ($request->idcliente) {
        $query->whereIn('idcliente', explode(',', $request->idcliente));
      }
      if ($request->idguia) {
        $query->whereIn('idguia', explode(',', $request->idguia));
      }
    });

    if ($request->idtiposervico) {
      $itens->whereIn('idtiposervico', explode(',', $request->idtiposervico));
    }

    if ($request->idmaterial) {
      $itens->whereIn('idmaterial', explode(',', $request->idmaterial));
    }

    if ($request->idcor) {
      $itens->whereIn('idcor', explode(',', $request->idcor));
    }

    if ($request->milini && $request->milfim) {
      $itens->whereBetween('milesimos', [$request->milini, $request->milfim]);
    }

    $itens->orderBy($request->sortbydet);

    return $itens;
  }

  /**
  * Do search according to request criteria.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \App\Servico
  */
  private function searchResumido(Request $request)
  {
    $servicos = Servico::select([
      'servico.id',
      'servico.datavenda',
      'servico.idcliente',
      'servico.idguia',
    ])->leftJoin('cliente', 'servico.idcliente', '=', 'cliente.id')
      ->leftJoin('guia', 'servico.idguia', '=', 'guia.id');

    $servicos->whereHas('itens', function($query) use ($request) {
      if ($request->idtiposervico) {
        $query->whereIn('idtiposervico', explode(',', $request->idtiposervico));
      }

      if ($request->idmaterial) {
        $query->whereIn('idmaterial', explode(',', $request->idmaterial));
      }

      if ($request->idcor) {
        $query->whereIn('idcor', explode(',', $request->idcor));
      }

      if ($request->milini && $request->milfim) {
        $query->whereBetween('milesimos', [$request->milini, $request->milfim]);
      }
    });

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      $servicos->whereBetween('datavenda', [$dtini, $dtfim]);
    }
    if ($request->idcliente) {
      $servicos->whereIn('idcliente', explode(',', $request->idcliente));
    }
    if ($request->idguia) {
      $servicos->whereIn('idguia', explode(',', $request->idguia));
    }

    $servicos->orderBy($request->sortbyres);

    return $servicos;
  }

  /**
  * Post preview.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function preview(Request $request)
  {
    switch ($request->modelo) {
      case 'D':
        $itens = $this->searchDetalhado($request);

        $total['valor'] = $itens->sum('valor');
        $total['peso'] = $itens->sum('peso') / 100;

        $itens = $itens->paginate(10);

        return response()->json(['view' => view('relatorios.servicos.preview_detalhado', ['itens' => $itens, 'total' => $total])->render()]);
        break;
      case 'R':
        $servicos = $this->searchResumido($request);

        $total['valor'] = 0;
        $total['peso'] = 0;
        $calc = $servicos->get();
        foreach ($calc as $servico) {
          $total['valor'] += $servico->itens->sum('valor');
          $total['peso'] += $servico->itens->sum('peso');
        }
        $total['peso'] = $total['peso'] / 100;

        $servicos = $servicos->paginate(10);

        return response()->json(['view' => view('relatorios.servicos.preview_resumido', ['servicos' => $servicos, 'total' => $total])->render()]);
        break;
    }
  }

  /**
  * Export to PDF.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function print(Request $request)
  {
    switch ($request->modelo) {
      case 'D':
        $itens = $this->searchDetalhado($request);

        $total['valor'] = $itens->sum('valor');
        $total['peso'] = $itens->sum('peso') / 100;

        $itens = $itens->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('a4', 'landscape');
        $pdf->loadView('relatorios.servicos.print_detalhado', [
          'itens' => $itens,
          'total' => $total
        ]);

        return $pdf->stream('relatorio_servicos.pdf');

        break;
      case 'R':
        // code...
        break;
    }
  }

}

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
use Illuminate\Pagination\LengthAwarePaginator;
Use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

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
        $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini)->toDateString();
        $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim)->toDateString();
        $query->whereBetween('datavenda', [$dtini, $dtfim]);
      }
      if ($request->idcliente) {
        $query->whereIn('idcliente', is_array($request->idcliente) ? $request->idcliente : explode(',', $request->idcliente));
      }
      if ($request->idguia) {
        $query->whereIn('idguia', is_array($request->idguia) ? $request->idguia : explode(',', $request->idguia));
      }
    });

    if ($request->idtiposervico) {
      $itens->whereIn('idtiposervico', is_array($request->idtiposervico) ? $request->idtiposervico  : explode(',', $request->idtiposervico));
    }

    if ($request->idmaterial) {
      $itens->whereIn('idmaterial', is_array($request->idmaterial) ? $request->idmaterial : explode(',', $request->idmaterial));
    }

    if ($request->idcor) {
      $itens->whereIn('idcor', is_array($request->idcor) ? $request->idcor : explode(',', $request->idcor));
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
    $servicos = Servico::select();

    $servicos->whereHas('itens', function($query) use ($request) {
      if ($request->idtiposervico) {
        $query->whereIn('idtiposervico', is_array($request->idtiposervico) ? $request->idtiposervico  : explode(',', $request->idtiposervico));
      }

      if ($request->idmaterial) {
        $query->whereIn('idmaterial', is_array($request->idmaterial) ? $request->idmaterial : explode(',', $request->idmaterial));
      }

      if ($request->idcor) {
        $query->whereIn('idcor', is_array($request->idcor) ? $request->idcor : explode(',', $request->idcor));
      }

      if ($request->milini && $request->milfim) {
        $query->whereBetween('milesimos', [$request->milini, $request->milfim]);
      }
    });

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini)->toDateString();
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim)->toDateString();
      $servicos->whereBetween('datavenda', [$dtini, $dtfim]);
    }
    if ($request->idcliente) {
      $servicos->whereIn('idcliente', is_array($request->idcliente) ? $request->idcliente : explode(',', $request->idcliente));
    }
    if ($request->idguia) {
      $servicos->whereIn('idguia', is_array($request->idguia) ? $request->idguia : explode(',', $request->idguia));
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
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $itens = $itens->paginate(10);

        return response()->json(['view' => view('relatorios.servicos.preview_detalhado', ['itens' => $itens, 'total' => $total])->render()]);
        break;
      case 'R':
        $servicos = $this->searchResumido($request);

        //Totalizadores com base nos itens
        //Antes estava pegando a soma pelos serviços e não batia
        $itens = $this->searchDetalhado($request);
        
        $total['valor'] = $itens->sum('valor');
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $servicos = $servicos->paginate(10);

        return response()->json(['view' => view('relatorios.servicos.preview_resumido', ['servicos' => $servicos, 'total' => $total])->render()]);
      break;
      case 'A':
        $itens = $this->searchDetalhado($request);

        $total['valor'] = $itens->sum('valor');
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $itens = $itens->get();

        //Agrupamento
        if ($request->grupos) {
          $grupos = is_array($request->grupos) ? $request->grupos : explode(',', $request->grupos);

          $itens = $itens->groupBy($grupos);
          $itens = $itens->sortBy($grupos);
        } else {
          abort(400, 'Informe pelo menos um grupo!');
        }

        return response()->json(['view' => view('relatorios.servicos.preview_agrupado', ['itens' => $itens, 'total' => $total, 'total_grupos' => count($grupos),])->render()]);
      break;
      case 'AR':
        $itens = $this->searchDetalhado($request);

        $total['valor'] = $itens->sum('valor');
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $itens = $itens->get();

        //Agrupamento
        if ($request->grupos) {
          $grupos = is_array($request->grupos) ? $request->grupos : explode(',', $request->grupos);

          $itens = $itens->groupBy($grupos);
        } else {
          abort(400, 'Informe pelo menos um grupo!');
        }

        return response()->json(['view' => view('relatorios.servicos.preview_agrupado_resumido', ['itens' => $itens, 'total' => $total, 'total_grupos' => count($grupos),])->render()]);
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
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $itens = $itens->get();

        switch ($request->output) {
          case 'pdf':
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('a4', 'landscape');
            $pdf->loadView('relatorios.servicos.print_detalhado', [
              'itens' => $itens,
              'total' => $total
            ]);

            return $pdf->stream('relatorio_servicos.pdf');
          break;
          case 'print':
            return view('relatorios.servicos.print_detalhado')->with([
              'itens' => $itens,
              'total' => $total
            ]);
          break;
          default:
            abort(404, 'Opção inválida');
          break;
        }

      break;
      case 'R':
        $servicos = $this->searchResumido($request);
        //Totalizadores com base nos itens
        //Antes estava pegando a soma pelos serviços e não batia
        $itens = $this->searchDetalhado($request);
        
        $total['valor'] = $itens->sum('valor');
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $servicos = $servicos->get();

        switch ($request->output) {
          case 'pdf':
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('a4', 'landscape');
            $pdf->loadView('relatorios.servicos.print_resumido', [
              'servicos' => $servicos,
              'total' => $total
            ]);

            return $pdf->stream('relatorio_servicos.pdf');
          break;
          case 'print':
            return view('relatorios.servicos.print_resumido')->with([
              'servicos' => $servicos,
              'total' => $total
            ]);
          break;
          default:
            abort(404, 'Opção inválida');
          break;
        }

      break;
      case 'A':
        $itens = $this->searchDetalhado($request);

        $total['valor'] = $itens->sum('valor');
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $itens = $itens->get();

        //Agrupamento
        if ($request->grupos) {
          $grupos = is_array($request->grupos) ? $request->grupos : explode(',', $request->grupos);

          $itens = $itens->groupBy($grupos);
        } else {
          abort(400, 'Informe pelo menos um grupo!');
        }

        switch ($request->output) {
          case 'pdf':
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('a4', 'landscape');
            $pdf->loadView('relatorios.servicos.print_agrupado', [
              'itens' => $itens,
              'total' => $total,
              'total_grupos' => count($grupos),
            ]);

            return $pdf->stream('relatorio_servicos.pdf');
          break;
          case 'print':
            return view('relatorios.servicos.print_agrupado')->with([
              'itens' => $itens,
              'total' => $total,
              'total_grupos' => count($grupos),
            ]);
          break;
          default:
            abort(404, 'Opção inválida');
          break;
        }

      break;
      case 'AR':
        $itens = $this->searchDetalhado($request);

        $total['valor'] = $itens->sum('valor');
        $total['valor_comis'] = $itens->sum('valor_comis');
        $total['peso'] = $itens->sum('peso');
        $total['consumo'] = $itens->get()->sum('consumo');

        $itens = $itens->get();

        //Agrupamento
        if ($request->grupos) {
          $grupos = is_array($request->grupos) ? $request->grupos : explode(',', $request->grupos);

          $itens = $itens->groupBy($grupos);
        } else {
          abort(400, 'Informe pelo menos um grupo!');
        }

        switch ($request->output) {
          case 'pdf':
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('a4', 'landscape');
            $pdf->loadView('relatorios.servicos.print_agrupado_resumido', [
              'itens' => $itens,
              'total' => $total,
              'total_grupos' => count($grupos),
            ]);

            return $pdf->stream('relatorio_servicos.pdf');
          break;
          case 'print':
            return view('relatorios.servicos.print_agrupado_resumido')->with([
              'itens' => $itens,
              'total' => $total,
              'total_grupos' => count($grupos),
            ]);
          break;
          default:
            abort(404, 'Opção inválida');
          break;
        }
      break;  
    }
  }

  public function collection_paginate($items, $perPage = 10, $page = null, $options = [])
  {
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
  }

}

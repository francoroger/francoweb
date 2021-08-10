<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Cor;
use App\Helpers\Utils;
use App\Material;
use App\Retrabalho;
use App\RetrabalhoItem;
use App\TipoFalha;
use App\TipoServico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RelatorioRetrabalhoController extends Controller
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
    $clientes = Cliente::select(['id', 'nome'])->orderBy('nome')->get();
    $tipos = TipoServico::select(['id', 'descricao'])->orderBy('descricao')->get();
    $materiais = Material::select(['id', 'descricao'])->orderBy('descricao')->get();
    $cores = Cor::select(['id', 'descricao'])->orderBy('descricao')->get();
    $tiposFalha = TipoFalha::select(['id', 'descricao'])->orderBy('descricao')->get();

    return view('relatorios.retrabalho.index')->with([
      'clientes' => $clientes,
      'tipos' => $tipos,
      'materiais' => $materiais,
      'cores' => $cores,
      'tiposFalha' => $tiposFalha,
    ]);
  }

  /**
   * Do search according to request criteria.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return Illuminate\Support\Collection
   */
  private function searchDetalhado(Request $request)
  {
    $itens = RetrabalhoItem::select()
      ->leftJoin('retrabalhos', 'retrabalho_id', '=', 'retrabalhos.id')
      ->leftJoin('cliente', 'retrabalhos.cliente_id', '=', 'cliente.id')
      ->leftJoin('tipos_falha', 'retrabalhos.tipo_falha_id', '=', 'tipos_falha.id')
      ->leftJoin('tiposervico', 'tiposervico_id', '=', 'tiposervico.id')
      ->leftJoin('material', 'material_id', '=', 'material.id')
      ->leftJoin('cores', 'cor_id', '=', 'cores.id');

    $itens->whereHas('retrabalho', function ($query) use ($request) {
      if ($request->dataini && $request->datafim) {
        $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini)->toDateString();
        $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim)->toDateString();
        $query->whereBetween('data_inicio', [$dtini, $dtfim]);
      }
      if ($request->idcliente) {
        $query->whereIn('cliente_id', is_array($request->idcliente) ? $request->idcliente : explode(',', $request->idcliente));
      }
    });

    if ($request->idtipofalha) {
      $itens->whereIn('tipo_falha_id', is_array($request->idtipofalha) ? $request->idtipofalha : explode(',', $request->idtipofalha));
    }

    if ($request->idtiposervico) {
      $itens->whereIn('tiposervico_id', is_array($request->idtiposervico) ? $request->idtiposervico : explode(',', $request->idtiposervico));
    }

    if ($request->idmaterial) {
      $itens->whereIn('material_id', is_array($request->idmaterial) ? $request->idmaterial : explode(',', $request->idmaterial));
    }

    if ($request->idcor) {
      $itens->whereIn('cor_id', is_array($request->idcor) ? $request->idcor : explode(',', $request->idcor));
    }

    if ($request->milini && $request->milfim) {
      $itens->whereBetween('milesimos', [$request->milini, $request->milfim]);
    }

    $itens->orderBy($request->sortbydet);

    return $itens->get();
  }

  /**
   * Do search according to request criteria.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return Illuminate\Support\Collection
   */
  private function searchResumido(Request $request)
  {
    $retrabalhos = Retrabalho::select()
      ->leftJoin('cliente', 'retrabalhos.cliente_id', '=', 'cliente.id');

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini)->toDateString();
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim)->toDateString();
      $retrabalhos->whereBetween('data_inicio', [$dtini, $dtfim]);
    }
    if ($request->idcliente) {
      $retrabalhos->whereIn('cliente_id', is_array($request->idcliente) ? $request->idcliente : explode(',', $request->idcliente));
    }

    $retrabalhos->whereHas('itens', function ($query) use ($request) {
      if ($request->idtipofalha) {
        $query->whereIn('tipo_falha_id', is_array($request->idtipofalha) ? $request->idtipofalha : explode(',', $request->idtipofalha));
      }

      if ($request->idtiposervico) {
        $query->whereIn('tiposervico_id', is_array($request->idtiposervico) ? $request->idtiposervico : explode(',', $request->idtiposervico));
      }

      if ($request->idmaterial) {
        $query->whereIn('material_id', is_array($request->idmaterial) ? $request->idmaterial : explode(',', $request->idmaterial));
      }

      if ($request->idcor) {
        $query->whereIn('cor_id', is_array($request->idcor) ? $request->idcor : explode(',', $request->idcor));
      }

      if ($request->milini && $request->milfim) {
        $query->whereBetween('milesimos', [$request->milini, $request->milfim]);
      }
    });

    $retrabalhos->orderBy($request->sortbyres);

    return $retrabalhos->get();
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

        $total['peso'] = $itens->sum('peso');
        $total['itens'] = $itens->count();

        $itens = Utils::paginate($itens);

        return response()->json(['view' => view('relatorios.retrabalho.preview', ['itens' => $itens, 'total' => $total])->render()]);
        break;
      case 'R':
        $retrabalhos = $this->searchResumido($request);

        //Totalizadores com base nos itens
        //Antes estava pegando a soma pelos serviços e não batia
        $itens = $this->searchDetalhado($request);

        $total['peso'] = $itens->sum('peso');
        $total['itens'] = $itens->count();

        $retrabalhos = Utils::paginate($retrabalhos);

        return response()->json(['view' => view('relatorios.retrabalho.preview_resumido', ['retrabalhos' => $retrabalhos, 'total' => $total])->render()]);
        break;
      case 'A':
        $itens = $this->searchDetalhado($request);

        $total['peso'] = $itens->sum('peso');
        $total['itens'] = $itens->count();

        //Agrupamento
        if ($request->grupos) {
          $grupos = is_array($request->grupos) ? $request->grupos : explode(',', $request->grupos);

          $itens = $itens->groupBy($grupos);
          $itens = $itens->sortBy($grupos);
        } else {
          abort(400, 'Informe pelo menos um grupo!');
        }

        return response()->json(['view' => view('relatorios.retrabalho.preview_agrupado', ['itens' => $itens, 'total_grupos' => count($grupos), 'total' => $total])->render()]);
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

        $total['peso'] = $itens->sum('peso');
        $total['itens'] = $itens->count();

        switch ($request->output) {
          case 'pdf':
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('a4', 'landscape');
            $pdf->loadView('relatorios.retrabalho.print', [
              'itens' => $itens,
              'total' => $total,
            ]);

            return $pdf->stream('relatorio_retrabalho.pdf');
            break;
          case 'print':
            return view('relatorios.retrabalho.print')->with([
              'itens' => $itens,
              'total' => $total,
            ]);
            break;
          default:
            abort(404, 'Opção inválida');
            break;
        }

        break;
      case 'R':
        $retrabalhos = $this->searchResumido($request);

        //Totalizadores com base nos itens
        //Antes estava pegando a soma pelos serviços e não batia
        $itens = $this->searchDetalhado($request);

        $total['peso'] = $itens->sum('peso');
        $total['itens'] = $itens->count();

        switch ($request->output) {
          case 'pdf':
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('a4', 'landscape');
            $pdf->loadView('relatorios.retrabalho.print_resumido', [
              'retrabalhos' => $retrabalhos,
              'total' => $total
            ]);

            return $pdf->stream('relatorio_retrabalho.pdf');
            break;
          case 'print':
            return view('relatorios.retrabalho.print_resumido')->with([
              'retrabalhos' => $retrabalhos,
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

        $total['peso'] = $itens->sum('peso');
        $total['itens'] = $itens->count();

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
            $pdf->loadView('relatorios.retrabalho.print_agrupado', [
              'itens' => $itens,
              'total' => $total,
              'total_grupos' => count($grupos),
            ]);

            return $pdf->stream('relatorio_retrabalho.pdf');
            break;
          case 'print':
            return view('relatorios.retrabalho.print_agrupado')->with([
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
}

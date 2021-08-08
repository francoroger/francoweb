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
    $tipos = TipoServico::whereHas('processos_tanque')->orderBy('descricao')->get();
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
  private function search(Request $request)
  {
    $itens = RetrabalhoItem::select();

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
      $itens->whereIn('tipo_falha_id', explode(',', $request->idtipofalha));
    }

    if ($request->idtiposervico) {
      $itens->whereIn('tiposervico_id', explode(',', $request->idtiposervico));
    }

    if ($request->idmaterial) {
      $itens->whereIn('material_id', explode(',', $request->idmaterial));
    }

    if ($request->idcor) {
      $itens->whereIn('cor_id', explode(',', $request->idcor));
    }

    if ($request->milini && $request->milfim) {
      $itens->whereBetween('milesimos', [$request->milini, $request->milfim]);
    }

    return $itens->get();
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

    $itens = Utils::paginate($itens);

    return response()->json(['view' => view('relatorios.retrabalho.preview', ['itens' => $itens])->render()]);
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

    switch ($request->output) {
      case 'pdf':
        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('a4', 'landscape');
        $pdf->loadView('relatorios.retrabalho.print', [
          'itens' => $itens,
        ]);

        return $pdf->stream('relatorio_retrabalho.pdf');
        break;
      case 'print':
        return view('relatorios.retrabalho.print', compact('itens'));
        break;
      default:
        abort(404, 'Opção inválida');
        break;
    }
  }
}

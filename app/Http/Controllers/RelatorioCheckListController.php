<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\Cliente;
use App\Fornecedor;
use App\Helpers\Utils;
use App\Material;
use App\Produto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RelatorioCheckListController extends Controller
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
    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();
    $fornecedores = Fornecedor::select(['id', 'nome', 'ativo'])->orderBy('nome')->get();
    $produtos = Produto::select(['id','descricao'])->orderBy('descricao')->get();
    $materiais = Material::select(['id','descricao'])->orderBy('descricao')->get();
    
    return view('relatorios.checklist.index')->with([
      'clientes' => $clientes,
      'fornecedores' => $fornecedores,
      'produtos' => $produtos,
      'materiais' => $materiais,
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
    $catalogacoes = Catalogacao::where('status', '<>', 'A');

    if ($request->dataini && $request->datafim) {
      $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
      $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
      
      $catalogacoes->whereBetween('datacad', [$dtini, $dtfim]);
    }

    if ($request->idcliente) {
      $catalogacoes->whereIn('idcliente', explode(',', $request->idcliente));
    }

    if ($request->idproduto) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->whereIn('idproduto', explode(',', $request->idproduto));
      });
    }

    if ($request->idmaterial) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->whereIn('idmaterial', explode(',', $request->idmaterial));
      });
    }

    if ($request->idfornec) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->whereIn('idfornec', explode(',', $request->idfornec));
      });
    }

    if ($request->status_check) {
      $filtro_check = explode(',', $request->status_check);
      if (in_array('-', $filtro_check)) {
        $catalogacoes->whereHas('itens', function($query) {
          $query->whereNull('status_check');
        });
      }

      $catalogacoes->whereHas('itens', function($query) use ($filtro_check) {
        $query->whereIn('status_check', $filtro_check);
      });
    }

    if ($request->status) {
      $filtro_status = explode(',', $request->status);
      if (in_array('C', $filtro_status)) {
        array_push($filtro_status, 'L'); 
      }
      $catalogacoes->whereIn('status', $filtro_status);
    }

    return $catalogacoes;
  }

  /**
  * Post preview.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function preview(Request $request)
  {
    $catalogacoes = $this->search($request);

    $catalogacoes = $catalogacoes->get()->sortBy($request->sortby);

    $catalogacoes = Utils::paginate($catalogacoes);
    
    return response()->json(['view' => view('relatorios.checklist.preview', ['catalogacoes' => $catalogacoes])->render()]);
  }

  /**
  * Export to PDF.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function print(Request $request)
  {
    $catalogacoes = $this->search($request);

    $catalogacoes = $catalogacoes->get()->sortBy($request->sortby);

    switch ($request->output) {
      case 'pdf':
        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('a4', 'portrait');
        $pdf->loadView('relatorios.checklist.print', [
          'catalogacoes' => $catalogacoes,
        ]);

        return $pdf->stream('relatorio_checklist.pdf');
      break;
      case 'print':
        return view('relatorios.checklist.print', compact('catalogacoes'));
      break;
      default:
        abort(404, 'Opção inválida');
      break;
    }

  }
}

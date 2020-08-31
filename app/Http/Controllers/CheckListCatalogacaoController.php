<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\CatalogacaoItem;
use App\Fornecedor;
use App\Material;
use App\Produto;
use App\Separacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;

class CheckListCatalogacaoController extends Controller
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
    $materiais = Material::select(['id','descricao'])->orderBy('descricao')->get();
    $produtos = Produto::select(['id','descricao'])->orderBy('descricao')->get();
    $fornecedores = Fornecedor::select(['id','nome'])->orderBy('nome')->get();
    return view('catalogacao_checklist.index')->with([
      'materiais' => $materiais,
      'produtos' => $produtos,
      'fornecedores' => $fornecedores,
    ]);
  }

  public function ajax(Request $request)
  {
    $catalogacoes = Catalogacao::where('status', '<>', 'A');

    if ($request->status) {
      if ($request->status = 'C') {
        $catalogacoes = Catalogacao::whereIn('status', ['C', 'L']);
      } else {
        $catalogacoes = Catalogacao::where('status', $request->status);
      }
    }

    if ($request->status_check) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        if ($request->status_check <> '-') {
          $query->where('status_check', $request->status_check);
        } else {
          $query->whereNull('status_check');
        }
      });
    }

    if ($request->idproduto) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->where('idproduto', $request->idproduto);
      });
    }

    if ($request->idmaterial) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->where('idmaterial', $request->idmaterial);
      });
    }

    if ($request->idfornec) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->where('idfornec', $request->idfornec);
      });
    }

    if ($request->referencia) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->where('referencia', 'like', "%".$request->referencia."%");
      });
    }

    if ($request->pesoini && $request->pesofim) {
      $catalogacoes->whereHas('itens', function($query) use ($request) {
        $query->whereBetween('peso', [$request->pesoini, $request->pesofim]);
      });
    }
    
    $catalogacoes = $catalogacoes->get();
    $data = [];
    foreach ($catalogacoes as $catalogacao) {
      $actions = '<div class="text-nowrap">';
      $actions .= ($catalogacao->status == 'F' || $catalogacao->status == 'G' || $catalogacao->status == 'P') ? '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Check List" href="'.route('catalogacao_checklist.check', $catalogacao->id).'"><i class="icon wb-pencil"></i></a>' : '';
      $actions .= ($catalogacao->status == 'C' || $catalogacao->status == 'L') ? '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('catalogacao_checklist.check', $catalogacao->id).'"><i class="icon wb-pencil"></i></a> <a class="btn btn-sm btn-icon btn-flat btn-primary" title="Visualizar" href="'.route('catalogacao_checklist.show', $catalogacao->id).'"><i class="icon wb-search"></i></a>' : '';
      $actions .= '</div>';
      switch ($catalogacao->status) {
        case 'F':
          $status = '<span class="badge badge-info">Banho/Preparação</span>';
          break;
        case 'G':
          $status = '<span class="badge badge-danger">Aguardando</span>';
          break;
        case 'P':
          $status = '<span class="badge badge-warning">Em Andamento</span>';
          break;
        case 'C':
          $status = '<span class="badge badge-success">Concluída</span>';
          break;
        case 'L':
          $status = '<span class="badge badge-success">Concluída</span>';
          break;
        default:
          break;
      }

      $data[] = [
        'id' => $catalogacao->id,
        'cliente' => $catalogacao->cliente->identificacao ?? '',
        'datacad' => date('d/m/Y', strtotime($catalogacao->datacad)),
        'horacad' => date('H:i', strtotime($catalogacao->horacad)),
        'status' => $status,
        'actions' => $actions,
      ];
    }
    $data = [
      'data' => $data
    ];
    return response()->json($data);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $catalogacao = Catalogacao::findOrFail($id);

    $itens = $catalogacao->itens->sortBy(function($item) {
      return sprintf('%-12s%s', $item->descricao_produto, $item->fornecedor->nome ?? '', $item->preco_bruto);
    });

    $produtos = $catalogacao->itens->unique('produto.descricao')->sortBy('produto.descricao')->pluck('produto.descricao', 'produto.id');
    $materiais = $catalogacao->itens->unique('material.descricao')->sortBy('material.descricao')->pluck('material.descricao', 'material.id');

    return view('catalogacao_checklist.show')->with([
      'catalogacao' => $catalogacao,
      'itens' => $itens,
      'produtos' => $produtos,
      'materiais' => $materiais,
    ]);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function check($id)
  {
    $catalogacao = Catalogacao::findOrFail($id);

    $itens = $catalogacao->itens->sortBy(function($item) {
      return sprintf('%-12s%s', $item->descricao_produto, $item->fornecedor->nome ?? '', $item->preco_bruto);
    });

    $produtos = $catalogacao->itens->unique('produto.descricao')->sortBy('produto.descricao')->pluck('produto.descricao', 'produto.id');
    $materiais = $catalogacao->itens->unique('material.descricao')->sortBy('material.descricao')->pluck('material.descricao', 'material.id');

    return view('catalogacao_checklist.check')->with([
      'catalogacao' => $catalogacao,
      'itens' => $itens,
      'produtos' => $produtos,
      'materiais' => $materiais,
    ]);
  }

  /**
  * Salva o conteúdo preenchido via ajax.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function autosave(Request $request)
  {
    $itens = json_decode($request->itens);
    foreach ($itens as $item) {
      if ($item->status_check || $item->obs_check) {
        $catalogacao_item = CatalogacaoItem::findOrFail($item->id);
        $catalogacao_item->status_check = $item->status_check;
        $catalogacao_item->obs_check = $item->obs_check;
        $catalogacao_item->save();
      }
    }

    $catalogacao = Catalogacao::findOrFail($request->id);
    
    $catalogacao->status = 'P';
    $catalogacao->save();

    $separacao = Separacao::where('catalogacao_id', $catalogacao->id)->get()->first();
    $separacao->status = 'P';

    if (!$separacao->data_fim_banho) {
      $separacao->data_fim_banho = Carbon::now();
    }

    if (!$separacao->data_inicio_revisao) {
      $separacao->data_inicio_revisao = Carbon::now();
    }

    $separacao->save();

    return response(200);
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    if ($request->itens) {
      foreach ($request->itens as $item) {
        $catalogacao_item = CatalogacaoItem::findOrFail($item['id']);
        $catalogacao_item->status_check = $item['status_check'];
        $catalogacao_item->obs_check = $item['obs_check'];
        $catalogacao_item->save();
      }
    }

    $catalogacao = Catalogacao::findOrFail($id);
    
    $catalogacao->status = $request->status;
    $catalogacao->save();

    $separacao = Separacao::where('catalogacao_id', $catalogacao->id)->get()->first();
    $separacao->status = $request->status;

    if (!$separacao->data_fim_banho) {
      $separacao->data_fim_banho = Carbon::now();
    }

    if (!$separacao->data_inicio_revisao) {
      $separacao->data_inicio_revisao = Carbon::now();
    }

    if ($request->status == 'C') {
      $separacao->data_fim_revisao = Carbon::now();
      $separacao->data_inicio_expedicao = Carbon::now();
    }

    $separacao->save();

    return redirect()->route('catalogacao_checklist.index');
  }

  /**
  * Export to PDF.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function print(Request $request, $id)
  {
    $catalogacao = Catalogacao::findOrFail($id);

    $itens = $catalogacao->itens->sortBy(function($item) {
      return sprintf('%-12s%s', $item->descricao_produto, $item->fornecedor->nome ?? '', $item->preco_bruto);
    });

    if ($request->filtro_material) {
      $itens = $itens->where('idmaterial', $request->filtro_material);
    }

    if ($request->filtro_produto) {
      $itens = $itens->where('idproduto', $request->filtro_produto);
    }

    if ($request->filtro_status) {
      switch ($request->filtro_status) {
        case 'V':
          $itens = $itens->where('status_check', '!=', null);
          break;
        case 'S':
          $itens = $itens->where('status_check', 'S');
          break;
        case 'N':
          $itens = $itens->where('status_check', 'N');
          break;
        case 'P':
          $itens = $itens->where('status_check', null);
          break;
      }
    }

    /*return view('catalogacao_checklist.print')->with([
      'catalogacao' => $catalogacao,
      'itens' => $itens,
    ]);*/

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadView('catalogacao_checklist.print', [
      'catalogacao' => $catalogacao,
      'itens' => $itens,
    ]);

    return $pdf->stream('checklist.pdf');
  }
}

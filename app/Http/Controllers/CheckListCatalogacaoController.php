<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\CatalogacaoEdicao;
use App\CatalogacaoItem;
use App\Fornecedor;
use App\Material;
use App\Produto;
use App\Separacao;
use App\TipoFalha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
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
    $materiais = Material::select(['id', 'descricao'])->orderBy('descricao')->get();
    $produtos = Produto::select(['id', 'descricao'])->orderBy('descricao')->get();
    $fornecedores = Fornecedor::select(['id', 'nome'])->orderBy('nome')->get();
    $tiposFalha = TipoFalha::select(['id', 'descricao'])->orderBy('descricao')->get();
    return view('catalogacao_checklist.index')->with([
      'materiais' => $materiais,
      'produtos' => $produtos,
      'fornecedores' => $fornecedores,
      'tiposFalha' => $tiposFalha,
    ]);
  }

  public function ajax(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get('start');
    $length = $request->get('length');
    $search = $request->get('search');
    $order = $request->get('order');
    $columns = $request->get('columns');

    $query = Catalogacao::query();

    // Filtros
    if (!empty($request->get('idproduto'))) {
      $query->where('idproduto', $request->get('idproduto'));
    }
    if (!empty($request->get('idmaterial'))) {
      $query->where('idmaterial', $request->get('idmaterial'));
    }
    if (!empty($request->get('idfornec'))) {
      $query->where('idfornec', $request->get('idfornec'));
    }
    if (!empty($request->get('referencia'))) {
      $query->where('referencia', 'like', '%' . $request->get('referencia') . '%');
    }
    if (!empty($request->get('status'))) {
      $query->where('status', $request->get('status'));
    }
    if (!empty($request->get('status_check'))) {
      $query->where('status_check', $request->get('status_check'));
    }

    // Busca global
    if (!empty($search['value'])) {
      $query->where(function($q) use ($search) {
        $q->where('id', 'like', '%' . $search['value'] . '%')
          ->orWhere('cliente', 'like', '%' . $search['value'] . '%');
      });
    }

    // Total de registros
    $recordsTotal = $query->count();
    $recordsFiltered = $recordsTotal;

    // Ordenação
    if (isset($order[0])) {
      $column = $columns[$order[0]['column']];
      $dir = $order[0]['dir'];
      if ($column['data'] != 'actions') {
        $query->orderBy($column['data'], $dir);
      }
    }

    // Paginação
    $query->skip($start)->take($length);

    // Dados
    $data = $query->get()->map(function($item) {
      $actions = '<div class="text-nowrap">';
      $actions .= ($item->status == 'G' || $item->status == 'P' || $item->status == 'C' || $item->status == 'L') ? '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Check List" href="' . route('catalogacao_checklist.check', $item->id) . '"><i class="icon wb-pencil"></i></a>' : '';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Visualizar" href="' . route('catalogacao_checklist.show', $item->id) . '"><i class="icon wb-search"></i></a>';
      $actions .= '</div>';
      switch ($item->status) {
        case 'F':
          $status = '<span class="badge badge-info">Preparação</span>';
          break;
        case 'B':
          $status = '<span class="badge badge-outline badge-info">Banho</span>';
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

      return [
        'id' => $item->id,
        'cliente' => $item->cliente->identificacao ?? '',
        'datacad' => date('d/m/Y', strtotime($item->datacad)),
        'horacad' => date('H:i', strtotime($item->horacad)),
        'status' => $status,
        'actions' => $actions,
      ];
    })->toArray();

    return response()->json([
      'draw' => (int)$draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => array_values($data)
    ]);
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

    $itens = $catalogacao->itens->sortBy(function ($item) {
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

    $itens = $catalogacao->itens->sortBy(function ($item) {
      return $item->descricao_produto . '-' . ($item->idfornec ? $item->fornecedor->nome : '') . '-' . $item->preco_bruto . '-' . $item->id;
      //return sprintf('%-12s%s', $item->descricao_produto, $item->fornecedor->nome ?? '', $item->preco_bruto);
    });

    $produtos = $catalogacao->itens->unique('produto.descricao')->sortBy('produto.descricao')->pluck('produto.descricao', 'produto.id');
    $materiais = $catalogacao->itens->unique('material.descricao')->sortBy('material.descricao')->pluck('material.descricao', 'material.id');

    $tiposFalha = TipoFalha::select(['id', 'descricao'])->orderBy('descricao')->get();
    $fornecedores = Fornecedor::select(['id', 'nome'])->orderBy('nome')->get();

    return view('catalogacao_checklist.check')->with([
      'catalogacao' => $catalogacao,
      'itens' => $itens,
      'produtos' => $produtos,
      'materiais' => $materiais,
      'tiposFalha' => $tiposFalha,
      'fornecedores' => $fornecedores,
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
      $catalogacao_item = CatalogacaoItem::findOrFail($item->id);
      $catalogacao_item->status_check = $item->status_check ?? null;
      $catalogacao_item->obs_check = $item->obs_check;
      $catalogacao_item->tipo_falha_id = $item->tipo_falha_id ? (int) $item->tipo_falha_id : null;
      $catalogacao_item->save();
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
        $catalogacao_item->status_check = $item['status_check'] ?? null;
        $catalogacao_item->obs_check = $item['obs_check'] ?? null;
        $catalogacao_item->tipo_falha_id = $item['tipo_falha_id'] ?? null;
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
      $separacao->status_expedicao = 'A';
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

    $itens = $catalogacao->itens->sortBy(function ($item) {
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

  public function printHtml(Request $request, $id)
  {
    $catalogacao = Catalogacao::findOrFail($id);

    $itens = $catalogacao->itens->sortBy(function ($item) {
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

    return view('catalogacao_checklist.print')->with([
      'catalogacao' => $catalogacao,
      'itens' => $itens,
    ]);
  }

  public function editItem($id)
  {
    $item = CatalogacaoItem::findOrFail($id);

    $res = $item->toArray();

    if ($item->edicao) {
      $res['idfornec'] = $item->edicao->idfornec ?? $item->idfornec;
      $res['peso'] = $item->edicao->peso ?? $item->peso;
      $res['referencia'] = $item->edicao->referencia ?? $item->referencia;
      $res['quantidade'] = $item->edicao->quantidade ?? $item->quantidade;
    }

    return response()->json($res);
  }

  public function updateItem(Request $request, $id)
  {
    $item = CatalogacaoItem::findOrFail($id);
    $edicao = $item->edicao;

    if (!$edicao) {
      $edicao = CatalogacaoEdicao::firstOrCreate([
        'iditemtriagem' => $id,
      ]);
    }

    $edicao->idfornec = $request->idfornec != $item->idfornec ? $request->idfornec : null;
    $edicao->peso = $request->peso != $item->peso ? $request->peso : null;
    $edicao->referencia = $request->referencia != $item->referencia ? $request->referencia : null;
    $edicao->quantidade = $request->quantidade != $item->quantidade ? $request->quantidade : null;
    $edicao->save();

    $tiposFalha = TipoFalha::select(['id', 'descricao'])->orderBy('descricao')->get();

    return response()->json(['index' => $request->index, 'view' => view('catalogacao_checklist._info', ['item' => $item, 'index' => $request->index, 'tiposFalha' => $tiposFalha])->render()]);
  }
}

<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\CatalogacaoItem;
use Illuminate\Http\Request;

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
    return view('catalogacao_checklist.index');
  }

  public function ajax(Request $request)
  {
    $catalogacoes = Catalogacao::where('status', '<>', 'A')->get();
    $data = [];
    foreach ($catalogacoes as $catalogacao) {
      $actions = '<div class="text-nowrap">';
      $actions .= $catalogacao->status == 'F' ? '<a class="btn btn-sm btn-icon btn-flat btn-success" title="Check List" href="'.route('catalogacao_checklist.check', $catalogacao->id).'"><i class="icon wb-check"></i></a>' : '';
      $actions .= $catalogacao->status == 'C' ? '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('catalogacao_checklist.check', $catalogacao->id).'"><i class="icon wb-pencil"></i></a> <a class="btn btn-sm btn-icon btn-flat btn-primary" title="Visualizar" href="'.route('catalogacao_checklist.show', $catalogacao->id).'"><i class="icon wb-search"></i></a>' : '';
      $actions .= '</div>';
      $data[] = [
        'id' => $catalogacao->id,
        'cliente' => $catalogacao->cliente->nome ?? '',
        'datacad' => date('d/m/Y', strtotime($catalogacao->datacad)),
        'horacad' => date('H:i', strtotime($catalogacao->horacad)),
        'status' => $catalogacao->status == 'F' ? '<span class="badge badge-outline badge-warning">Aguardando</span>' : '<span class="badge badge-outline badge-success">Concluída</span>',
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

    return view('catalogacao_checklist.show')->with([
      'catalogacao' => $catalogacao,
      'itens' => $itens,
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
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    foreach ($request->itens as $item) {
      $catalogacao_item = CatalogacaoItem::findOrFail($item['id']);
      $catalogacao_item->status_check = $item['status_check'];
      $catalogacao_item->obs_check = $item['obs_check'];
      $catalogacao_item->save();
    }

    $catalogacao = Catalogacao::findOrFail($id);
    $catalogacao->status = $request->status;
    $catalogacao->save();

    return redirect()->route('catalogacao_checklist.index');
  }
}

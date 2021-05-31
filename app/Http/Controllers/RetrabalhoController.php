<?php

namespace App\Http\Controllers;

use App\Retrabalho;
use App\RetrabalhoItem;
use App\Separacao;
use Illuminate\Http\Request;

class RetrabalhoController extends Controller
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
    return view('retrabalhos.index');
  }

  /**
   * Process ajax request.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function ajax(Request $request)
  {
    $retrabalhos = Retrabalho::all();
    $data = [];
    foreach ($retrabalhos as $retrabalho) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="' . route('retrabalhos.edit', $retrabalho->id) . '"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="' . $retrabalho->id . '"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'id' => $retrabalho->id,
        'nome' => $retrabalho->cliente->identificacao,
        'datacad' => date('d/m/Y', strtotime($retrabalho->created_at)),
        'status' => '',
        'actions' => $actions,
      ];
    }
    $data = [
      'data' => $data
    ];
    return response()->json($data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('retrabalhos.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $retrabalho = new Retrabalho;
    $retrabalho->cliente_id = $request->idcliente;
    $retrabalho->observacoes = $request->observacoes;
    $retrabalho->status = 'G'; //G = Aguardando A = Andamento C = Concluído
    $retrabalho->save();

    foreach ($request->item_retrabalho as $item_retrabalho) {
      if ($item_retrabalho['idtiposervico']) {
        $item = new RetrabalhoItem;
        $item->retrabalho_id = $retrabalho->id;
        $item->tiposervico_id = $item_retrabalho['idtiposervico'];
        $item->material_id = $item_retrabalho['idmaterial'];
        $item->cor_id = $item_retrabalho['idcor'];
        $item->milesimos = $item_retrabalho['milesimos'];
        $item->peso = $item_retrabalho['peso'];
        $item->save();
      }
    }

    $separacao = Separacao::findOrFail($request->idseparacao);
    $separacao->retrabalho_id = $retrabalho->id;
    $separacao->save();

    if ($request->ajax()) {
      return response()->json($retrabalho);
    }

    return redirect()->route('guias.index');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
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
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $retrabalho = Retrabalho::findOrFail($id);
    if ($retrabalho->delete()) {
      return response(200);
    }
  }

  public function retrabalhoData($id)
  {
    $retrabalho = Retrabalho::findOrFail($id);
    $data = [];
    $data['id'] = $retrabalho->id;
    $data['cliente_id'] = $retrabalho->cliente_id;

    $item = [];
    foreach ($retrabalho->itens as $item_retrabalho) {
      $item['idtiposervico'] = $item_retrabalho->tiposervico_id;
      $item['idmaterial'] = $item_retrabalho->material_id;
      $item['idcor'] = $item_retrabalho->cor_id;
      $item['milesimos'] = $item_retrabalho->milesimos;
      $item['peso'] = $item_retrabalho->peso;
      $data['itens'][] = $item;
    }

    return $data;
  }
}

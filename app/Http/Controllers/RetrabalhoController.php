<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Material;
use App\Retrabalho;
use App\RetrabalhoItem;
use App\Separacao;
use App\TipoServico;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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

      switch ($retrabalho->status) {
        case 'G':
          $status = '<span class="badge badge-danger">Aguardando</span>';
          break;
        case 'A':
          $status = '<span class="badge badge-warning">Em Andamento</span>';
          break;
        case 'E':
          $status = '<span class="badge badge-success">Concluído</span>';
          break;
        default:
          $status = '<span class="badge badge-danger">Aguardando</span>';
          break;
      }

      $data[] = [
        'id' => $retrabalho->id,
        'nome' => $retrabalho->cliente->identificacao,
        'data_inicio' => $retrabalho->data_inicio ? date('d/m/Y', strtotime($retrabalho->data_inicio)) : '',
        'data_fim' => $retrabalho->data_fim ? date('d/m/Y', strtotime($retrabalho->data_fim)) : '',
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
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();
    $tiposServico = TipoServico::orderBy('descricao')->get();
    $materiais = Material::where('ativo', true)->orderBy('pos')->get();

    return view('retrabalhos.create')->with([
      'tiposServico' => $tiposServico,
      'materiais' => $materiais,
      'clientes' => $clientes,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data_inicio = $request->data_inicio ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('data_inicio') . ' ' . $request->get('hora_inicio')) : null;
    $data_fim = $request->data_fim ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('data_fim') . ' ' . $request->get('hora_fim')) : null;
    $retrabalho = new Retrabalho;
    $retrabalho->cliente_id = $request->idcliente;
    $retrabalho->observacoes = $request->observacoes;
    $retrabalho->status = $request->status;
    $retrabalho->data_inicio = $data_inicio;
    $retrabalho->data_fim = $data_fim;
    $retrabalho->save();

    foreach ($request->item_retrabalho as $item_retrabalho) {
      if ($item_retrabalho) {
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
    }

    return redirect()->route('retrabalhos.index');
  }

  public function ajaxstore(Request $request)
  {
    if ($request->idretrabalho) {
      $retrabalho = Retrabalho::findOrFail($request->idretrabalho);
    } else {
      $retrabalho = new Retrabalho;
    }
    $retrabalho->cliente_id = $request->idcliente;
    $retrabalho->observacoes = $request->observacoes;
    $retrabalho->status = 'G'; //G = Aguardando A = Andamento C = Concluído
    $retrabalho->save();

    //Delete
    $del = array_filter($request->item_retrabalho, function ($v) {
      return !is_null($v);
    });
    $ids = Arr::pluck($del, 'item_id');
    $to_remove = RetrabalhoItem::where('retrabalho_id', $retrabalho->id);
    if (array_filter($ids)) {
      $to_remove->whereNotIn('id', array_filter($ids));
    }
    $to_remove = $to_remove->get();
    foreach ($to_remove as $r) {
      $r->delete();
    }

    //Salva
    foreach ($request->item_retrabalho as $item_retrabalho) {
      if ($item_retrabalho) {
        if ($item_retrabalho['idtiposervico']) {
          if ($item_retrabalho['item_id']) {
            $item = RetrabalhoItem::findOrFail($item_retrabalho['item_id']);
          } else {
            $item = new RetrabalhoItem;
          }
          $item->retrabalho_id = $retrabalho->id;
          $item->tiposervico_id = $item_retrabalho['idtiposervico'];
          $item->material_id = $item_retrabalho['idmaterial'];
          $item->cor_id = $item_retrabalho['idcor'];
          $item->milesimos = $item_retrabalho['milesimos'];
          $item->peso = $item_retrabalho['peso'];
          $item->save();
        }
      }
    }

    $separacao = Separacao::findOrFail($request->idseparacao);
    $separacao->retrabalho_id = $retrabalho->id;
    $separacao->status_retrabalho = 'G';
    $separacao->data_inicio_retrabalho = null;
    $separacao->data_fim_retrabalho = null;
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
    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();
    $tiposServico = TipoServico::orderBy('descricao')->get();
    $materiais = Material::where('ativo', true)->orderBy('pos')->get();
    $retrabalho = Retrabalho::findOrFail($id);
    return view('retrabalhos.edit')->with([
      'tiposServico' => $tiposServico,
      'materiais' => $materiais,
      'clientes' => $clientes,
      'retrabalho' => $retrabalho,
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
    $data['observacoes'] = $retrabalho->observacoes;
    $data['data_inicio'] = $retrabalho->data_inicio;
    $data['data_fim'] = $retrabalho->data_fim;
    $data['status'] = $retrabalho->status;

    $item = [];
    foreach ($retrabalho->itens as $item_retrabalho) {
      $item['id'] = $item_retrabalho->id;
      $item['idtiposervico'] = $item_retrabalho->tiposervico_id;
      $item['idmaterial'] = $item_retrabalho->material_id;
      $item['idcor'] = $item_retrabalho->cor_id;
      $item['milesimos'] = $item_retrabalho->milesimos;
      $item['peso'] = number_format($item_retrabalho->peso, 0);
      $data['itens'][] = $item;
    }

    return $data;
  }
}

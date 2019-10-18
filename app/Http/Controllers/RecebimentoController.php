<?php

namespace App\Http\Controllers;

use App\Recebimento;
use Illuminate\Http\Request;

class RecebimentoController extends Controller
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
    return view('recebimentos.index');
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function ajax(Request $request)
  {
    $recebimentos = Recebimento::all();
    $data = [];
    foreach ($recebimentos as $recebimento) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('recebimentos.edit', $recebimento->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$recebimento->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'data_hora' => date("Y-m-d H:i:s", strtotime("$recebimento->data_receb $recebimento->hora_receb")),
        'cliente' => $recebimento->cliente->nome ?? $recebimento->nome_cliente,
        'fornecedor' => $recebimento->fornecedor->nome ?? $recebimento->nome_fornec,
        'pesototal' => number_format($recebimento->pesototal, 2, ',', '.'),
        'responsavel' => $recebimento->responsavel->descricao ?? '',
        'status' => $recebimento->status == 'A' ? '<span class="badge badge-outline badge-success">Em O.S.</span>' : '<span class="badge badge-outline badge-info">Recebido</span>',
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
    return view('recebimentos.create');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    //
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    //
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
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
    //
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }
}

<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Guia;
use Illuminate\Http\Request;

class ClienteController extends Controller
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
    return view('clientes.index');
  }

  public function ajax(Request $request)
  {
    $clientes = Cliente::all();
    $data = [];
    foreach ($clientes as $cliente) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('clientes.edit', $cliente->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$cliente->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'nome' => $cliente->nome,
        'cpf' => $cliente->cpf,
        'cidade' => $cliente->cidade,
        'uf' => $cliente->uf,
        'telefone' => $cliente->telefone,
        'status' => $cliente->ativo ? '<span class="badge badge-outline badge-success">Ativo</span>' : '<span class="badge badge-outline badge-default">Inativo</span>',
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
    $guias = Guia::select(['id','nome', 'ativo'])->orderBy('nome')->get();
    return view('clientes.create')->with([
      'guias' => $guias,
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
    $cliente = Cliente::findOrFail($id);
    $guias = Guia::select(['id','nome', 'ativo'])->orderBy('nome')->get();
    return view('clientes.edit')->with([
      'cliente' => $cliente,
      'guias' => $guias,
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
    return response(200);
  }
}

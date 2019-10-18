<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Guia;
use App\MeioProspeccao;
use Carbon\Carbon;
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

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
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
    $meiosProspec = MeioProspeccao::select(['id','descricao'])->orderBy('descricao')->get();

    return view('clientes.create')->with([
      'guias' => $guias,
      'meiosProspec' => $meiosProspec,
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
    $request->validate([
      'nome' => 'required|string|max:255',
    ]);

    $cliente = new Cliente;
    $cliente->nome = $request->nome;
    $cliente->tipopessoa = $request->tipopessoa;
    $cliente->cpf = $request->cpf;
    $cliente->rzsc = $request->rzsc;
    $cliente->inscest = $request->inscest;
    $cliente->idguia = $request->idguia;
    $cliente->guia_inativo = $request->guia_inativo;
    $cliente->telefone = $request->telefone;
    $cliente->celular = $request->celular;
    $cliente->email = $request->email;
    $cliente->telefone2 = $request->telefone2;
    $cliente->celular2 = $request->celular2;
    $cliente->email2 = $request->email2;
    $cliente->telefone3 = $request->telefone3;
    $cliente->cep = $request->cep;
    $cliente->endereco = $request->endereco;
    $cliente->numero = $request->numero;
    $cliente->compl = $request->compl;
    $cliente->bairro = $request->bairro;
    $cliente->cidade = $request->cidade;
    $cliente->uf = $request->uf;
    $cliente->cep_entrega = $request->cep_entrega;
    $cliente->endereco_entrega = $request->endereco_entrega;
    $cliente->numero_entrega = $request->numero_entrega;
    $cliente->compl_entrega = $request->compl_entrega;
    $cliente->bairro_entrega = $request->bairro_entrega;
    $cliente->cidade_entrega = $request->cidade_entrega;
    $cliente->uf_entrega = $request->uf_entrega;
    $cliente->obs = $request->obs;
    $cliente->prospec_id = $request->prospec_id;
    $cliente->ativo = $request->ativo;
    $cliente->atualizado = 'N';
    $cliente->data_cadastro = Carbon::now();
    $cliente->save();

    return redirect()->route('clientes.index');
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $cliente = Cliente::findOrFail($id);

    return view('clientes.show')->with([
      'cliente' => $cliente,
    ]);
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
    $meiosProspec = MeioProspeccao::select(['id','descricao'])->orderBy('descricao')->get();

    return view('clientes.edit')->with([
      'cliente' => $cliente,
      'guias' => $guias,
      'meiosProspec' => $meiosProspec,
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
    $request->validate([
      'nome' => 'required|string|max:255',
    ]);

    $cliente = Cliente::findOrFail($id);
    $cliente->nome = $request->nome;
    $cliente->tipopessoa = $request->tipopessoa;
    $cliente->cpf = $request->cpf;
    $cliente->rzsc = $request->rzsc;
    $cliente->inscest = $request->inscest;
    $cliente->idguia = $request->idguia;
    $cliente->telefone = $request->telefone;
    $cliente->celular = $request->celular;
    $cliente->email = $request->email;
    $cliente->telefone2 = $request->telefone2;
    $cliente->celular2 = $request->celular2;
    $cliente->email2 = $request->email2;
    $cliente->telefone3 = $request->telefone3;
    $cliente->cep = $request->cep;
    $cliente->endereco = $request->endereco;
    $cliente->numero = $request->numero;
    $cliente->compl = $request->compl;
    $cliente->bairro = $request->bairro;
    $cliente->cidade = $request->cidade;
    $cliente->uf = $request->uf;
    $cliente->cep_entrega = $request->cep_entrega;
    $cliente->endereco_entrega = $request->endereco_entrega;
    $cliente->numero_entrega = $request->numero_entrega;
    $cliente->compl_entrega = $request->compl_entrega;
    $cliente->bairro_entrega = $request->bairro_entrega;
    $cliente->cidade_entrega = $request->cidade_entrega;
    $cliente->uf_entrega = $request->uf_entrega;
    $cliente->obs = $request->obs;
    $cliente->prospec_id = $request->prospec_id;
    $cliente->ativo = $request->ativo;
    $cliente->atualizado = 'N';
    $cliente->data_cadastro = Carbon::now();
    //$cliente->guia_inativo = $request->guia_inativo;
    $cliente->save();

    return redirect()->route('clientes.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $cliente = Cliente::findOrFail($id);
    if ($cliente->delete()) {
      return response(200);
    }
  }
}

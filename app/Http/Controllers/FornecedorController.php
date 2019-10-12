<?php

namespace App\Http\Controllers;

use App\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
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
    return view('fornecedores.index');
  }

  public function ajax(Request $request)
  {
    $fornecedores = Fornecedor::all();
    $data = [];
    foreach ($fornecedores as $fornecedor) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('fornecedores.edit', $fornecedor->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$fornecedor->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'nome' => $fornecedor->nome,
        'cpf' => $fornecedor->cpf,
        'cidade' => $fornecedor->cidade,
        'uf' => $fornecedor->uf,
        'telefone' => $fornecedor->telefone,
        'status' => $fornecedor->ativo ? '<span class="badge badge-outline badge-success">Ativo</span>' : '<span class="badge badge-outline badge-default">Inativo</span>',
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
    return view('fornecedores.create');
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

    $fornecedor = new Fornecedor;
    $fornecedor->nome = $request->nome;
    $fornecedor->ativo = $request->ativo;
    $fornecedor->tipopessoa = $request->tipopessoa;
    $fornecedor->cpf = $request->cpf;
    $fornecedor->rzsc = $request->rzsc;
    $fornecedor->inscest = $request->inscest;
    $fornecedor->telefone = $request->telefone;
    $fornecedor->celular = $request->celular;
    $fornecedor->email = $request->email;
    $fornecedor->telefone2 = $request->telefone2;
    $fornecedor->celular2 = $request->celular2;
    $fornecedor->email2 = $request->email2;
    $fornecedor->cep = $request->cep;
    $fornecedor->endereco = $request->endereco;
    $fornecedor->numero = $request->numero;
    $fornecedor->compl = $request->compl;
    $fornecedor->bairro = $request->bairro;
    $fornecedor->cidade = $request->cidade;
    $fornecedor->uf = $request->uf;
    $fornecedor->obs = $request->obs;

    $fornecedor->save();

    return redirect()->route('fornecedores.index');
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $fornecedor = Fornecedor::findOrFail($id);

    return view('fornecedores.show')->with([
      'fornecedor' => $fornecedor,
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
    $fornecedor = Fornecedor::findOrFail($id);

    return view('fornecedores.edit')->with([
      'fornecedor' => $fornecedor,
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

    $fornecedor = Fornecedor::findOrFail($id);
    $fornecedor->nome = $request->nome;
    $fornecedor->ativo = $request->ativo;
    $fornecedor->tipopessoa = $request->tipopessoa;
    $fornecedor->cpf = $request->cpf;
    $fornecedor->rzsc = $request->rzsc;
    $fornecedor->inscest = $request->inscest;
    $fornecedor->telefone = $request->telefone;
    $fornecedor->celular = $request->celular;
    $fornecedor->email = $request->email;
    $fornecedor->telefone2 = $request->telefone2;
    $fornecedor->celular2 = $request->celular2;
    $fornecedor->email2 = $request->email2;
    $fornecedor->cep = $request->cep;
    $fornecedor->endereco = $request->endereco;
    $fornecedor->numero = $request->numero;
    $fornecedor->compl = $request->compl;
    $fornecedor->bairro = $request->bairro;
    $fornecedor->cidade = $request->cidade;
    $fornecedor->uf = $request->uf;
    $fornecedor->obs = $request->obs;
    $fornecedor->save();

    return redirect()->route('fornecedores.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $fornecedor = Fornecedor::findOrFail($id);
    if ($fornecedor->delete()) {
      return response(200);
    }
  }
}

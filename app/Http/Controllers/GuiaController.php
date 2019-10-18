<?php

namespace App\Http\Controllers;

use App\Guia;
use Illuminate\Http\Request;

class GuiaController extends Controller
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
    return view('guias.index');
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function ajax(Request $request)
  {
    $guias = Guia::all();
    $data = [];
    foreach ($guias as $guia) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('guias.edit', $guia->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$guia->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'nome' => $guia->nome,
        'cpf' => $guia->cpf,
        'cidade' => $guia->cidade,
        'uf' => $guia->uf,
        'telefone' => $guia->telefone,
        'status' => $guia->ativo ? '<span class="badge badge-outline badge-success">Ativo</span>' : '<span class="badge badge-outline badge-default">Inativo</span>',
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
    return view('guias.create');
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

    $guia = new Guia;
    $guia->nome = $request->nome;
    $guia->ativo = $request->ativo;
    $guia->tipopessoa = $request->tipopessoa;
    $guia->cpf = $request->cpf;
    $guia->rzsc = $request->rzsc;
    $guia->telefone = $request->telefone;
    $guia->celular = $request->celular;
    $guia->email = $request->email;
    $guia->telefone2 = $request->telefone2;
    $guia->celular2 = $request->celular2;
    $guia->telefone3 = $request->telefone3;
    $guia->celular3 = $request->celular3;
    $guia->cep = $request->cep;
	  $guia->endereco = $request->endereco;
    $guia->numero = $request->numero;
    $guia->compl = $request->compl;
	  $guia->bairro = $request->bairro;
	  $guia->cidade = $request->cidade;
    $guia->uf = $request->uf;
	  $guia->obs = $request->obs;
	  $guia->tipocomissao = $request->tipocomissao;
	  $guia->atualizado = 'N';
    $guia->save();

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
    $guia = Guia::findOrFail($id);

    return view('guias.show')->with([
      'guia' => $guia,
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
    $guia = Guia::findOrFail($id);

    return view('guias.edit')->with([
      'guia' => $guia,
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

    $guia = Guia::findOrFail($id);
    $guia->nome = $request->nome;
    $guia->ativo = $request->ativo;
    $guia->tipopessoa = $request->tipopessoa;
    $guia->cpf = $request->cpf;
    $guia->rzsc = $request->rzsc;
    $guia->telefone = $request->telefone;
    $guia->celular = $request->celular;
    $guia->email = $request->email;
    $guia->telefone2 = $request->telefone2;
    $guia->celular2 = $request->celular2;
    $guia->telefone3 = $request->telefone3;
    $guia->celular3 = $request->celular3;
    $guia->cep = $request->cep;
	  $guia->endereco = $request->endereco;
    $guia->numero = $request->numero;
    $guia->compl = $request->compl;
	  $guia->bairro = $request->bairro;
	  $guia->cidade = $request->cidade;
    $guia->uf = $request->uf;
	  $guia->obs = $request->obs;
	  $guia->tipocomissao = $request->tipocomissao;
	  $guia->atualizado = 'N';
    $guia->save();

    return redirect()->route('guias.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $guia = Guia::findOrFail($id);
    if ($guia->delete()) {
      return response(200);
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\TipoServico;
use Illuminate\Http\Request;

class TipoServicoController extends Controller
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
    $tiposServico = TipoServico::orderBy('descricao')->get();
    return view('tipos_servico.index')->with([
      'tiposServico' => $tiposServico
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
      'descricao' => 'required|max:255'
    ]);

    $tipoServico = new TipoServico;
    $tipoServico->descricao = $request->descricao;
    $tipoServico->parametro = $request->parametro;

    if ($tipoServico->save()) {
      $tiposServico = TipoServico::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_servico.data', compact('tiposServico'))->render()]);
    }
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
      'descricao' => 'required|max:255'
    ]);

    $tipoServico = TipoServico::findOrFail($id);
    $tipoServico->descricao = $request->descricao;
    $tipoServico->parametro = $request->parametro;

    if ($tipoServico->save()) {
      $tiposServico = TipoServico::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_servico.data', compact('tiposServico'))->render()]);
    }
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $tipoServico = TipoServico::findOrFail($id);
    if ($tipoServico->delete()) {
      $tiposServico = TipoServico::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_servico.data', compact('tiposServico'))->render()]);
    }
  }
}

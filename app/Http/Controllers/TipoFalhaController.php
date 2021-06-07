<?php

namespace App\Http\Controllers;

use App\TipoFalha;
use Illuminate\Http\Request;

class TipoFalhaController extends Controller
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
    $tiposFalha = TipoFalha::orderBy('descricao')->get();
    return view('tipos_falha.index')->with([
      'tiposFalha' => $tiposFalha
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

    $tipoFalha = new TipoFalha;
    $tipoFalha->descricao = $request->descricao;

    if ($tipoFalha->save()) {
      $tiposFalha = TipoFalha::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_falha.data', compact('tiposFalha'))->render()]);
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

    $tipoFalha = TipoFalha::findOrFail($id);
    $tipoFalha->descricao = $request->descricao;

    if ($tipoFalha->save()) {
      $tiposFalha = TipoFalha::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_falha.data', compact('tiposFalha'))->render()]);
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
    $tipoFalha = TipoFalha::findOrFail($id);
    if ($tipoFalha->delete()) {
      $tiposFalha = TipoFalha::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_falha.data', compact('tiposFalha'))->render()]);
    }
  }
}

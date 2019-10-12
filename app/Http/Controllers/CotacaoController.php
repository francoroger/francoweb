<?php

namespace App\Http\Controllers;

use App\Cotacao;
use App\Material;
use DB;
use Illuminate\Http\Request;

class CotacaoController extends Controller
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
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request, $id)
  {
    $material = Material::findOrFail($id);
    $cotacoes = DB::table('cotacoes')
                  ->where('idmaterial', $id)
                  ->orderBy('data', 'desc')
                  ->simplePaginate(10);

    if ($request->ajax()) {
      return response()->json(['view' => view('cotacoes.data', compact('cotacoes'))->render()]);
    }

    return view('cotacoes.index')->with([
      'material' => $material,
      'cotacoes' => $cotacoes,
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
      'idmaterial' => 'required',
      'data' => 'required',
      'valorg' => 'required',
    ]);

    $cotacao = new Cotacao;
    $cotacao->idmaterial = $request->idmaterial;
    $cotacao->data = $request->data;
    $cotacao->valorg = $request->valorg;

    if ($cotacao->save()) {
      $cotacoes = DB::table('cotacoes')
                    ->where('idmaterial', $request->idmaterial)
                    ->orderBy('data', 'desc')
                    ->simplePaginate(10);

      return response()->json(['view' => view('cotacoes.data', compact('cotacoes'))->render()]);
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
    $cotacao = Cotacao::findOrFail($id);
    if ($cotacao->delete()) {
      return response(200);
    }
  }
}

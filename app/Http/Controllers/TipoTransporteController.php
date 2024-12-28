<?php

namespace App\Http\Controllers;

use App\TipoTransporte;
use Illuminate\Http\Request;

class TipoTransporteController extends Controller
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
    $tiposTransporte = TipoTransporte::orderBy('descricao')->get();
    return view('tipos_transporte.index')->with([
      'tiposTransporte' => $tiposTransporte
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

    $tipoTransporte = new TipoTransporte;
    $tipoTransporte->descricao = $request->descricao;

    if ($tipoTransporte->save()) {
      $tiposTransporte = TipoTransporte::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_transporte.data', compact('tiposTransporte'))->render()]);
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

    $tipoTransporte = TipoTransporte::findOrFail($id);
    $tipoTransporte->descricao = $request->descricao;

    if ($tipoTransporte->save()) {
      $tiposTransporte = TipoTransporte::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_transporte.data', compact('tiposTransporte'))->render()]);
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
    $tipoTransporte = TipoTransporte::findOrFail($id);
    if ($tipoTransporte->delete()) {
      $tiposTransporte = TipoTransporte::orderBy('descricao')->get();
      return response()->json(['view' => view('tipos_transporte.data', compact('tiposTransporte'))->render()]);
    }
  }
}

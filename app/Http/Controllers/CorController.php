<?php

namespace App\Http\Controllers;

use App\Cor;
use Illuminate\Http\Request;

class CorController extends Controller
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
    $cores = Cor::orderBy('descricao')->get();
    return view('cores.index')->with([
      'cores' => $cores
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

    $cor = new Cor;
    $cor->descricao = $request->descricao;

    if ($cor->save()) {
      $cores = Cor::orderBy('descricao')->get();
      return response()->json(['view' => view('cores.data', compact('cores'))->render()]);
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

    $cor = Cor::findOrFail($id);
    $cor->descricao = $request->descricao;

    if ($cor->save()) {
      $cores = Cor::orderBy('descricao')->get();
      return response()->json(['view' => view('cores.data', compact('cores'))->render()]);
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
    $cor = Cor::findOrFail($id);
    if ($cor->delete()) {
      $cores = Cor::orderBy('descricao')->get();
      return response()->json(['view' => view('cores.data', compact('cores'))->render()]);
    }
  }
}

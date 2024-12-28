<?php

namespace App\Http\Controllers;

use App\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
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
    $produtos = Produto::orderBy('descricao')->get();
    return view('produtos.index')->with([
      'produtos' => $produtos
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

    $produto = new Produto;
    $produto->descricao = $request->descricao;

    if ($produto->save()) {
      $produtos = Produto::orderBy('descricao')->get();
      return response()->json(['view' => view('produtos.data', compact('produtos'))->render()]);
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

    $produto = Produto::findOrFail($id);
    $produto->descricao = $request->descricao;

    if ($produto->save()) {
      $produtos = Produto::orderBy('descricao')->get();
      return response()->json(['view' => view('produtos.data', compact('produtos'))->render()]);
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
    $produto = Produto::findOrFail($id);
    if ($produto->delete()) {
      $produtos = Produto::orderBy('descricao')->get();
      return response()->json(['view' => view('produtos.data', compact('produtos'))->render()]);
    }
  }
}

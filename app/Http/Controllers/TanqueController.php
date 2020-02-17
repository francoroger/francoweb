<?php

namespace App\Http\Controllers;

use App\Tanque;
use Illuminate\Http\Request;

class TanqueController extends Controller
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
    return view('tanques.index');
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function ajax(Request $request)
  {
    $tanques = Tanque::orderBy('pos')->get();
    $data = [];
    foreach ($tanques as $tanque) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('tanques.edit', $tanque->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$tanque->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'descricao' => $tanque->descricao,
        'ciclo_reforco' => $tanque->ciclo_reforco,
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
    //
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
    $tanque = Tanque::findOrFail($id);
    return view('tanques.show')->with([
      'tanque' => $tanque,
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
    $tanque = Tanque::findOrFail($id);
    return view('tanques.edit')->with([
      'tanque' => $tanque,
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
      'descricao' => 'required|string|max:255',
    ]);

    $tanque = Tanque::findOrFail($id);
    $tanque->descricao = $request->descricao;
    $tanque->ciclo_reforco = $request->ciclo_reforco;
    $tanque->save();

    return redirect()->route('tanques.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $tanque = Tanque::findOrFail($id);
    if ($tanque->delete()) {
      return response(200);
    }
  }
}

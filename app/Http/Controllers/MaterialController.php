<?php

namespace App\Http\Controllers;

use App\Cor;
use App\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
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
    return view('materiais.index');
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function ajax(Request $request)
  {
    $materiais = Material::all();
    $data = [];
    foreach ($materiais as $material) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('materiais.edit', $material->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-success" title="Cotações" href="'.route('materiais.cotacoes', $material->id).'"><i class="fa fa-usd"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$material->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'descricao' => $material->descricao,
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
    $cores = Cor::select(['id', 'descricao'])->orderBy('descricao')->get();
    return view('materiais.create')->with([
      'cores' => $cores,
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
      'descricao' => 'required|string|max:255',
    ]);

    $material = new Material;
    $material->descricao = $request->descricao;
    $material->save();
    $material->cores()->sync($request->cores);

    return redirect()->route('materiais.index');
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $material = Material::findOrFail($id);
    return view('materiais.show')->with([
      'material' => $material,
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
    $material = Material::findOrFail($id);
    $cores = Cor::select(['id', 'descricao'])->orderBy('descricao')->get();
    return view('materiais.edit')->with([
      'material' => $material,
      'cores' => $cores,
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

    $material = Material::findOrFail($id);
    $material->descricao = $request->descricao;
    $material->save();
    $material->cores()->sync($request->cores);

    return redirect()->route('materiais.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $material = Material::findOrFail($id);
    if ($material->delete()) {
      return response(200);
    }
  }

  /**
  * Obtem a cotação atual do material
  *
  * @param  int  $id
  * @return \Illuminate\Http\JsonResponse
  */
  public function cotacao($id)
  {
    $material = Material::findOrFail($id);
    return response()->json([
      'valorg' => $material->cotacoes->count() > 0 ? $material->cotacoes->sortByDesc('data')->first()->valorg : '0,00'
    ]);
  }

  /**
  * Obtem as cores disponíveis do material
  *
  * @param  int  $id
  * @return \Illuminate\Http\JsonResponse
  */
  public function cores_disponiveis($id)
  {
    $material = Material::findOrFail($id);
    $cores = [];
    foreach ($material->cores->sortBy('descricao')->pluck('descricao', 'id') as $k => $cor) {
      $cores[] = [
        'id' => $k,
        'descricao' => $cor
      ];
    }
    return response()->json($cores);
  }
}

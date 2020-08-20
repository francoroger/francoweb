<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Fornecedor;
use App\Recebimento;
use App\RecebimentoFoto;
use App\ResponsavelEntrega;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecebimentoController extends Controller
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
    return view('recebimentos.index');
  }

  /**
  * Process ajax request.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function ajax(Request $request)
  {
    $recebimentos = Recebimento::all();
    $data = [];
    foreach ($recebimentos as $recebimento) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('recebimentos.edit', $recebimento->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$recebimento->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'data_hora' => date("Y-m-d H:i:s", strtotime("$recebimento->data_receb $recebimento->hora_receb")),
        'cliente' => $recebimento->cliente->identificacao ?? $recebimento->nome_cliente,
        'fornecedor' => $recebimento->fornecedor->nome ?? $recebimento->nome_fornec,
        'pesototal' => $recebimento->pesototal,
        'responsavel' => $recebimento->responsavel->descricao ?? '',
        'status' => $recebimento->status == 'A' ? '<span class="badge badge-outline badge-success">Em O.S.</span>' : '<span class="badge badge-outline badge-info">Recebido</span>',
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
    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();
    $fornecedores = Fornecedor::select(['id', 'nome', 'ativo'])->orderBy('nome')->get();
    $responsaveis = ResponsavelEntrega::select()->orderBy('descricao')->get();
    return view('recebimentos.create')->with([
      'clientes' => $clientes,
      'fornecedores' => $fornecedores,
      'responsaveis' => $responsaveis,
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
      'data_receb' => 'required',
      'hora_receb' => 'required',
      'idcliente' => 'required',
    ]);

    $recebimento = new Recebimento;
    $recebimento->data_receb = Carbon::createFromFormat('d/m/Y', $request->data_receb);
    $recebimento->hora_receb = $request->hora_receb;
    $recebimento->idcliente = $request->idcliente;
    $recebimento->idfornec = $request->idfornec;
    $recebimento->pesototal = $request->pesototal;
    $recebimento->obs = $request->obs;
    $recebimento->idresp = $request->idresp;
    $recebimento->save();

    foreach ($request->fotos as $foto) {
      $recebimento_foto = new RecebimentoFoto;
      $recebimento_foto->receb_id = $recebimento->id;
      $recebimento_foto->foto = 'T:\\' . str_replace('/', '\\', $foto);
      $recebimento_foto->save();
    }

    return redirect()->route('recebimentos.index');

  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $recebimento = Recebimento::findOrFail($id);

    return view('recebimentos.show')->with([
      'recebimento' => $recebimento,
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
    $recebimento = Recebimento::findOrFail($id);
    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();
    $fornecedores = Fornecedor::select(['id', 'nome', 'ativo'])->orderBy('nome')->get();
    $responsaveis = ResponsavelEntrega::select()->orderBy('descricao')->get();
    return view('recebimentos.edit')->with([
      'recebimento' => $recebimento,
      'clientes' => $clientes,
      'fornecedores' => $fornecedores,
      'responsaveis' => $responsaveis,
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
      'data_receb' => 'required',
      'hora_receb' => 'required',
      'idcliente' => 'required',
    ]);

    $recebimento = Recebimento::findOrFail($id);
    $recebimento->data_receb = Carbon::createFromFormat('d/m/Y', $request->data_receb);
    $recebimento->hora_receb = $request->hora_receb;
    $recebimento->idcliente = $request->idcliente;
    $recebimento->idfornec = $request->idfornec;
    $recebimento->pesototal = $request->pesototal;
    $recebimento->obs = $request->obs;
    $recebimento->idresp = $request->idresp;
    $recebimento->save();

    foreach ($request->fotos as $foto) {
      $exists = RecebimentoFoto::where('foto', '=', 'T:\\' . str_replace('/', '\\', $foto))->get()->first();
      if (!$exists) {
        $recebimento_foto = new RecebimentoFoto;
        $recebimento_foto->receb_id = $recebimento->id;
        $recebimento_foto->foto = 'T:\\' . str_replace('/', '\\', $foto);
        $recebimento_foto->save();
      }
    }

    return redirect()->route('recebimentos.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $recebimento = Recebimento::findOrFail($id);
    foreach ($recebimento->fotos as $foto) {
      $foto->delete();
    }

    if ($recebimento->delete()) {
      return response(200);
    }
  }

  public function destroyFoto($id)
  {
    $foto = RecebimentoFoto::findOrFail($id);
    if ($foto->delete()) {
      return response(200);
    }
  }

  public function upload(Request $request)
  {
    $filename = Storage::disk('fotos')->put('Recebimentos', $request->file('snapshot'));
    $path = Storage::disk('fotos')->url($filename);
    return response()->json([
      'path' => $path,
      'filename' => $filename
    ]);
  }
}

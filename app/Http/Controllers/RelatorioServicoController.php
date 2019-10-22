<?php

namespace App\Http\Controllers;

use App\Cor;
use App\Cliente;
use App\Guia;
use App\Material;
use App\TipoServico;
use App\Servico;
use App\ServicoItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatorioServicoController extends Controller
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
    $clientes = Cliente::select(['id','nome'])->orderBy('nome')->get();
    $guias = Guia::select(['id','nome'])->orderBy('nome')->get();
    $tipos = TipoServico::select(['id','descricao'])->orderBy('descricao')->get();
    $cores = Cor::select(['id','descricao'])->orderBy('descricao')->get();
    $materiais = Material::select(['id','descricao'])->orderBy('descricao')->get();
    $camadas = ServicoItem::select(['milesimos'])->distinct()->get();
    $milesimos = collect([
      $camadas->min('milesimos'),
      $camadas->max('milesimos'),
    ]);

    return view('relatorios.servicos.index')->with([
      'clientes' => $clientes,
      'guias' => $guias,
      'tipos' => $tipos,
      'cores' => $cores,
      'materiais' => $materiais,
      'milesimos' => $milesimos,
    ]);
  }

  /**
  * Post search criteria.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function search(Request $request)
  {
    $itens = ServicoItem::select()
                        ->leftJoin('servico', 'idservico', '=', 'servico.id')
                        ->leftJoin('cliente', 'servico.idcliente', '=', 'cliente.id')
                        ->leftJoin('guia', 'servico.idguia', '=', 'guia.id')
                        ->leftJoin('tiposervico', 'idtiposervico', '=', 'tiposervico.id')
                        ->leftJoin('material', 'idmaterial', '=', 'material.id')
                        ->leftJoin('cores', 'idcor', '=', 'cores.id');

    $itens->whereHas('servico', function($query) use ($request) {
      if ($request->dataini && $request->datafim) {
        $dtini = Carbon::createFromFormat('d/m/Y', $request->dataini);
        $dtfim = Carbon::createFromFormat('d/m/Y', $request->datafim);
        $query->whereBetween('datavenda', [$dtini, $dtfim]);
      }
      if ($request->idcliente) {
        $query->whereIn('idcliente', explode(',', $request->idcliente));
      }
      if ($request->idguia) {
        $query->whereIn('idguia', explode(',', $request->idguia));
      }
    });

    if ($request->idtiposervico) {
      $itens->whereIn('idtiposervico', explode(',', $request->idtiposervico));
    }

    if ($request->idmaterial) {
      $itens->whereIn('idmaterial', explode(',', $request->idmaterial));
    }

    if ($request->idcor) {
      $itens->whereIn('idcor', explode(',', $request->idcor));
    }

    if ($request->milesimos) {
      $camada = explode(';', $request->milesimos);
      $itens->whereBetween('milesimos', $camada);
    }

    $itens->orderBy($request->sort);

    $total['valor'] = $itens->sum('valor');
    $total['peso'] = $itens->sum('peso') / 100;

    $itens = $itens->paginate(10);

    return response()->json(['view' => view('relatorios.servicos.result', ['itens' => $itens, 'total' => $total])->render()]);
  }
}

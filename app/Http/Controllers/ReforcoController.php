<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Material;
use App\PassagemPeca;
use App\ProcessoTanque;
use App\Reforco;
use App\Tanque;
use App\TanqueCiclo;
use App\TipoServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReforcoController extends Controller
{
  public function index()
  {
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();

    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();

    $tiposServico = TipoServico::whereHas('processos_tanque')->orderBy('descricao')->get();
    $materiais = Material::whereHas('processos_tanque')->orderBy('pos')->get();
    return view('controle_reforco.index')->with([
      'tanques' => $tanques,
      'tiposServico' => $tiposServico,
      'materiais' => $materiais,
      'clientes' => $clientes,
    ]);
  }

  public function tanques(Request $request)
  {
    $processos = ProcessoTanque::orderBy('id');

    if ($request->get('idtiposervico')) {
      $processos->where('tiposervico_id', $request->get('idtiposervico'));
    }

    if ($request->get('idmaterial')) {
      $processos->where('material_id', $request->get('idmaterial'));
    }

    if ($request->get('idcor')) {
      $processos->where('cor_id', $request->get('idcor'));
    }

    if ($request->get('milesimos')) {
      $processos->where('mil_ini', '<=', $request->get('milesimos'))
                ->where('mil_fim', '>=', $request->get('milesimos'));
    }

    $processos = $processos->get();

    $data = [];

    foreach ($processos as $proc) {
      $data[] = [
        'id' => $proc->tanque_id,
        'descricao' => $proc->tanque->descricao,
      ];
    }

    return response()->json($data);
  }

  public function registra_ciclo(Request $request)
  {
    $passagem = new PassagemPeca;
    $passagem->data_servico = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('data_servico') . ' ' . $request->get('hora_servico'));
    $passagem->cliente_id = $request->get('idcliente');
    $passagem->tiposervico_id = $request->get('idtiposervico');
    $passagem->material_id = $request->get('idmaterial');
    $passagem->cor_id = $request->get('idcor');
    $passagem->milesimos = $request->get('milesimos');
    $passagem->peso = str_replace(',', '.', $request->get('peso'));
    $passagem->save();

    $processos = ProcessoTanque::orderBy('id');

    if ($request->get('idtiposervico')) {
      $processos->where('tiposervico_id', $request->get('idtiposervico'));
    }

    if ($request->get('idmaterial')) {
      $processos->where('material_id', $request->get('idmaterial'));
    }

    if ($request->get('idcor')) {
      $processos->where('cor_id', $request->get('idcor'));
    }

    if ($request->get('milesimos')) {
      $processos->where('mil_ini', '<=', $request->get('milesimos'))
                ->where('mil_fim', '>=', $request->get('milesimos'));
    }

    $processos = $processos->get();

    foreach ($processos as $proc) {
      $ciclo = new TanqueCiclo;

      $NDesconto = (double)$proc->tanque->desconto_milesimo ?? 0;
      
      if ($proc->tanque->tipo_consumo == 'M') {
        $NPeso = str_replace(',', '.', $request->get('peso'));
        $NMilesimos = $request->get('milesimos');
        $peso_consumido = (($NPeso * $NMilesimos) / 1000) - (($NPeso * $NDesconto) / 1000);
        $ciclo->peso = $peso_consumido;
      } else {
        $fat = $proc->fator ?? 1;
        $ciclo->peso = str_replace(',', '.', $request->get('peso')) * $fat;
      }

      $ciclo->tanque_id = $proc->tanque_id;
      $ciclo->cliente_id = $request->get('idcliente');
      $ciclo->tiposervico_id = $request->get('idtiposervico');
      $ciclo->material_id = $request->get('idmaterial');
      $ciclo->cor_id = $request->get('idcor');
      $ciclo->milesimos = $request->get('milesimos');
      $ciclo->data_servico = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('data_servico') . ' ' . $request->get('hora_servico'));
      $ciclo->status = 'P';
      $ciclo->save();
    }

    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    $data = [];

    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);

  }

  public function reset_ciclo(Request $request)
  {
    //Pega o excedente para criar uma nova transação após o reforço
    $tanque = Tanque::findOrFail($request->get('id'));
    $exd = $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? $tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco : 0;

    //Obter a quantidade mínima necessária pra zerar, o que sobra é o negativo
    $neg = $tanque->ciclos->where('status', 'P')->sum('peso') < $tanque->ciclo_reforco ? $tanque->ciclo_reforco - $tanque->ciclos->where('status', 'P')->sum('peso') : 0;

    //Gera um registro para o reforço que será associado com o item
    $reforco = new Reforco;
    $reforco->tanque_id = $request->get('id');
    $reforco->save();

    //Atualiza o item com o status de R (realizado) e a id do reforço
    $affected = DB::table('tanque_ciclos')
                  ->where('tanque_id', $request->get('id'))
                  ->where('status', 'P')
                  ->update([
                    'status' => 'R',
                    'reforco_id' => $reforco->id,
                  ]);

    //Cria a transação do excedente
    if ($exd > 0) {
      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $request->get('id');
      $ciclo->data_servico = \Carbon\Carbon::now();
      $ciclo->peso = $exd;
      $ciclo->status = 'P';
      $ciclo->excedente = true;
      $ciclo->reforco_id = $reforco->id;
      $ciclo->save();
    }

    //Cria a transação do negativo
    if ($neg > 0) {
      $ciclo = new TanqueCiclo;
      $ciclo->tanque_id = $request->get('id');
      $ciclo->data_servico = \Carbon\Carbon::now();
      $ciclo->peso = - $neg;
      $ciclo->status = 'P';
      $ciclo->excedente = true;
      $ciclo->reforco_id = $reforco->id;
      $ciclo->save();
    }

    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function reset_tanque(Request $request)
  {
    //Pega o excedente para criar uma nova transação após o reforço
    $tanque = Tanque::findOrFail($request->get('id'));
    
    DB::table('tanque_ciclos')->where('tanque_id', $request->get('id'))->delete();

    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function reforco_analise(Request $request)
  {
    $tanque = Tanque::findOrFail($request->get('id'));

    //Gera um registro para o reforço que será associado com o item
    $reforco = new Reforco;
    $reforco->tanque_id = $request->get('id');
    $reforco->tipo = 'A';
    $reforco->save();

    $current_val = $tanque->ciclos->where('status', 'P')->sum('peso');
    $reforco_val = $request->get('reforco_analise_valor');

    $diff = 0;
    if ($reforco_val > $current_val) {
      $diff = $reforco_val - $current_val;
    } else {
      $diff = ($current_val - $reforco_val) * (-1);
    }

    $ciclo = new TanqueCiclo;
    $ciclo->tanque_id = $request->get('id');
    $ciclo->data_servico = \Carbon\Carbon::now();
    $ciclo->peso = $diff;
    $ciclo->status = 'P';
    $ciclo->excedente = true;
    $ciclo->reforco_id = $reforco->id;
    $ciclo->save();
    
    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function undo_reforco(Request $request)
  {
    $tanque = Tanque::findOrFail($request->get('id'));
    $reforco = $tanque->reforcos->sortByDesc('created_at')->first();
    $reforco_id = $reforco->id;

    //Exclui os excedentes
    $exced = DB::table('tanque_ciclos')
               ->where('tanque_id', $request->get('id'))
               ->where('reforco_id', $reforco_id)
               ->where('status', 'P')
               ->where('excedente', true)
               ->delete();

    //Atualiza o item com o status de R (realizado) e a id do reforço
    $updt = DB::table('tanque_ciclos')
              ->where('tanque_id', $request->get('id'))
              ->where('reforco_id', $reforco_id)
              ->update([
                'status' => 'P',
                'reforco_id' => null,
              ]);

    //Exclui o reforço
    $reforco->delete();

    $data = [];
    $tanques = Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();
    foreach ($tanques as $tanque) {
      $data[] = [
        'id' => $tanque->id,
        'val' => $tanque->ciclos->where('status', 'P')->sum('peso'),
        'exd' => $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : ""
      ];
    }

    return response()->json($data);
  }

  public function consulta(Request $request)
  {
    $passagens = PassagemPeca::latest('created_at');
    $passagens = $passagens->paginate(10);

    if ($request->ajax()) {
      return response()->json([
        'view' => view('consulta_reforco.data', compact('passagens'))->render()
      ]);
    } else {
      return view('consulta_reforco.index')->with([
        'passagens' => $passagens
      ]);
    }
  }

  public function destroy($id)
  {
    $passagem = PassagemPeca::findOrFail($id);

    $processos = ProcessoTanque::orderBy('id');

    if ($passagem->tiposervico_id) {
      $processos->where('tiposervico_id', $passagem->tiposervico_id);
    }

    if ($passagem->material_id) {
      $processos->where('material_id', $passagem->material_id);
    }

    if ($passagem->cor_id) {
      $processos->where('cor_id', $passagem->cor_id);
    }

    if ($passagem->milesimos) {
      $processos->where('mil_ini', '<=', $passagem->milesimos)
                ->where('mil_fim', '>=', $passagem->milesimos);
    }

    $processos = $processos->get();

    foreach ($processos as $proc) {
      $fat = $proc->fator ?? 1;
      $peso = $passagem->peso * $fat;

      $ciclo = TanqueCiclo::where('tanque_id', $proc->tanque_id)
                          ->where('cliente_id', $passagem->cliente_id)
                          ->where('tiposervico_id', $passagem->tiposervico_id)
                          ->where('material_id', $passagem->material_id)
                          ->where('peso', $peso)
                          ->get();

      if ($ciclo->count() > 0) {
        $toDel = TanqueCiclo::findOrFail($ciclo->first()->id);
        $toDel->delete();
      }

    }

    if ($passagem->delete()) {
      return response(200);
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\OrdemServico;
use App\Recebimento;
use App\Separacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class PainelAcompanhamentoController extends Controller
{
  //Coluna Recebimento
  private function recebimentos()
  {
    $recebimentos = Recebimento::whereNull('status')->where('data_receb', '>=', date('Y-m-d', strtotime('2020-01-01')) )->orderBy('data_receb', 'desc')->get();

    $colRecebimento = [];
    foreach ($recebimentos as $recebimento) {
      $obj = new stdClass();
      $obj->id = $recebimento->id;
      $obj->cliente = $recebimento->cliente->identificacao ?? '';
      $obj->peso = $recebimento->pesototal;
      $obj->qtde_itens = 1;
      $obj->qtde_check = 0;
      $obj->data_situacao = $recebimento->data_receb;
      $obj->obs = $recebimento->obs;
      $colRecebimento[] = $obj;
    }

    return collect((object) $colRecebimento);
  }

  //Coluna Separação
  private function separacoes()
  {
    $separacoes = Separacao::where('status', 'S')->orderBy('created_at', 'desc')->get();

    $colSeparacao = [];
    foreach ($separacoes as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->id;
      $obj->cliente = $separacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->qtde_itens = $separacao->recebimentos->count();
      $obj->qtde_check = 0;
      $obj->data_situacao = $separacao->created_at;
      $obj->obs = null;
      $colSeparacao[] = $obj;
    }

    return collect((object) $colSeparacao);
  }

  //Coluna Catalogação
  private function catalogacoes()
  {
    $catalogacoes = Catalogacao::where('status', 'A')->whereNotNull('idcliente')->orderBy('datacad', 'desc')->get();

    $colCatalogacao = [];
    foreach ($catalogacoes as $catalogacao) {
      $obj = new stdClass();
      $obj->id = $catalogacao->id;
      $obj->cliente = $catalogacao->cliente->identificacao ?? '';
      $obj->peso = $catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $catalogacao->itens->count();
      $obj->qtde_check = $catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $catalogacao->datacad;
      $obj->obs = $catalogacao->observacoes;
      $colCatalogacao[] = $obj;
    }

    return collect((object) $colCatalogacao);
  }

  //Coluna Ordens
  private function ordens()
  {
    $ordens = Catalogacao::where('status', 'F')->whereNotNull('idcliente')->orderBy('datacad', 'desc')->get();

    $colOrdens = [];
    foreach ($ordens as $catalogacao) {
      $obj = new stdClass();
      $obj->id = $catalogacao->id;
      $obj->cliente = $catalogacao->cliente->identificacao ?? '';
      $obj->peso = $catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $catalogacao->itens->count();
      $obj->qtde_check = $catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $catalogacao->datacad;
      $obj->obs = $catalogacao->observacoes;
      $colOrdens[] = $obj;
    }

    return collect((object) $colOrdens);
  }

  //Coluna Revisões
  private function revisoes()
  {
    $revisoes = Catalogacao::whereIn('status', ['P', 'G'])->whereNotNull('idcliente')->orderBy('datacad', 'desc')->get();

    $colRevisoes = [];
    foreach ($revisoes as $catalogacao) {
      $obj = new stdClass();
      $obj->id = $catalogacao->id;
      $obj->cliente = $catalogacao->cliente->identificacao ?? '';
      $obj->peso = $catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $catalogacao->itens->count();
      $obj->qtde_check = $catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $catalogacao->datacad;
      $obj->obs = $catalogacao->observacoes;
      $colRevisoes[] = $obj;
    }

    return collect((object) $colRevisoes);
  }

  //Coluna Expedições
  private function expedicoes()
  {
    $expedicoes = Catalogacao::where('status', 'C')->whereNotNull('idcliente')->orderBy('datacad', 'desc')->take(30)->get();

    $colExpedicoes = [];
    foreach ($expedicoes as $catalogacao) {
      $obj = new stdClass();
      $obj->id = $catalogacao->id;
      $obj->cliente = $catalogacao->cliente->identificacao ?? '';
      $obj->peso = $catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $catalogacao->itens->count();
      $obj->qtde_check = $catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $catalogacao->datacad;
      $obj->obs = $catalogacao->observacoes;
      $colExpedicoes[] = $obj;
    }

    return collect((object) $colExpedicoes);
  }

  //Coluna Concluídos
  private function concluidos()
  {
    $concluidos = Catalogacao::where('status', 'L')->whereNotNull('idcliente')->orderBy('datacad', 'desc')->take(30)->get();

    $colConcluidos = [];
    foreach ($concluidos as $catalogacao) {
      $obj = new stdClass();
      $obj->id = $catalogacao->id;
      $obj->cliente = $catalogacao->cliente->identificacao ?? '';
      $obj->peso = $catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $catalogacao->itens->count();
      $obj->qtde_check = $catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $catalogacao->datacad;
      $obj->obs = $catalogacao->observacoes;
      $colConcluidos[] = $obj;
    }

    return collect((object) $colConcluidos);
  }

  public function index()
  {
    //Montando painel
    $painel = collect();
    $painel->put('recebimentos', collect((object) $this->recebimentos()));
    $painel->put('separacoes', collect((object) $this->separacoes()));
    $painel->put('catalogacoes', collect((object) $this->catalogacoes()));
    $painel->put('ordens', collect((object) $this->ordens()));
    $painel->put('revisoes', collect((object) $this->revisoes()));
    $painel->put('expedicoes', collect((object) $this->expedicoes()));
    $painel->put('concluidos', collect((object) $this->concluidos()));

    return view('painel_acompanhamento.index')->with([
      'painel' => $painel,
    ]);
  }

  public function move(Request $request)
  {
    $from = $request->from;
    $to = $request->to;
    $ids = $request->ids;

    if ($from == 'R' && $to == 'S') {
      //de Recebimentos para Separação
      //Validando cliente
      $cliente_id = null;
      foreach ($ids as $id) {
        $recebimento = Recebimento::findOrFail($id);

        if ($cliente_id != null) {
          if ($cliente_id != $recebimento->idcliente) {
            return response('Os recebimentos não são do mesmo cliente', 503);
          }
        }
        $cliente_id = $recebimento->idcliente;
      }

      //Criando a separação
      $separacao = new Separacao;
      $separacao->cliente_id = $cliente_id;
      $separacao->status = 'S';
      $separacao->save();
      //Adicionando os recebimentos
      $separacao->recebimentos()->sync($ids);

      //Mudando o status dos recebimentos
      foreach ($ids as $id) {
        $recebimento = Recebimento::findOrFail($id);
        $recebimento->status = 'S';
        $recebimento->save();
      }

    }
    else if ($from == 'S' && $to == 'R') {
      //de Separação para Recebimento
      $separacao = Separacao::findOrFail($ids[0]);
      //Mudando o status dos recebimentos
      foreach ($separacao->recebimentos as $receb) {
        $recebimento = Recebimento::findOrFail($receb->id);
        $recebimento->status = null;
        $recebimento->save();
      }
      //Excluindo a separação
      $separacao->delete();
    } 
    else {
      //Catalogação
      foreach ($ids as $id) {
        $catalogacao = Catalogacao::findOrFail($id);
        //$catalogacao->status = $to;
        //$catalogacao->save();
      }
    }

    return response(200);
  }

  public function column(Request $request)
  {
    $data = null;
    $label = null;
    $bg_color = null;
    $text_color = null;
    $multi_drag = null;
    $status = null;

    switch ($request->col) {
      case 'R':
        $data = $this->recebimentos();
        $label = 'Recebimento';
        $bg_color = 'bg-teal-400';
        $text_color = 'blue-grey-700';
        $multi_drag =  true;
        $status = 'R';
        break;
      case 'S':
        $data = $this->separacoes();
        $label = 'Separação';
        $bg_color = 'bg-orange-500';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'S';
        break;
      case 'A':
        $data = $this->catalogacoes();
        $label = 'Catalogando';
        $bg_color = 'bg-yellow-600';
        $text_color = 'blue-grey-700';
        $multi_drag = false;
        $status = 'A';
        break;
      case 'F':
        $data = $this->ordens();
        $label = 'Preparação / Banho';
        $bg_color = 'bg-blue-600';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'F';
        break;
      case 'G':
        $data = $this->revisoes();
        $label = 'Revisão';
        $bg_color = 'bg-red-600';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'G';
        break;
      case 'C':
        $data = $this->expedicoes();
        $label = 'Peças Prontas - Expedição';
        $bg_color = 'bg-green-600';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'C';
        break;
      case 'L':
        $data = $this->concluidos();
        $label = 'Enviado';
        $bg_color = 'bg-grey-600';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'L';
        break;
      default:
        
        break;
    }

    return response()->json(['view' => view('painel_acompanhamento._column', [
      'data' => $data, 
      'label' => $label, 
      'bg_color' => $bg_color, 
      'text_color' => $text_color,
      'multi_drag' => $multi_drag,
      'status' => $status,
    ])->render()]);
  }
}

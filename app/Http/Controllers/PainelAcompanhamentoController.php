<?php

namespace App\Http\Controllers;

use App\Catalogacao;
use App\Cliente;
use App\Material;
use App\OrdemServico;
use App\Recebimento;
use App\Retrabalho;
use App\Separacao;
use App\TipoFalha;
use App\TipoServico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class PainelAcompanhamentoController extends Controller
{
  //Coluna Recebimento
  private function recebimentos()
  {
    $recebimentos = Recebimento::where('data_receb', '>=', date('Y-m-d', strtotime('2020-03-01')))
      ->whereNull('status')
      ->where(function ($query) {
        $query->where('arquivado', '=', '0')->orWhereNull('arquivado');
      })
      ->orderBy('id', 'desc')->get();

    $colRecebimento = [];
    foreach ($recebimentos as $recebimento) {
      $obj = new stdClass();
      $obj->id = $recebimento->id;
      $obj->cliente = $recebimento->cliente->identificacao ?? '';
      $obj->peso = $recebimento->pesototal;
      $obj->peso_real = $recebimento->pesototal;
      $obj->qtde_itens = 1;
      $obj->qtde_check = 0;
      $obj->data_situacao = $recebimento->data_receb;
      $obj->data_carbon = Carbon::parse($recebimento->data_receb . ' ' . $recebimento->hora_receb);
      $obj->data_inicio = Carbon::parse($recebimento->data_receb . ' ' . $recebimento->hora_receb);
      $obj->obs = $recebimento->obs;
      $obj->substatus = null;
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
      $obj->peso_real = $separacao->recebimentos->sum('pesototal');
      $obj->qtde_itens = $separacao->recebimentos->count();
      $obj->qtde_check = 0;
      $obj->data_situacao = $separacao->created_at;
      $obj->data_carbon = Carbon::parse($separacao->created_at);
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = null;
      $obj->substatus = $separacao->status_separacao;
      $colSeparacao[] = $obj;
    }

    return collect((object) $colSeparacao);
  }

  //Coluna Catalogação
  private function catalogacoes()
  {
    $catalogacoes = Separacao::where('status', 'A')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();

    $colCatalogacao = [];
    foreach ($catalogacoes as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      $obj->data_carbon = $separacao->data_inicio_catalogacao ? Carbon::parse($separacao->data_inicio_catalogacao) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = null;
      $colCatalogacao[] = $obj;
    }

    return collect((object) $colCatalogacao);
  }

  //Coluna Ordens
  /*
  private function ordens()
  {
    $ordens = Separacao::where('status', 'F')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();

    $colOrdens = [];
    foreach ($ordens as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      $obj->data_carbon = $separacao->data_inicio_preparacao ? Carbon::parse($separacao->data_inicio_preparacao) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = $separacao->status_banho;
      $colOrdens[] = $obj;
    }

    return collect((object) $colOrdens);
  }
  */

  //Coluna Preparação
  private function preparacao()
  {
    $ordens = Separacao::where('status', 'F')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();

    $colOrdens = [];
    foreach ($ordens as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      $obj->data_carbon = $separacao->data_inicio_preparacao ? Carbon::parse($separacao->data_inicio_preparacao) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = $separacao->status_preparacao ?? 'A'; //TEMPORÁRIO, POIS POR PADRÃO ESTÁ NULO
      $colOrdens[] = $obj;
    }

    return collect((object) $colOrdens);
  }

  //Coluna Banho
  private function banho()
  {
    $ordens = Separacao::where('status', 'B')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();

    $colOrdens = [];
    foreach ($ordens as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      $obj->data_carbon = $separacao->data_inicio_banho ? Carbon::parse($separacao->data_inicio_banho) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = null; //$separacao->status_banho;
      $colOrdens[] = $obj;
    }

    return collect((object) $colOrdens);
  }

  //Coluna Retrabalho
  private function retrabalho()
  {
    $ordens = Separacao::whereHas('retrabalho')->where('status_retrabalho', '<>', 'E')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();

    $colOrdens = [];
    foreach ($ordens as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->retrabalho->peso_total;
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->retrabalho->created_at;
      $obj->data_carbon = $separacao->data_inicio_retrabalho ? Carbon::parse($separacao->data_inicio_retrabalho) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = $separacao->status_retrabalho;
      $colOrdens[] = $obj;
    }

    return collect((object) $colOrdens);
  }

  //Coluna Revisões
  private function revisoes()
  {
    $revisoes = Separacao::whereIn('status', ['P', 'G'])->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->get();

    $colRevisoes = [];
    foreach ($revisoes as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      if ($separacao->status == 'G') {
        $obj->data_carbon = $separacao->data_fim_banho ? Carbon::parse($separacao->data_fim_banho) : null;
      } else {
        $obj->data_carbon = $separacao->data_inicio_revisao ? Carbon::parse($separacao->data_inicio_revisao) : null;
      }
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = $separacao->status == 'G' ? 'G' : 'A';
      $colRevisoes[] = $obj;
    }

    return collect((object) $colRevisoes);
  }

  //Coluna Expedições
  private function expedicoes()
  {
    $expedicoes = Separacao::where('status', 'C')->whereNotNull('cliente_id')->whereHas('catalogacao', function ($query) {
      $query->where('datacad', '>=', date('Y-m-d', strtotime('2020-03-01')));
    })->orderBy('catalogacao_id', 'desc')->get();

    $colExpedicoes = [];
    foreach ($expedicoes as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      $obj->data_carbon = $separacao->data_inicio_expedicao ? Carbon::parse($separacao->data_inicio_expedicao) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = $separacao->status_expedicao;
      $colExpedicoes[] = $obj;
    }

    return collect((object) $colExpedicoes);
  }

  //Coluna Concluídos
  private function concluidos()
  {
    $concluidos = Separacao::where('status', 'L')->whereNotNull('cliente_id')->whereHas('catalogacao')->orderBy('catalogacao_id', 'desc')->take(10)->get();

    $colConcluidos = [];
    foreach ($concluidos as $separacao) {
      $obj = new stdClass();
      $obj->id = $separacao->catalogacao->id;
      $obj->cliente = $separacao->catalogacao->cliente->identificacao ?? '';
      $obj->peso = $separacao->recebimentos->sum('pesototal');
      $obj->peso_real = $separacao->catalogacao->itens->sum('peso_real');
      $obj->qtde_itens = $separacao->catalogacao->itens->count();
      $obj->qtde_check = $separacao->catalogacao->itens->where('status_check', '<>', null)->count();
      $obj->data_situacao = $separacao->catalogacao->datacad;
      $obj->data_carbon = $separacao->data_fim_expedicao ? Carbon::parse($separacao->data_fim_expedicao) : null;
      $obj->data_inicio = Carbon::parse($separacao->created_at);
      $obj->obs = $separacao->catalogacao->observacoes;
      $obj->substatus = null;
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
    //$painel->put('ordens', collect((object) $this->ordens()));
    $painel->put('preparacoes', collect((object) $this->preparacao()));
    $painel->put('banhos', collect((object) $this->banho()));
    $painel->put('retrabalhos', collect((object) $this->retrabalho()));
    $painel->put('revisoes', collect((object) $this->revisoes()));
    $painel->put('expedicoes', collect((object) $this->expedicoes()));
    $painel->put('concluidos', collect((object) $this->concluidos()));

    $clientes = Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();
    $tiposServico = TipoServico::orderBy('descricao')->get();
    $materiais = Material::where('ativo', true)->orderBy('pos')->get();
    $tiposFalha = TipoFalha::select(['id', 'descricao'])->orderBy('descricao')->get();

    return view('painel_acompanhamento.index')->with([
      'painel' => $painel,
      'tiposServico' => $tiposServico,
      'materiais' => $materiais,
      'clientes' => $clientes,
      'tiposFalha' => $tiposFalha,
    ]);
  }

  public function move(Request $request)
  {
    $from = $request->from;
    $to = $request->to;
    $ids = $request->ids;

    if ($from == $to) {
      return response(200);
    }

    do {
      /**
       * De Recebimentos para Separação
       */
      if ($from == 'R' && $to == 'S') {
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
        $separacao->status_separacao = 'A';
        $separacao->save();
        //Adicionando os recebimentos
        $separacao->recebimentos()->sync($ids);

        //Mudando o status dos recebimentos
        foreach ($ids as $id) {
          $recebimento = Recebimento::findOrFail($id);
          $recebimento->status = 'S';
          $recebimento->data_fim = Carbon::now();
          $recebimento->save();
        }

        //Setando as datas de início e fim do recebimento (primeira e última)
        $primeiro_receb = $separacao->recebimentos->sortBy('data_receb')->first();
        $ultimo_receb = $separacao->recebimentos->sortBy('data_receb')->last();
        $separacao->data_inicio_recebimento = Carbon::parse($primeiro_receb->data_receb . ' ' . $primeiro_receb->hora_receb);
        $separacao->data_fim_recebimento = Carbon::parse($ultimo_receb->data_receb . ' ' . $ultimo_receb->hora_receb);
        $separacao->save();
        break;
      }

      /**
       * Do Recebimento para outros que não sejam Separação
       */
      if ($from == 'R' && $to != 'S') {
        return response('Os recebimentos precisam passar pela separação', 503);
        break;
      }

      //De Separação para Recebimento
      if ($from == 'S' && $to == 'R') {
        $separacao = Separacao::findOrFail($ids[0]);
        //Se já tiver catalogação relacionada, não permite voltar
        if ($separacao->catalogacao_id) {
          return response('A separação já entrou em fase de catalogação. Não é possível voltar ao recebimento', 503);
          break;
        }

        //Mudando o status dos recebimentos
        foreach ($separacao->recebimentos as $receb) {
          $recebimento = Recebimento::findOrFail($receb->id);
          $recebimento->status = null;
          $recebimento->data_fim = null;
          $recebimento->save();
        }
        //Excluindo a separação
        $separacao->delete();
        break;
      }

      /**
       * De Separação para Catalogação (A, F, G, C, L)
       */
      if ($from == 'S') {
        $separacao = Separacao::findOrFail($ids[0]);

        //Se não foi encerrada não deixa arrastar
        if (!$separacao->data_fim_separacao) {
          return response('A separação deve ser encerrada antes de entrar em catalogação!', 503);
        }

        //Cria a catalogação
        $catalogacao = new Catalogacao;
        $catalogacao->idcliente = $separacao->cliente_id;
        $catalogacao->datacad = Carbon::now();
        $catalogacao->horacad = Carbon::now();
        $catalogacao->save();

        //Relaciona a separação com a catalogação
        $separacao->catalogacao_id = $catalogacao->id;
        $separacao->data_inicio_catalogacao = Carbon::parse($catalogacao->datacad);
        $separacao->status = $to;
        $separacao->save();

        break;
      }

      /**
       * Catalogação (se chegou até aqui é de catalogação para catalogação)
       */
      foreach ($ids as $id) {
        //Localiza a catalogação
        $catalogacao = Catalogacao::findOrFail($id);

        //Localiza a separação
        $separacao = Separacao::where('catalogacao_id', $id)->get()->first();

        //Se já tiver catalogação relacionada, não permite voltar
        if ($to == 'S') {
          return response('A separação já entrou em fase de catalogação. Não é possível voltar ao recebimento', 503);
          break;
        }

        //Encerra a data do status anterior
        switch ($from) {
          case 'A':
            if (!$separacao->data_fim_catalogacao) {
              $separacao->data_fim_catalogacao = Carbon::now();
            }
            break;
          case 'F':
            if (!$separacao->data_fim_preparacao) {
              $separacao->data_fim_preparacao = Carbon::now();
            }
            break;
          case 'B':
            if (!$separacao->data_fim_banho) {
              $separacao->data_fim_banho = Carbon::now();
            }
            break;
          case 'T':
            if (!$separacao->data_fim_retrabalho) {
              $separacao->data_fim_retrabalho = Carbon::now();
            }
            break;
          case 'G':
            //Só pega a data fim da revisão se não tiver preenchida
            //Pode ser que tenha sido preenchida ao finalizar o check list
            if (!$separacao->data_fim_revisao) {
              $separacao->data_fim_revisao = Carbon::now();
            }
            break;
          case 'C':
            if (!$separacao->data_fim_expedicao) {
              $separacao->data_fim_expedicao = Carbon::now();
            }
            break;
          case 'L':
            //Não faz nada
            break;
          default:
            //Não faz nada
            break;
        }

        //Insere a data do status atual
        switch ($to) {
          case 'A':
            if (!$separacao->data_inicio_catalogacao) {
              $separacao->data_inicio_catalogacao = Carbon::now();
            }
            break;
          case 'F':
            $separacao->status_preparacao = 'G';
            //Não faz nada, pois só inicia a contagem quando clicar no menu iniciar
            //$separacao->data_inicio_banho = Carbon::now();
            break;
          case 'B':
            //Se não foi encerrada não deixa arrastar
            if ($separacao->status_preparacao == 'G') {
              return response('O processo de preparação não foi iniciado!', 503);
            }
            //$separacao->status_banho = 'A';
            //Não faz nada, pois só inicia a contagem quando clicar no menu iniciar
            //Sobre comentário acima: agora faz pois não teremos mais o menu iniciar nesse box
            //$separacao->data_inicio_preparacao = Carbon::now();
            if (!$separacao->data_inicio_banho) {
              $separacao->data_inicio_banho = Carbon::now();
            }
            $separacao->status_banho = 'A';
            break;
          case 'T':
            $separacao->status_retrabalho = 'G';
            //Zera tudo que foi pela frente
            $separacao->data_inicio_revisao = null;
            $separacao->data_fim_revisao = null;
            $separacao->data_fim_expedicao = null;
            $separacao->data_inicio_expedicao = null;
            break;
          case 'G':
            //Se não foi encerrada não deixa arrastar
            //if ($separacao->status_banho == 'G') {
            //  return response('O processo de banho não foi iniciado!', 503);
            //}
            if ($separacao->status_retrabalho == 'G') {
              return response('O processo de retrabalho não foi iniciado!', 503);
            }
            //Não faz nada, pois só inicia a revisão quando abrir pelo checklist
            //$separacao->data_inicio_revisao = Carbon::now();
            break;
          case 'C':
            //ATUALIZADO 09/02/2021 - 
            //Antes: Não faz nada, pois só inicia a contagem quando clicar no menu iniciar
            //Agora: Entra direto em Andamento
            //$separacao->status_expedicao = 'G';
            $separacao->status_expedicao = 'A';
            if (!$separacao->data_inicio_expedicao) {
              $separacao->data_inicio_expedicao = Carbon::now();
            }
            break;
          case 'L':
            if ($separacao->status_expedicao == 'G') {
              return response('O processo de expedição não foi iniciado!', 503);
            }
            //Não faz nada
            break;
          default:
            //Não faz nada
            break;
        }

        //Caso seja retrabalho não atualiza o status do processo, para simular um clone, já que os 
        //retrabalhos são um processo a parte
        if ($to != 'T') {
          //Atualiza a catalogação
          $catalogacao->status = $to;
          $catalogacao->save();

          //Atualiza a separação
          $separacao->status = $to;
          $separacao->save();
        }
      }
      break;
    } while (0);

    return response(200);
  }

  public function arquivar(Request $request)
  {
    $recebimento = Recebimento::findOrFail($request->id);
    $recebimento->arquivado = true;
    $recebimento->save();
    return response(200);
  }

  public function encerrarSeparacao(Request $request)
  {
    $separacao = Separacao::findOrFail($request->id);
    $separacao->data_fim_separacao = Carbon::now();
    $separacao->status_separacao = 'E';
    $separacao->save();
    return response(200);
  }

  public function encerrarRetrabalho(Request $request)
  {
    $retrab = Catalogacao::findOrFail($request->id);
    $separacao = $retrab->separacao;
    $separacao->data_fim_retrabalho = Carbon::now();
    $separacao->status_retrabalho = 'E';
    $separacao->save();

    $retrabalho = Retrabalho::findOrFail($separacao->retrabalho_id);
    $retrabalho->data_fim = Carbon::now();
    $retrabalho->status = 'E';
    $retrabalho->save();

    return response(200);
  }

  public function iniciarPreparacao(Request $request)
  {
    $banho = Catalogacao::findOrFail($request->id);
    $separacao = $banho->separacao;
    if (!$separacao->data_inicio_preparacao) {
      $separacao->data_inicio_preparacao = Carbon::now();
    }
    $separacao->status_preparacao = 'A';
    $separacao->save();
    return response(200);
  }

  public function iniciarRetrabalho(Request $request)
  {
    $retrab = Catalogacao::findOrFail($request->id);
    $separacao = $retrab->separacao;
    $separacao->data_inicio_retrabalho = Carbon::now();
    $separacao->status_retrabalho = 'A';
    $separacao->save();

    $retrabalho = Retrabalho::findOrFail($separacao->retrabalho_id);
    $retrabalho->data_inicio = Carbon::now();
    $retrabalho->status = 'A';
    $retrabalho->save();

    return response(200);
  }

  public function iniciarExpedicao(Request $request)
  {
    $exped = Catalogacao::findOrFail($request->id);
    $separacao = $exped->separacao;
    if (!$separacao->data_inicio_expedicao) {
      $separacao->data_inicio_expedicao = Carbon::now();
    }
    $separacao->status_expedicao = 'A';
    $separacao->save();
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
        $data = $this->preparacao();
        $label = 'Preparação';
        $bg_color = 'bg-blue-600';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'F';
        break;
      case 'B':
        $data = $this->banho();
        $label = 'Banho';
        $bg_color = 'bg-purple-500';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'B';
        break;
      case 'T':
        $data = $this->retrabalho();
        $label = 'Retrabalho';
        $bg_color = 'bg-brown-500';
        $text_color = 'text-white';
        $multi_drag = false;
        $status = 'T';
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

  public function separacaoFromCatalogacao(Request $request, $id)
  {
    $cat = Catalogacao::findOrFail($id);
    $item = $cat->separacao;

    return response()->json($item);
  }
}

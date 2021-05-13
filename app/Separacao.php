<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Separacao extends Model
{
  public const ETAPA_RECEBIMENTO = 1;
  public const ETAPA_SEPARACAO = 2;
  public const ETAPA_CATALOGACAO = 3;
  public const ETAPA_PREPARACAO = 4;
  public const ETAPA_BANHO = 5;
  public const ETAPA_REVISAO = 6;
  public const ETAPA_EXPEDICAO = 7;

  protected $table = 'separacoes';

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'cliente_id');
  }

  public function catalogacao()
  {
    return $this->belongsTo('App\Catalogacao', 'catalogacao_id');
  }

  public function recebimentos()
  {
    return $this->belongsToMany('App\Recebimento', 'separacoes_recebimentos', 'separacao_id', 'recebimento_id');
  }

  /** Peso total de todos os recebimentos */
  public function getPesoRecebimentosAttribute()
  {
    return $this->recebimentos ? $this->recebimentos->sum('pesototal') : 0;
  }

  public function getTotalItensCatalogacaoAttribute()
  {
    return $this->catalogacao_id ? $this->catalogacao->itens->count() : 0;
  }

  /** Etapas */

  public function primeiraEtapa($from = Separacao::ETAPA_RECEBIMENTO)
  {
    if ($this->inicioRecebimento() && $from <= Separacao::ETAPA_RECEBIMENTO) {
      return $this->inicioRecebimento();
    }

    if ($this->inicioCatalogacao() && $from <= Separacao::ETAPA_CATALOGACAO) {
      return $this->inicioCatalogacao();
    }

    if ($this->inicioPreparacao() && $from <= Separacao::ETAPA_PREPARACAO) {
      return $this->inicioPreparacao();
    }

    if ($this->inicioBanho() && $from <= Separacao::ETAPA_BANHO) {
      return $this->inicioBanho();
    }

    if ($this->inicioRevisao() && $from <= Separacao::ETAPA_REVISAO) {
      return $this->inicioRevisao();
    }

    if ($this->inicioExpedicao() && $from <= Separacao::ETAPA_EXPEDICAO) {
      return $this->inicioExpedicao();
    }

    return $this->inicioSeparacao();
  }

  public function ultimaEtapa($from = Separacao::ETAPA_EXPEDICAO)
  {
    if ($this->fimExpedicao() && $from >= Separacao::ETAPA_EXPEDICAO) {
      return $this->fimExpedicao();
    }

    if ($this->fimRevisao() && $from >= Separacao::ETAPA_REVISAO) {
      return $this->fimRevisao();
    }

    if ($this->fimBanho() && $from >= Separacao::ETAPA_BANHO) {
      return $this->fimBanho();
    }

    if ($this->fimPreparacao() && $from >= Separacao::ETAPA_PREPARACAO) {
      return $this->fimPreparacao();
    }

    if ($this->fimCatalogacao() && $from >= Separacao::ETAPA_CATALOGACAO) {
      return $this->fimCatalogacao();
    }

    if ($this->fimRecebimento() && $from >= Separacao::ETAPA_RECEBIMENTO) {
      return $this->fimRecebimento();
    }

    return $this->fimSeparacao();
  }

  /** Início e Fim de cada etapa */

  public function inicioRecebimento()
  {
    return $this->recebimentos->sortBy('id')->first() ? \Carbon\Carbon::parse($this->recebimentos->sortBy('id')->first()->data_receb . ' ' . $this->recebimentos->sortBy('id')->first()->hora_receb) : null;
  }

  public function fimRecebimento()
  {
    return $this->recebimentos->sortBy('id')->last() ? \Carbon\Carbon::parse($this->recebimentos->sortBy('id')->last()->data_receb . ' ' . $this->recebimentos->sortBy('id')->last()->hora_receb) : null;
  }

  public function inicioSeparacao()
  {
    return \Carbon\Carbon::parse($this->created_at);
  }

  public function fimSeparacao()
  {
    return $this->data_fim_separacao ? \Carbon\Carbon::parse($this->data_fim_separacao) : null;
  }

  public function inicioCatalogacao()
  {
    return $this->data_inicio_catalogacao ? \Carbon\Carbon::parse($this->data_inicio_catalogacao) : null;
  }

  public function fimCatalogacao()
  {
    return $this->data_fim_catalogacao ? \Carbon\Carbon::parse($this->data_fim_catalogacao) : null;
  }

  public function inicioPreparacao()
  {
    return $this->data_inicio_preparacao ? \Carbon\Carbon::parse($this->data_inicio_preparacao) : null;
  }

  public function fimPreparacao()
  {
    return $this->data_fim_preparacao ? \Carbon\Carbon::parse($this->data_fim_preparacao) : null;
  }

  public function inicioBanho()
  {
    return $this->data_inicio_banho ? \Carbon\Carbon::parse($this->data_inicio_banho) : null;
  }

  public function fimBanho()
  {
    return $this->data_fim_banho ? \Carbon\Carbon::parse($this->data_fim_banho) : null;
  }

  public function inicioRetrabalho()
  {
    return $this->data_inicio_retrabalho ? \Carbon\Carbon::parse($this->data_inicio_retrabalho) : null;
  }

  public function fimRetrabalho()
  {
    return $this->data_fim_retrabalho ? \Carbon\Carbon::parse($this->data_fim_retrabalho) : null;
  }

  public function inicioRevisao()
  {
    return $this->data_inicio_revisao ? \Carbon\Carbon::parse($this->data_inicio_revisao) : null;
  }

  public function fimRevisao()
  {
    return $this->data_fim_revisao ? \Carbon\Carbon::parse($this->data_fim_revisao) : null;
  }

  public function inicioExpedicao()
  {
    return $this->data_inicio_expedicao ? \Carbon\Carbon::parse($this->data_inicio_expedicao) : null;
  }

  public function fimExpedicao()
  {
    return $this->data_fim_expedicao ? \Carbon\Carbon::parse($this->data_fim_expedicao) : null;
  }

  /** Primeira e última data */
  public function getPrimeiraDataAttribute()
  {
    return $this->primeiraEtapa();
  }

  public function getUltimaDataAttribute()
  {
    return $this->ultimaEtapa();
  }

  /**Início e fim de cada etapa formatada em d/m/Y H:i:s */

  public function getInicioRecebimentoFormattedAttribute()
  {
    return $this->inicioRecebimento() ? $this->inicioRecebimento()->format('d/m/Y H:i:s') : '';
  }

  public function getFimRecebimentoFormattedAttribute()
  {
    return $this->fimRecebimento() ? $this->fimRecebimento()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioSeparacaoFormattedAttribute()
  {
    return $this->inicioSeparacao() ? $this->inicioSeparacao()->format('d/m/Y H:i:s') : '';
  }

  public function getFimSeparacaoFormattedAttribute()
  {
    return $this->fimSeparacao() ? $this->fimSeparacao()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioCatalogacaoFormattedAttribute()
  {
    return $this->inicioCatalogacao() ? $this->inicioCatalogacao()->format('d/m/Y H:i:s') : '';
  }

  public function getFimCatalogacaoFormattedAttribute()
  {
    return $this->fimCatalogacao() ? $this->fimCatalogacao()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioPreparacaoFormattedAttribute()
  {
    return $this->inicioPreparacao() ? $this->inicioPreparacao()->format('d/m/Y H:i:s') : '';
  }

  public function getFimPreparacaoFormattedAttribute()
  {
    return $this->fimPreparacao() ? $this->fimPreparacao()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioBanhoFormattedAttribute()
  {
    return $this->inicioBanho() ? $this->inicioBanho()->format('d/m/Y H:i:s') : '';
  }

  public function getFimBanhoFormattedAttribute()
  {
    return $this->fimBanho() ? $this->fimBanho()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioRetrabalhoFormattedAttribute()
  {
    return $this->inicioRetrabalho() ? $this->inicioRetrabalho()->format('d/m/Y H:i:s') : '';
  }

  public function getFimRetrabalhoFormattedAttribute()
  {
    return $this->fimRetrabalho() ? $this->fimRetrabalho()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioRevisaoFormattedAttribute()
  {
    return $this->inicioRevisao() ? $this->inicioRevisao()->format('d/m/Y H:i:s') : '';
  }

  public function getFimRevisaoFormattedAttribute()
  {
    return $this->fimRevisao() ? $this->fimRevisao()->format('d/m/Y H:i:s') : '';
  }

  public function getInicioExpedicaoFormattedAttribute()
  {
    return $this->inicioExpedicao() ? $this->inicioExpedicao()->format('d/m/Y H:i:s') : '';
  }

  public function getFimExpedicaoFormattedAttribute()
  {
    return $this->fimExpedicao() ? $this->fimExpedicao()->format('d/m/Y H:i:s') : '';
  }

  /** Tempo em segundos entre as próprias etapas e entre elas */

  public function getSecondsBetweenRecebimentoAttribute()
  {
    return ($this->inicioRecebimento() && $this->fimRecebimento()) ? $this->inicioRecebimento()->diffInSeconds($this->fimRecebimento()) : 0;
  }

  public function getSecondsBetweenRecebimentoSeparacaoAttribute()
  {
    return ($this->fimRecebimento() && $this->inicioSeparacao()) ? $this->fimRecebimento()->diffInSeconds($this->inicioSeparacao()) : 0;
  }

  public function getSecondsBetweenSeparacaoAttribute()
  {
    return ($this->inicioSeparacao() && $this->fimSeparacao()) ? $this->inicioSeparacao()->diffInSeconds($this->fimSeparacao()) : 0;
  }

  public function getSecondsBetweenSeparacaoCatalogacaoAttribute()
  {
    return ($this->fimSeparacao() && $this->inicioCatalogacao()) ? $this->fimSeparacao()->diffInSeconds($this->inicioCatalogacao()) : 0;
  }

  public function getSecondsBetweenCatalogacaoAttribute()
  {
    return ($this->inicioCatalogacao() && $this->fimCatalogacao()) ? $this->inicioCatalogacao()->diffInSeconds($this->fimCatalogacao()) : 0;
  }

  public function getSecondsBetweenCatalogacaoPreparacaoAttribute()
  {
    return ($this->fimCatalogacao() && $this->inicioPreparacao()) ? $this->fimCatalogacao()->diffInSeconds($this->inicioPreparacao()) : 0;
  }

  public function getSecondsBetweenPreparacaoAttribute()
  {
    return ($this->inicioPreparacao() && $this->fimPreparacao()) ? $this->inicioPreparacao()->diffInSeconds($this->fimPreparacao()) : 0;
  }

  public function getSecondsBetweenPreparacaoBanhoAttribute()
  {
    return ($this->fimPreparacao() && $this->inicioBanho()) ? $this->fimPreparacao()->diffInSeconds($this->inicioBanho()) : 0;
  }

  public function getSecondsBetweenBanhoAttribute()
  {
    return ($this->inicioBanho() && $this->fimBanho()) ? $this->inicioBanho()->diffInSeconds($this->fimBanho()) : 0;
  }

  public function getSecondsBetweenBanhoRevisaoAttribute()
  {
    return ($this->fimBanho() && $this->inicioRevisao()) ? $this->fimBanho()->diffInSeconds($this->inicioRevisao()) : 0;
  }

  public function getSecondsBetweenRevisaoAttribute()
  {
    return ($this->inicioRevisao() && $this->fimRevisao()) ? $this->inicioRevisao()->diffInSeconds($this->fimRevisao()) : 0;
  }

  public function getSecondsBetweenRevisaoExpedicaoAttribute()
  {
    return ($this->fimRevisao() && $this->inicioExpedicao()) ? $this->fimRevisao()->diffInSeconds($this->inicioExpedicao()) : 0;
  }

  public function getSecondsBetweenExpedicaoAttribute()
  {
    return ($this->inicioExpedicao() && $this->fimExpedicao()) ? $this->inicioExpedicao()->diffInSeconds($this->fimExpedicao()) : 0;
  }

  /** Tempo formatado em forHumans entre as próprias etapas e entre cada uma */

  public function getDiffForHumansBetweenRecebimentoAttribute()
  {
    return ($this->inicioRecebimento() && $this->fimRecebimento()) ? $this->inicioRecebimento()->diffForHumans($this->fimRecebimento(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenRecebimentoSeparacaoAttribute()
  {
    return ($this->fimRecebimento() && $this->inicioSeparacao()) ? $this->fimRecebimento()->diffForHumans($this->inicioSeparacao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenSeparacaoAttribute()
  {
    return ($this->inicioSeparacao() && $this->fimSeparacao()) ? $this->inicioSeparacao()->diffForHumans($this->fimSeparacao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenSeparacaoCatalogacaoAttribute()
  {
    return ($this->fimSeparacao() && $this->inicioCatalogacao()) ? $this->fimSeparacao()->diffForHumans($this->inicioCatalogacao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenCatalogacaoAttribute()
  {
    return ($this->inicioCatalogacao() && $this->fimCatalogacao()) ? $this->inicioCatalogacao()->diffForHumans($this->fimCatalogacao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenCatalogacaoPreparacaoAttribute()
  {
    return ($this->fimCatalogacao() && $this->inicioPreparacao()) ? $this->fimCatalogacao()->diffForHumans($this->inicioPreparacao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenPreparacaoAttribute()
  {
    return ($this->inicioPreparacao() && $this->fimPreparacao()) ? $this->inicioPreparacao()->diffForHumans($this->fimPreparacao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenPreparacaoBanhoAttribute()
  {
    return ($this->fimPreparacao() && $this->inicioBanho()) ? $this->fimPreparacao()->diffForHumans($this->inicioBanho(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenBanhoAttribute()
  {
    return ($this->inicioBanho() && $this->fimBanho()) ? $this->inicioBanho()->diffForHumans($this->fimBanho(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenBanhoRevisaoAttribute()
  {
    return ($this->fimBanho() && $this->inicioRevisao()) ? $this->fimBanho()->diffForHumans($this->inicioRevisao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenRevisaoAttribute()
  {
    return ($this->inicioRevisao() && $this->fimRevisao()) ? $this->inicioRevisao()->diffForHumans($this->fimRevisao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenRevisaoExpedicaoAttribute()
  {
    return ($this->fimRevisao() && $this->inicioExpedicao()) ? $this->fimRevisao()->diffForHumans($this->inicioExpedicao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  public function getDiffForHumansBetweenExpedicaoAttribute()
  {
    return ($this->inicioExpedicao() && $this->fimExpedicao()) ? $this->inicioExpedicao()->diffForHumans($this->fimExpedicao(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : 0;
  }

  /** Tempo útil formatado forHumans entre as etapas em si e entre cada uma */
  public function getBusinessTimeBetweenRecebimentoAttribute()
  {
    return ($this->inicioRecebimento() && $this->fimRecebimento()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioRecebimento(), $this->fimRecebimento()) : 0;
  }

  public function getBusinessTimeBetweenRecebimentoSeparacaoAttribute()
  {
    return ($this->fimRecebimento() && $this->inicioSeparacao()) ? \App\Helpers\HorasUteis::calculaHoras($this->fimRecebimento(), $this->inicioSeparacao()) : 0;
  }

  public function getBusinessTimeBetweenSeparacaoAttribute()
  {
    return ($this->inicioSeparacao() && $this->fimSeparacao()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioSeparacao(), $this->fimSeparacao()) : 0;
  }

  public function getBusinessTimeBetweenSeparacaoCatalogacaoAttribute()
  {
    return ($this->fimSeparacao() && $this->inicioCatalogacao()) ? \App\Helpers\HorasUteis::calculaHoras($this->fimSeparacao(), $this->inicioCatalogacao()) : 0;
  }

  public function getBusinessTimeBetweenCatalogacaoAttribute()
  {
    return ($this->inicioCatalogacao() && $this->fimCatalogacao()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioCatalogacao(), $this->fimCatalogacao()) : 0;
  }

  public function getBusinessTimeBetweenCatalogacaoPreparacaoAttribute()
  {
    return ($this->fimCatalogacao() && $this->inicioPreparacao()) ? \App\Helpers\HorasUteis::calculaHoras($this->fimCatalogacao(), $this->inicioPreparacao()) : 0;
  }

  public function getBusinessTimeBetweenPreparacaoAttribute()
  {
    return ($this->inicioPreparacao() && $this->fimPreparacao()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioPreparacao(), $this->fimPreparacao()) : 0;
  }

  public function getBusinessTimeBetweenPreparacaoBanhoAttribute()
  {
    return ($this->fimPreparacao() && $this->inicioBanho()) ? \App\Helpers\HorasUteis::calculaHoras($this->fimPreparacao(), $this->inicioBanho()) : 0;
  }

  public function getBusinessTimeBetweenBanhoAttribute()
  {
    return ($this->inicioBanho() && $this->fimBanho()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioBanho(), $this->fimBanho()) : 0;
  }

  public function getBusinessTimeBetweenBanhoRevisaoAttribute()
  {
    return ($this->fimBanho() && $this->inicioRevisao()) ? \App\Helpers\HorasUteis::calculaHoras($this->fimBanho(), $this->inicioRevisao()) : 0;
  }

  public function getBusinessTimeBetweenRevisaoAttribute()
  {
    return ($this->inicioRevisao() && $this->fimRevisao()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioRevisao(), $this->fimRevisao()) : 0;
  }

  public function getBusinessTimeBetweenRevisaoExpedicaoAttribute()
  {
    return ($this->fimRevisao() && $this->inicioExpedicao()) ? \App\Helpers\HorasUteis::calculaHoras($this->fimRevisao(), $this->inicioExpedicao()) : 0;
  }

  public function getBusinessTimeBetweenExpedicaoAttribute()
  {
    return ($this->inicioExpedicao() && $this->fimExpedicao()) ? \App\Helpers\HorasUteis::calculaHoras($this->inicioExpedicao(), $this->fimExpedicao()) : 0;
  }

  /** Tempo útil em segundos entre cada uma das etapas */
  public function getBusinessTimeSecondsBetweenRecebimentoAttribute()
  {
    return ($this->inicioRecebimento() && $this->fimRecebimento()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioRecebimento(), $this->fimRecebimento()) : 0;
  }

  public function getBusinessTimeSecondsBetweenRecebimentoSeparacaoAttribute()
  {
    return ($this->fimRecebimento() && $this->inicioSeparacao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->fimRecebimento(), $this->inicioSeparacao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenSeparacaoAttribute()
  {
    return ($this->inicioSeparacao() && $this->fimSeparacao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioSeparacao(), $this->fimSeparacao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenSeparacaoCatalogacaoAttribute()
  {
    return ($this->fimSeparacao() && $this->inicioCatalogacao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->fimSeparacao(), $this->inicioCatalogacao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenCatalogacaoAttribute()
  {
    return ($this->inicioCatalogacao() && $this->fimCatalogacao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioCatalogacao(), $this->fimCatalogacao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenCatalogacaoPreparacaoAttribute()
  {
    return ($this->fimCatalogacao() && $this->inicioPreparacao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->fimCatalogacao(), $this->inicioPreparacao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenPreparacaoAttribute()
  {
    return ($this->inicioPreparacao() && $this->fimPreparacao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioPreparacao(), $this->fimPreparacao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenPreparacaoBanhoAttribute()
  {
    return ($this->fimPreparacao() && $this->inicioBanho()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->fimPreparacao(), $this->inicioBanho()) : 0;
  }

  public function getBusinessTimeSecondsBetweenBanhoAttribute()
  {
    return ($this->inicioBanho() && $this->fimBanho()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioBanho(), $this->fimBanho()) : 0;
  }

  public function getBusinessTimeSecondsBetweenBanhoRevisaoAttribute()
  {
    return ($this->fimBanho() && $this->inicioRevisao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->fimBanho(), $this->inicioRevisao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenRevisaoAttribute()
  {
    return ($this->inicioRevisao() && $this->fimRevisao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioRevisao(), $this->fimRevisao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenRevisaoExpedicaoAttribute()
  {
    return ($this->fimRevisao() && $this->inicioExpedicao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->fimRevisao(), $this->inicioExpedicao()) : 0;
  }

  public function getBusinessTimeSecondsBetweenExpedicaoAttribute()
  {
    return ($this->inicioExpedicao() && $this->fimExpedicao()) ? \App\Helpers\HorasUteis::calculaIntervalo($this->inicioExpedicao(), $this->fimExpedicao()) : 0;
  }

  /** Tempos totais em segundos */
  public function getTotalSecondsTempoExecucaoAttribute()
  {
    return $this->primeiraEtapa()->diffInSeconds($this->ultimaEtapa());
  }

  /** Tempos totais forHumans */

  public function tempoExecucaoForHumans($start = Separacao::ETAPA_RECEBIMENTO, $end = Separacao::ETAPA_EXPEDICAO)
  {
    return $this->primeiraEtapa($start)->diffForHumans($this->ultimaEtapa($end), \Carbon\CarbonInterface::DIFF_ABSOLUTE, false, 4);
  }

  public function getTempoExecucaoForHumansAttribute()
  {
    return $this->tempoExecucaoForHumans();
  }

  public function tempoOciosoForHumans($start = Separacao::ETAPA_RECEBIMENTO, $end = Separacao::ETAPA_EXPEDICAO)
  {
    $tempo_ocioso = 0;
    if ($start <= Separacao::ETAPA_RECEBIMENTO && $end >= Separacao::ETAPA_SEPARACAO) {
      $tempo_ocioso += $this->getSecondsBetweenRecebimentoSeparacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_SEPARACAO && $end >= Separacao::ETAPA_CATALOGACAO) {
      $tempo_ocioso += $this->getSecondsBetweenSeparacaoCatalogacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_CATALOGACAO && $end >= Separacao::ETAPA_PREPARACAO) {
      $tempo_ocioso += $this->getSecondsBetweenCatalogacaoPreparacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_PREPARACAO && $end >= Separacao::ETAPA_BANHO) {
      $tempo_ocioso += $this->getSecondsBetweenPreparacaoBanhoAttribute();
    }
    if ($start <= Separacao::ETAPA_BANHO && $end >= Separacao::ETAPA_REVISAO) {
      $tempo_ocioso += $this->getSecondsBetweenBanhoRevisaoAttribute();
    }
    if ($start <= Separacao::ETAPA_REVISAO && $end >= Separacao::ETAPA_EXPEDICAO) {
      $tempo_ocioso += $this->getSecondsBetweenRevisaoExpedicaoAttribute();
    }

    return \Carbon\CarbonInterval::seconds($tempo_ocioso)->cascade()->forHumans();
  }

  public function getTempoOciosoForHumansAttribute()
  {
    return $this->tempoOciosoForHumans();
  }

  /** Tempos úteis forHumans */

  public function businessTimeTempoExecucaoForHumans($start = Separacao::ETAPA_RECEBIMENTO, $end = Separacao::ETAPA_EXPEDICAO)
  {
    return \App\Helpers\HorasUteis::calculaHoras($this->primeiraEtapa($start), $this->ultimaEtapa($end), false);
  }

  public function getBusinessTimeTempoExecucaoForHumansAttribute()
  {
    return $this->businessTimeTempoExecucaoForHumans();
  }

  public function businessTimeTempoOciosoForHumans($start = Separacao::ETAPA_RECEBIMENTO, $end = Separacao::ETAPA_EXPEDICAO)
  {
    $tempo_ocioso = 0;
    if ($start <= Separacao::ETAPA_RECEBIMENTO && $end >= Separacao::ETAPA_SEPARACAO) {
      $tempo_ocioso += $this->getBusinessTimeSecondsBetweenRecebimentoSeparacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_SEPARACAO && $end >= Separacao::ETAPA_CATALOGACAO) {
      $tempo_ocioso += $this->getBusinessTimeSecondsBetweenSeparacaoCatalogacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_CATALOGACAO && $end >= Separacao::ETAPA_PREPARACAO) {
      $tempo_ocioso += $this->getBusinessTimeSecondsBetweenCatalogacaoPreparacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_PREPARACAO && $end >= Separacao::ETAPA_BANHO) {
      $tempo_ocioso += $this->getBusinessTimeSecondsBetweenPreparacaoBanhoAttribute();
    }
    if ($start <= Separacao::ETAPA_BANHO && $end >= Separacao::ETAPA_REVISAO) {
      $tempo_ocioso += $this->getBusinessTimeSecondsBetweenBanhoRevisaoAttribute();
    }
    if ($start <= Separacao::ETAPA_REVISAO && $end >= Separacao::ETAPA_EXPEDICAO) {
      $tempo_ocioso += $this->getBusinessTimeSecondsBetweenRevisaoExpedicaoAttribute();
    }

    return \Carbon\CarbonInterval::seconds($tempo_ocioso)->cascade()->forHumans();
  }

  public function getBusinessTimeTempoOciosoForHumansAttribute()
  {
    return $this->businessTimeTempoOciosoForHumans();
  }

  /** Dias úteis formatado forHumans entre as etapas em si e entre cada uma */
  public function getBusinessDaysBetweenRecebimentoAttribute()
  {
    return ($this->inicioRecebimento() && $this->fimRecebimento()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioRecebimento(), $this->fimRecebimento()) : 0;
  }

  public function getBusinessDaysBetweenRecebimentoSeparacaoAttribute()
  {
    return ($this->fimRecebimento() && $this->inicioSeparacao()) ? \App\Helpers\DiasUteis::calculaHoras($this->fimRecebimento(), $this->inicioSeparacao()) : 0;
  }

  public function getBusinessDaysBetweenSeparacaoAttribute()
  {
    return ($this->inicioSeparacao() && $this->fimSeparacao()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioSeparacao(), $this->fimSeparacao()) : 0;
  }

  public function getBusinessDaysBetweenSeparacaoCatalogacaoAttribute()
  {
    return ($this->fimSeparacao() && $this->inicioCatalogacao()) ? \App\Helpers\DiasUteis::calculaHoras($this->fimSeparacao(), $this->inicioCatalogacao()) : 0;
  }

  public function getBusinessDaysBetweenCatalogacaoAttribute()
  {
    return ($this->inicioCatalogacao() && $this->fimCatalogacao()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioCatalogacao(), $this->fimCatalogacao()) : 0;
  }

  public function getBusinessDaysBetweenCatalogacaoPreparacaoAttribute()
  {
    return ($this->fimCatalogacao() && $this->inicioPreparacao()) ? \App\Helpers\DiasUteis::calculaHoras($this->fimCatalogacao(), $this->inicioPreparacao()) : 0;
  }

  public function getBusinessDaysBetweenPreparacaoAttribute()
  {
    return ($this->inicioPreparacao() && $this->fimPreparacao()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioPreparacao(), $this->fimPreparacao()) : 0;
  }

  public function getBusinessDaysBetweenPreparacaoBanhoAttribute()
  {
    return ($this->fimPreparacao() && $this->inicioBanho()) ? \App\Helpers\DiasUteis::calculaHoras($this->fimPreparacao(), $this->inicioBanho()) : 0;
  }

  public function getBusinessDaysBetweenBanhoAttribute()
  {
    return ($this->inicioBanho() && $this->fimBanho()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioBanho(), $this->fimBanho()) : 0;
  }

  public function getBusinessDaysBetweenBanhoRevisaoAttribute()
  {
    return ($this->fimBanho() && $this->inicioRevisao()) ? \App\Helpers\DiasUteis::calculaHoras($this->fimBanho(), $this->inicioRevisao()) : 0;
  }

  public function getBusinessDaysBetweenRevisaoAttribute()
  {
    return ($this->inicioRevisao() && $this->fimRevisao()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioRevisao(), $this->fimRevisao()) : 0;
  }

  public function getBusinessDaysBetweenRevisaoExpedicaoAttribute()
  {
    return ($this->fimRevisao() && $this->inicioExpedicao()) ? \App\Helpers\DiasUteis::calculaHoras($this->fimRevisao(), $this->inicioExpedicao()) : 0;
  }

  public function getBusinessDaysBetweenExpedicaoAttribute()
  {
    return ($this->inicioExpedicao() && $this->fimExpedicao()) ? \App\Helpers\DiasUteis::calculaHoras($this->inicioExpedicao(), $this->fimExpedicao()) : 0;
  }

  /** Tempo útil em segundos entre cada uma das etapas */
  public function getBusinessDaysSecondsBetweenRecebimentoAttribute()
  {
    return ($this->inicioRecebimento() && $this->fimRecebimento()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioRecebimento(), $this->fimRecebimento()) : 0;
  }

  public function getBusinessDaysSecondsBetweenRecebimentoSeparacaoAttribute()
  {
    return ($this->fimRecebimento() && $this->inicioSeparacao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->fimRecebimento(), $this->inicioSeparacao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenSeparacaoAttribute()
  {
    return ($this->inicioSeparacao() && $this->fimSeparacao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioSeparacao(), $this->fimSeparacao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenSeparacaoCatalogacaoAttribute()
  {
    return ($this->fimSeparacao() && $this->inicioCatalogacao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->fimSeparacao(), $this->inicioCatalogacao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenCatalogacaoAttribute()
  {
    return ($this->inicioCatalogacao() && $this->fimCatalogacao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioCatalogacao(), $this->fimCatalogacao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenCatalogacaoPreparacaoAttribute()
  {
    return ($this->fimCatalogacao() && $this->inicioPreparacao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->fimCatalogacao(), $this->inicioPreparacao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenPreparacaoAttribute()
  {
    return ($this->inicioPreparacao() && $this->fimPreparacao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioPreparacao(), $this->fimPreparacao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenPreparacaoBanhoAttribute()
  {
    return ($this->fimPreparacao() && $this->inicioBanho()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->fimPreparacao(), $this->inicioBanho()) : 0;
  }

  public function getBusinessDaysSecondsBetweenBanhoAttribute()
  {
    return ($this->inicioBanho() && $this->fimBanho()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioBanho(), $this->fimBanho()) : 0;
  }

  public function getBusinessDaysSecondsBetweenBanhoRevisaoAttribute()
  {
    return ($this->fimBanho() && $this->inicioRevisao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->fimBanho(), $this->inicioRevisao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenRevisaoAttribute()
  {
    return ($this->inicioRevisao() && $this->fimRevisao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioRevisao(), $this->fimRevisao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenRevisaoExpedicaoAttribute()
  {
    return ($this->fimRevisao() && $this->inicioExpedicao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->fimRevisao(), $this->inicioExpedicao()) : 0;
  }

  public function getBusinessDaysSecondsBetweenExpedicaoAttribute()
  {
    return ($this->inicioExpedicao() && $this->fimExpedicao()) ? \App\Helpers\DiasUteis::calculaIntervalo($this->inicioExpedicao(), $this->fimExpedicao()) : 0;
  }

  /** Dias úteis forHumans */

  public function businessDaysTempoExecucaoForHumans($start = Separacao::ETAPA_RECEBIMENTO, $end = Separacao::ETAPA_EXPEDICAO)
  {
    return \App\Helpers\DiasUteis::calculaHoras($this->primeiraEtapa($start), $this->ultimaEtapa($end), false);
  }

  public function getBusinessDaysTempoExecucaoForHumansAttribute()
  {
    return $this->businessDaysTempoExecucaoForHumans();
  }

  public function businessDaysTempoOciosoForHumans($start = Separacao::ETAPA_RECEBIMENTO, $end = Separacao::ETAPA_EXPEDICAO)
  {
    $tempo_ocioso = 0;
    if ($start <= Separacao::ETAPA_RECEBIMENTO && $end >= Separacao::ETAPA_SEPARACAO) {
      $tempo_ocioso += $this->getBusinessDaysSecondsBetweenRecebimentoSeparacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_SEPARACAO && $end >= Separacao::ETAPA_CATALOGACAO) {
      $tempo_ocioso += $this->getBusinessDaysSecondsBetweenSeparacaoCatalogacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_CATALOGACAO && $end >= Separacao::ETAPA_PREPARACAO) {
      $tempo_ocioso += $this->getBusinessDaysSecondsBetweenCatalogacaoPreparacaoAttribute();
    }
    if ($start <= Separacao::ETAPA_PREPARACAO && $end >= Separacao::ETAPA_BANHO) {
      $tempo_ocioso += $this->getBusinessDaysSecondsBetweenPreparacaoBanhoAttribute();
    }
    if ($start <= Separacao::ETAPA_BANHO && $end >= Separacao::ETAPA_REVISAO) {
      $tempo_ocioso += $this->getBusinessDaysSecondsBetweenBanhoRevisaoAttribute();
    }
    if ($start <= Separacao::ETAPA_REVISAO && $end >= Separacao::ETAPA_EXPEDICAO) {
      $tempo_ocioso += $this->getBusinessDaysSecondsBetweenRevisaoExpedicaoAttribute();
    }

    return \Carbon\CarbonInterval::seconds($tempo_ocioso)->cascade()->forHumans();
  }

  public function getBusinessDaysTempoOciosoForHumansAttribute()
  {
    return $this->businessDaysTempoOciosoForHumans();
  }
}

<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CatalogacaoHistorico extends Model
{
  protected $table = 'catalogacoes_historico';

  public function catalogacao()
  {
    return $this->belongsTo('App\Catalogacao', 'catalogacao_id');
  }

  public function getCarbonDataHoraEntradaAttribute()
  {
    return $this->data_inicio ? Carbon::parse($this->data_inicio) : null;
  }

  public function getCarbonDataHoraSaidaAttribute()
  {
    return $this->data_inicio ? Carbon::parse($this->data_fim) : null;
  }
}

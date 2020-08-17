<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Recebimento extends Model
{
  protected $table = 'recebimento_pecas';

  public $timestamps = false;

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'idcliente');
  }

  public function fornecedor()
  {
    return $this->belongsTo('App\Fornecedor', 'idfornec');
  }

  public function responsavel()
  {
    return $this->belongsTo('App\ResponsavelEntrega', 'idresp');
  }

  public function fotos()
  {
    return $this->hasMany('App\RecebimentoFoto', 'receb_id', 'id');
  }

  public function separacao()
  {
    return $this->belongsToMany('App\Separacao', 'separacoes_recebimentos', 'recebimento_id', 'separacao_id');
  }

  public function setPesototalAttribute($value)
  {
    isset($value) ? $this->attributes['pesototal'] = str_replace(',', '.', str_replace('.', '', $value)) : $this->attributes['pesototal'] = null;
  }

  public function getDataHoraEntradaAttribute()
  {
    if ($this->data_receb && $this->hora_receb) {
      return date('Y-m-d H:i:s', strtotime($this->data_receb . ' ' . $this->hora_receb));
    } else {
      return null;
    }
  }

  public function getCarbonDataHoraEntradaAttribute()
  {
    if ($this->data_receb && $this->hora_receb) {
      return Carbon::parse($this->data_receb . ' ' . $this->hora_receb);
    } else {
      return null;
    }
  }

}

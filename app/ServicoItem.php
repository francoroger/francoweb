<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicoItem extends Model
{
  protected $table = 'itemservico';

  public $timestamps = false;

  public function servico()
  {
    return $this->belongsTo('App\Servico', 'idservico');
  }

  public function material()
  {
    return $this->belongsTo('App\Material', 'idmaterial');
  }

  public function tipo_servico()
  {
    return $this->belongsTo('App\TipoServico', 'idtiposervico');
  }

  public function cor()
  {
    return $this->belongsTo('App\Cor', 'idcor');
  }

  public function getValorAttribute($value)
  {
    return number_format($value, 2, ',', '.');
  }

  public function setValorAttribute($value)
  {
    isset($value) ? $this->attributes['valor'] = str_replace(',', '.', str_replace('.', '', $value)) : $this->attributes['valor'] = null;
  }

  public function getPesoAttribute($value)
  {
    return number_format($value, 2, ',', '.');
  }

  public function setPesoAttribute($value)
  {
    isset($value) ? $this->attributes['peso'] = str_replace(',', '.', str_replace('.', '', $value)) : $this->attributes['peso'] = null;
  }
}

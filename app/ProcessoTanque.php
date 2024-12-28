<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessoTanque extends Model
{
  protected $table = 'tabprocesso';

  public $timestamps = false;

  public function material()
  {
    return $this->belongsTo('App\Material', 'material_id');
  }

  public function tipo_servico()
  {
    return $this->belongsTo('App\TipoServico', 'tiposervico_id');
  }

  public function cor()
  {
    return $this->belongsTo('App\Cor', 'cor_id');
  }

  public function tanque()
  {
    return $this->belongsTo('App\Tanque', 'tanque_id');
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TanqueCiclo extends Model
{
  public function reforco()
  {
    return $this->belongsTo('App\Reforco', 'tanque_id');
  }

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

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'cliente_id');
  }
}

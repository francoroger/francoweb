<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetrabalhoItem extends Model
{
  protected $table = 'retrabalhos_itens';

  public function tipo_servico()
  {
    return $this->belongsTo('App\TipoServico', 'tipo_servico_id');
  }

  public function material()
  {
    return $this->belongsTo('App\Material', 'material_id');
  }

  public function cor()
  {
    return $this->belongsTo('App\Cor', 'cor_id');
  }
}

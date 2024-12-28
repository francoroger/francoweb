<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetrabalhoItem extends Model
{
  protected $table = 'retrabalhos_itens';

  public function retrabalho()
  {
    return $this->belongsTo('App\Retrabalho', 'retrabalho_id');
  }

  public function tipo_falha()
  {
    return $this->belongsTo('App\TipoFalha', 'tipo_falha_id');
  }

  public function tipo_servico()
  {
    return $this->belongsTo('App\TipoServico', 'tiposervico_id');
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

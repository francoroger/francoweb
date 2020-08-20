<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Separacao extends Model
{
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
}

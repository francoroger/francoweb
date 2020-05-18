<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Separacao extends Model
{
  protected $table = 'separacoes';

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'cliente_id');
  }

  public function recebimentos()
  {
    return $this->belongsToMany('App\Recebimento', 'separacoes_recebimentos', 'separacao_id', 'recebimento_id');
  }
}

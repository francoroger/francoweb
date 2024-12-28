<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
  protected $table = 'ordemservico';

  public $timestamps = false;

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'idcliente');
  }
}

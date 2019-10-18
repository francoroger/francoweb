<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
  protected $table = 'servico';

  public $timestamps = false;

  public function itens()
  {
    return $this->hasMany('App\ServicoItem', 'idservico', 'id');
  }

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'idcliente');
  }

  public function transporte()
  {
    return $this->belongsTo('App\TipoTransporte', 'idtransporte');
  }

  public function guia()
  {
    return $this->belongsTo('App\Guia', 'idguia');
  }
}

<?php

namespace App;

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
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogacao extends Model
{
  protected $table = 'triagem';

  public $timestamps = false;

  public function itens()
  {
    return $this->hasMany('App\CatalogacaoItem', 'idtriagem', 'id');
  }

  public function cliente()
  {
    return $this->belongsTo('App\Cliente', 'idcliente');
  }
}

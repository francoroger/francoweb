<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogacaoServico extends Model
{
  protected $table = 'triagemservico';

  public $timestamps = false;

  public function item_catalogacao()
  {
    return $this->belongsTo('App\CatalogacaoItem', 'iditemtri');
  }
}

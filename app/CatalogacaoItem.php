<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogacaoItem extends Model
{
  protected $table = 'itemtriagem';

  public $timestamps = false;

  public function catalogacao()
  {
    return $this->belongsTo('App\Catalogacao', 'idtriagem');
  }

  public function fornecedor()
  {
    return $this->belongsTo('App\Fornecedor', 'idfornec');
  }

  public function material()
  {
    return $this->belongsTo('App\Material', 'idmaterial');
  }

  public function produto()
  {
    return $this->belongsTo('App\Produto', 'idproduto');
  }

  /**
  * Get foto - remove 'T:\' do caminho do arquivo
  *
  * @param  string  $value
  * @return string
  */
  public function getFotoAttribute($value)
  {
    return substr($value, 3);
  }
}

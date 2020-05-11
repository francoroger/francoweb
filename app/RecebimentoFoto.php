<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecebimentoFoto extends Model
{
  protected $table = 'recebimento_fotos';

  public $timestamps = false;

  public function recebimento()
  {
    return $this->belongsTo('App\Recebimento', 'receb_id');
  }

  /**
  * Get foto - trata o caminho para localizar a foto no link simbólico de fotos (public/fotos)
  *
  * @param  string  $value
  * @return string
  */
  public function getFotoAttribute($value)
  {
    //remove 'T:\' do caminho do arquivo
    $filename = substr($value, 3);
    return $filename;
  }
}

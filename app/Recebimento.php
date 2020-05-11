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

  public function fotos()
  {
    return $this->hasMany('App\RecebimentoFoto', 'receb_id', 'id');
  }

  public function setPesototalAttribute($value)
  {
    isset($value) ? $this->attributes['pesototal'] = str_replace(',', '.', str_replace('.', '', $value)) : $this->attributes['pesototal'] = null;
  }

  public function getPesototalAttribute($value)
  {
    return is_numeric( $value ) && floor( $value ) != $value ? number_format($value, 4, ',', '.') : number_format($value, 0, ',', '.');
  }
}

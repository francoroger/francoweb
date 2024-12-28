<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotacao extends Model
{
  protected $table = 'cotacoes';

  public $timestamps = false;

  public function setDataAttribute($value)
  {
    isset($value) ? $this->attributes['data'] = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $value) : $this->attributes['data'] = null;
  }

  public function getValorgAttribute($value)
  {
    return number_format($value, 2, ',', '.');
  }

  public function setValorgAttribute($value)
  {
    isset($value) ? $this->attributes['valorg'] = str_replace(',', '.', str_replace('.', '', $value)) : $this->attributes['valorg'] = null;
  }
}

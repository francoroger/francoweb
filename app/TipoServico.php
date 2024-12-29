<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoServico extends Model
{
  protected $table = 'tiposervico';

  public $timestamps = false;

  public function processos_tanque()
  {
    return $this->hasMany('App\ProcessoTanque', 'tiposervico_id');
  }

  /**
  * Get Descrição do Parâmetro
  *
  * @return string
  */
  public function getParamAttribute($value)
  {
    switch ($this->parametro) {
      case '+':
        return 'Somar';
        break;
      case '=':
        return 'Neutro';
        break;
      case '-':
        return 'Subtrair';
        break;
      default:
        return '';
    }
  }
}

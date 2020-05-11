<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
  protected $table = 'cliente';

  public $timestamps = false;

  public function getIdentificacaoAttribute($value)
  {
    $rzsc = $this->rzsc;
    $fantasia = $this->rzsc ? '(' . $this->nome . ')' : $this->nome; 
    return "{$rzsc} {$fantasia}";
  }
}

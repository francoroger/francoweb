<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tanque extends Model
{
  protected $table = 'tanque';

  public $timestamps = false;

  public function ciclos()
  {
    return $this->hasMany('App\TanqueCiclo', 'tanque_id', 'id');
  }

  public function reforcos()
  {
    return $this->hasMany('App\Reforco', 'tanque_id', 'id');
  }
}

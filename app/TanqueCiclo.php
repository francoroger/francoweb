<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TanqueCiclo extends Model
{
  public function reforco()
  {
    return $this->belongsTo('App\Reforco', 'tanque_id');
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reforco extends Model
{
  use SoftDeletes;

  public function tanque()
  {
    return $this->belongsTo('App\Tanque', 'tanque_id');
  }
}

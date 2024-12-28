<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  protected $table = 'material';

  public $timestamps = false;

  public function cores()
  {
    return $this->belongsToMany('App\Cor', 'coresmat', 'idmaterial', 'idcor');
  }

  public function cotacoes()
  {
    return $this->hasMany('App\Cotacao', 'idmaterial', 'id');
  }

  public function processos_tanque()
  {
    return $this->hasMany('App\ProcessoTanque', 'material_id', 'id');
  }
}

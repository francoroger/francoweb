<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogacaoEdicao extends Model
{
  protected $table = 'catalogacoes_edicoes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['iditemtriagem', 'idfornec', 'peso', 'referencia', 'quantidade',];

  public function fornecedor()
  {
    return $this->belongsTo('App\Fornecedor', 'idfornec');
  }
}

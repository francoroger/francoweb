<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogacaoItem extends Model
{
  protected $table = 'itemtriagem';

  public $timestamps = false;

  public function catalogacao()
  {
    return $this->belongsTo('App\Catalogacao', 'idtriagem');
  }

  public function fornecedor()
  {
    return $this->belongsTo('App\Fornecedor', 'idfornec');
  }

  public function material()
  {
    return $this->belongsTo('App\Material', 'idmaterial');
  }

  public function produto()
  {
    return $this->belongsTo('App\Produto', 'idproduto');
  }

  public function tipo_falha()
  {
    return $this->belongsTo('App\TipoFalha', 'tipo_falha_id');
  }

  public function servicos()
  {
    return $this->hasMany('App\CatalogacaoServico', 'iditemtri', 'id');
  }

  public function edicao()
  {
    return $this->hasOne('App\CatalogacaoEdicao', 'iditemtriagem', 'id');
  }

  public function getPesoRealAttribute($value)
  {
    return $this->peso * $this->quantidade;
  }

  /**
   * Get foto - trata o caminho para localizar a foto na estrutura de pastas
   * Exemplo: CATALOGAÇÃO/FOTOS CATALOGO YYYY/MM-YYYY/FILENAME.jpg
   *
   * @param  string  $value
   * @return string
   */
  public function getFotoAttribute($value)
  {
    //remove 'T:\' do caminho do arquivo
    $filename = substr($value, 3);
    //obtem o ano do arquivo
    $ano = substr($filename, 0, 4);
    //obtem o mês do arquivo, colocando 0 para corrigir o formato m
    $mes = str_pad(str_replace('_', '', substr($filename, 5, 2)), 2, '0', STR_PAD_LEFT);
    //retorna o caminho concatenando com a raiz
    return "CATALOGAÇÃO/FOTOS CATALOGO $ano/$mes-$ano/$filename";
  }

  public function getValorComDescontoAttribute($value)
  {
    return $this->desconto > 0 ? $this->preco_bruto - ($this->preco_bruto * $this->desconto / 100) : $this->preco_bruto;
  }
}

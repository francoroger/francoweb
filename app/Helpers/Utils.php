<?php

namespace App\Helpers;

use App\Log;
use Illuminate\Pagination\LengthAwarePaginator;
Use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class Utils
{
  /**
  * Gera a paginação dos itens de um array ou collection.
  *
  * @param array|Collection      $items
  * @param int   $perPage
  * @param int  $page
  * @param array $options
  *
  * @return LengthAwarePaginator
  */
  public static function paginate($items, $perPage = 15, $page = null, $options = [])
  {
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
  }

}

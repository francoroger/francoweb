<?php

namespace App;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
  /**
   * Scope a query to exclude super-admin.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeVisible($query)
  {
    return $query->where('name', '<>', 'super-admin');
  }
}

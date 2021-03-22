<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder03 extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $p = Permission::firstOrCreate(['position' => '82', 'name' => 'relatorio_tempo_execucao.index', 'group' => 'Relatórios', 'feature' => 'Tempo de Execução', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '83', 'name' => 'relatorio_checklist.index', 'group' => 'Relatórios', 'feature' => 'Relatório Check List', 'method' => 'index']);

    $role = Role::firstOrCreate(['name' => 'Administrador']);
    $role->givePermissionTo('relatorio_tempo_execucao.index');
    $role->givePermissionTo('relatorio_checklist.index');
  }
}

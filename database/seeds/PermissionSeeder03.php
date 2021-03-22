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
    $p1 = new Permission;
    $p1->position = '82';
    $p1->name = 'relatorio_tempo_execucao.index';
    $p1->group = 'Relatórios';
    $p1->feature = 'Tempo de Execução';
    $p1->method = 'index';
    $p1->save();

    $p2 = new Permission;
    $p2->position = '83';
    $p2->name = 'relatorio_checklist.index';
    $p2->group = 'Relatórios';
    $p2->feature = 'Relatório Check List';
    $p2->method = 'index';
    $p2->save();

    $role = Role::firstOrCreate(['name' => 'Administrador']);
    $role->givePermissionTo('relatorio_tempo_execucao.index');
    $role->givePermissionTo('relatorio_checklist.index');
  }
}

<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder04 extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $p = new Permission;
    $p->position = '100';
    $p->name = 'painel_acompanhamento.recebimentos';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Recebimentos';
    $p->method = 'recebimentos';
    $p->save();

    $p = new Permission;
    $p->position = '101';
    $p->name = 'painel_acompanhamento.separacoes';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Separações';
    $p->method = 'separacoes';
    $p->save();

    $p = new Permission;
    $p->position = '102';
    $p->name = 'painel_acompanhamento.catalogacoes';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Catalogações';
    $p->method = 'catalogacoes';
    $p->save();

    $p = new Permission;
    $p->position = '103';
    $p->name = 'painel_acompanhamento.preparacoes';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Preparações';
    $p->method = 'preparacoes';
    $p->save();

    $p = new Permission;
    $p->position = '104';
    $p->name = 'painel_acompanhamento.banhos';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Banhos';
    $p->method = 'banhos';
    $p->save();

    $p = new Permission;
    $p->position = '105';
    $p->name = 'painel_acompanhamento.retrabalhos';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Retrabalhos';
    $p->method = 'retrabalhos';
    $p->save();

    $p = new Permission;
    $p->position = '106';
    $p->name = 'painel_acompanhamento.revisoes';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Revisões';
    $p->method = 'revisoes';
    $p->save();

    $p = new Permission;
    $p->position = '107';
    $p->name = 'painel_acompanhamento.expedicoes';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Expedições';
    $p->method = 'expedicoes';
    $p->save();

    $p = new Permission;
    $p->position = '108';
    $p->name = 'painel_acompanhamento.enviados';
    $p->group = 'Painel de Acompanhamento';
    $p->feature = 'Enviados';
    $p->method = 'enviados';
    $p->save();
  }
}

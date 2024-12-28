<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder01 extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    // Reset cached roles and permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    // create permissions
    $p = Permission::firstOrCreate(['position' => '1', 'name' => 'Processo', 'group' => 'Menu']);

    $p = Permission::firstOrCreate(['position' => '2', 'name' => 'painel_acompanhamento.index', 'group' => 'Processo', 'feature' => 'Painel de Acompanhamento', 'method' => 'index']);

    $p = Permission::firstOrCreate(['position' => '3', 'name' => 'recebimentos.index', 'group' => 'Processo', 'feature' => 'Recebimentos', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '4', 'name' => 'recebimentos.create', 'group' => 'Processo', 'feature' => 'Recebimentos', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '5', 'name' => 'recebimentos.edit', 'group' => 'Processo', 'feature' => 'Recebimentos', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '6', 'name' => 'recebimentos.destroy', 'group' => 'Processo', 'feature' => 'Recebimentos', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '7', 'name' => 'catalogacoes.index', 'group' => 'Processo', 'feature' => 'Catalogações', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '8', 'name' => 'catalogacoes.create', 'group' => 'Processo', 'feature' => 'Catalogações', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '9', 'name' => 'catalogacoes.edit', 'group' => 'Processo', 'feature' => 'Catalogações', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '10', 'name' => 'catalogacoes.destroy', 'group' => 'Processo', 'feature' => 'Catalogações', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '11', 'name' => 'checklist.index', 'group' => 'Processo', 'feature' => 'Check List', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '12', 'name' => 'checklist.create', 'group' => 'Processo', 'feature' => 'Check List', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '13', 'name' => 'checklist.edit', 'group' => 'Processo', 'feature' => 'Check List', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '14', 'name' => 'checklist.destroy', 'group' => 'Processo', 'feature' => 'Check List', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '15', 'name' => 'ordens_servico.index', 'group' => 'Processo', 'feature' => 'Ordem de Serviço', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '16', 'name' => 'ordens_servico.create', 'group' => 'Processo', 'feature' => 'Ordem de Serviço', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '17', 'name' => 'ordens_servico.edit', 'group' => 'Processo', 'feature' => 'Ordem de Serviço', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '18', 'name' => 'ordens_servico.destroy', 'group' => 'Processo', 'feature' => 'Ordem de Serviço', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '19', 'name' => 'servicos.index', 'group' => 'Processo', 'feature' => 'Serviço', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '20', 'name' => 'servicos.create', 'group' => 'Processo', 'feature' => 'Serviço', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '21', 'name' => 'servicos.edit', 'group' => 'Processo', 'feature' => 'Serviço', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '22', 'name' => 'servicos.destroy', 'group' => 'Processo', 'feature' => 'Serviço', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '23', 'name' => 'Produção', 'group' => 'Menu']);

    $p = Permission::firstOrCreate(['position' => '24', 'name' => 'controle_reforco.index', 'group' => 'Produção', 'feature' => 'Controle de Reforço', 'method' => 'index']);

    $p = Permission::firstOrCreate(['position' => '25', 'name' => 'Cadastros', 'group' => 'Menu']);

    $p = Permission::firstOrCreate(['position' => '26', 'name' => 'clientes.index', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '27', 'name' => 'clientes.create', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '28', 'name' => 'clientes.edit', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '29', 'name' => 'clientes.destroy', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '30', 'name' => 'guias.index', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '31', 'name' => 'guias.create', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '32', 'name' => 'guias.edit', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '33', 'name' => 'guias.destroy', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '34', 'name' => 'fornecedores.index', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '35', 'name' => 'fornecedores.create', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '36', 'name' => 'fornecedores.edit', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '37', 'name' => 'fornecedores.destroy', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '38', 'name' => 'materiais.index', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '39', 'name' => 'materiais.create', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '40', 'name' => 'materiais.edit', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '41', 'name' => 'materiais.destroy', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '42', 'name' => 'tipos_servico.index', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '43', 'name' => 'tipos_servico.create', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '44', 'name' => 'tipos_servico.edit', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '45', 'name' => 'tipos_servico.destroy', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '46', 'name' => 'cores.index', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '47', 'name' => 'cores.create', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '48', 'name' => 'cores.edit', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '49', 'name' => 'cores.destroy', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '50', 'name' => 'produtos.index', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '51', 'name' => 'produtos.create', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '52', 'name' => 'produtos.edit', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '53', 'name' => 'produtos.destroy', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '54', 'name' => 'tipos_transporte.index', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '55', 'name' => 'tipos_transporte.create', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '56', 'name' => 'tipos_transporte.edit', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '57', 'name' => 'tipos_transporte.destroy', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '58', 'name' => 'tanques.index', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '59', 'name' => 'tanques.create', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '60', 'name' => 'tanques.edit', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '61', 'name' => 'tanques.destroy', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '62', 'name' => 'materias_primas.index', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '63', 'name' => 'materias_primas.create', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '64', 'name' => 'materias_primas.edit', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '65', 'name' => 'materias_primas.destroy', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '66', 'name' => 'tabelas_preco.index', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '67', 'name' => 'tabelas_preco.create', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '68', 'name' => 'tabelas_preco.edit', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '69', 'name' => 'tabelas_preco.destroy', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '70', 'name' => 'users.index', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '71', 'name' => 'users.create', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '72', 'name' => 'users.edit', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '73', 'name' => 'users.destroy', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'destroy']);

    $p = Permission::firstOrCreate(['position' => '74', 'name' => 'roles.index', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '75', 'name' => 'roles.create', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'create']);
    $p = Permission::firstOrCreate(['position' => '76', 'name' => 'roles.edit', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'edit']);
    $p = Permission::firstOrCreate(['position' => '77', 'name' => 'roles.destroy', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'destroy']);
    
    $role = Role::firstOrCreate(['name' => 'Administrador']);
    
    $role->givePermissionTo('Processo');
    $role->givePermissionTo('painel_acompanhamento.index');
    $role->givePermissionTo('recebimentos.index');
    $role->givePermissionTo('recebimentos.create');
    $role->givePermissionTo('recebimentos.edit');
    $role->givePermissionTo('recebimentos.destroy');
    $role->givePermissionTo('catalogacoes.index');
    $role->givePermissionTo('catalogacoes.create');
    $role->givePermissionTo('catalogacoes.edit');
    $role->givePermissionTo('catalogacoes.destroy');
    $role->givePermissionTo('checklist.index');
    $role->givePermissionTo('checklist.create');
    $role->givePermissionTo('checklist.edit');
    $role->givePermissionTo('checklist.destroy');
    $role->givePermissionTo('ordens_servico.index');
    $role->givePermissionTo('ordens_servico.create');
    $role->givePermissionTo('ordens_servico.edit');
    $role->givePermissionTo('ordens_servico.destroy');
    $role->givePermissionTo('servicos.index');
    $role->givePermissionTo('servicos.create');
    $role->givePermissionTo('servicos.edit');
    $role->givePermissionTo('servicos.destroy');

    $role->givePermissionTo('Produção');
    $role->givePermissionTo('controle_reforco.index');

    $role->givePermissionTo('Cadastros');
    $role->givePermissionTo('clientes.index');
    $role->givePermissionTo('clientes.create');
    $role->givePermissionTo('clientes.edit');
    $role->givePermissionTo('clientes.destroy');
    $role->givePermissionTo('guias.index');
    $role->givePermissionTo('guias.create');
    $role->givePermissionTo('guias.edit');
    $role->givePermissionTo('guias.destroy');
    $role->givePermissionTo('fornecedores.index');
    $role->givePermissionTo('fornecedores.create');
    $role->givePermissionTo('fornecedores.edit');
    $role->givePermissionTo('fornecedores.destroy');
    $role->givePermissionTo('materiais.index');
    $role->givePermissionTo('materiais.create');
    $role->givePermissionTo('materiais.edit');
    $role->givePermissionTo('materiais.destroy');
    $role->givePermissionTo('tipos_servico.index');
    $role->givePermissionTo('tipos_servico.create');
    $role->givePermissionTo('tipos_servico.edit');
    $role->givePermissionTo('tipos_servico.destroy');
    $role->givePermissionTo('cores.index');
    $role->givePermissionTo('cores.create');
    $role->givePermissionTo('cores.edit');
    $role->givePermissionTo('cores.destroy');
    $role->givePermissionTo('produtos.index');
    $role->givePermissionTo('produtos.create');
    $role->givePermissionTo('produtos.edit');
    $role->givePermissionTo('produtos.destroy');
    $role->givePermissionTo('tipos_transporte.index');
    $role->givePermissionTo('tipos_transporte.create');
    $role->givePermissionTo('tipos_transporte.edit');
    $role->givePermissionTo('tipos_transporte.destroy');
    $role->givePermissionTo('tanques.index');
    $role->givePermissionTo('tanques.create');
    $role->givePermissionTo('tanques.edit');
    $role->givePermissionTo('tanques.destroy');
    $role->givePermissionTo('materias_primas.index');
    $role->givePermissionTo('materias_primas.create');
    $role->givePermissionTo('materias_primas.edit');
    $role->givePermissionTo('materias_primas.destroy');
    $role->givePermissionTo('tabelas_preco.index');
    $role->givePermissionTo('tabelas_preco.create');
    $role->givePermissionTo('tabelas_preco.edit');
    $role->givePermissionTo('tabelas_preco.destroy');
    $role->givePermissionTo('users.index');
    $role->givePermissionTo('users.create');
    $role->givePermissionTo('users.edit');
    $role->givePermissionTo('users.destroy');
    $role->givePermissionTo('roles.index');
    $role->givePermissionTo('roles.create');
    $role->givePermissionTo('roles.edit');
    $role->givePermissionTo('roles.destroy');

    $user1 = User::where('name', 'Administrador')->get()->first();
    $user1->syncRoles([$role->id]);

    $user2 = User::where('name', 'roger')->get()->first();
    $user2->syncRoles([$role->id]);

  }
}

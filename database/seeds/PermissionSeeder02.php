<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder02 extends Seeder
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
    $p = Permission::firstOrCreate(['position' => '25', 'name' => 'relatorio_ficha_producao.index', 'group' => 'Produção', 'feature' => 'Ficha de Produção', 'method' => 'index']);
    $p = Permission::firstOrCreate(['position' => '26', 'name' => 'relatorio_producao.index', 'group' => 'Produção', 'feature' => 'Relatório de Produção', 'method' => 'index']);

    $p = Permission::firstOrCreate(['position' => '25', 'name' => 'Cadastros', 'group' => 'Menu']);
    $p->position = '27';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '26', 'name' => 'clientes.index', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'index']);
    $p->position = '28';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '27', 'name' => 'clientes.create', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'create']);
    $p->position = '29';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '28', 'name' => 'clientes.edit', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'edit']);
    $p->position = '30';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '29', 'name' => 'clientes.destroy', 'group' => 'Cadastros', 'feature' => 'Clientes', 'method' => 'destroy']);
    $p->position = '31';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '30', 'name' => 'guias.index', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'index']);
    $p->position = '32';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '31', 'name' => 'guias.create', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'create']);
    $p->position = '33';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '32', 'name' => 'guias.edit', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'edit']);
    $p->position = '34';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '33', 'name' => 'guias.destroy', 'group' => 'Cadastros', 'feature' => 'Guias', 'method' => 'destroy']);
    $p->position = '35';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '34', 'name' => 'fornecedores.index', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'index']);
    $p->position = '36';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '35', 'name' => 'fornecedores.create', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'create']);
    $p->position = '37';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '36', 'name' => 'fornecedores.edit', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'edit']);
    $p->position = '38';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '37', 'name' => 'fornecedores.destroy', 'group' => 'Cadastros', 'feature' => 'Fornecedores', 'method' => 'destroy']);
    $p->position = '39';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '38', 'name' => 'materiais.index', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'index']);
    $p->position = '40';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '39', 'name' => 'materiais.create', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'create']);
    $p->position = '41';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '40', 'name' => 'materiais.edit', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'edit']);
    $p->position = '42';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '41', 'name' => 'materiais.destroy', 'group' => 'Cadastros', 'feature' => 'Materiais', 'method' => 'destroy']);
    $p->position = '43';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '42', 'name' => 'tipos_servico.index', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'index']);
    $p->position = '44';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '43', 'name' => 'tipos_servico.create', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'create']);
    $p->position = '45';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '44', 'name' => 'tipos_servico.edit', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'edit']);
    $p->position = '46';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '45', 'name' => 'tipos_servico.destroy', 'group' => 'Cadastros', 'feature' => 'Tipos de Serviço', 'method' => 'destroy']);
    $p->position = '47';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '46', 'name' => 'cores.index', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'index']);
    $p->position = '48';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '47', 'name' => 'cores.create', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'create']);
    $p->position = '49';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '48', 'name' => 'cores.edit', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'edit']);
    $p->position = '50';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '49', 'name' => 'cores.destroy', 'group' => 'Cadastros', 'feature' => 'Cores', 'method' => 'destroy']);
    $p->position = '51';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '50', 'name' => 'produtos.index', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'index']);
    $p->position = '52';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '51', 'name' => 'produtos.create', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'create']);
    $p->position = '53';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '52', 'name' => 'produtos.edit', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'edit']);
    $p->position = '54';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '53', 'name' => 'produtos.destroy', 'group' => 'Cadastros', 'feature' => 'Produtos', 'method' => 'destroy']);
    $p->position = '55';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '54', 'name' => 'tipos_transporte.index', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'index']);
    $p->position = '56';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '55', 'name' => 'tipos_transporte.create', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'create']);
    $p->position = '57';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '56', 'name' => 'tipos_transporte.edit', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'edit']);
    $p->position = '58';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '57', 'name' => 'tipos_transporte.destroy', 'group' => 'Cadastros', 'feature' => 'Tipos de Transporte', 'method' => 'destroy']);
    $p->position = '59';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '58', 'name' => 'tanques.index', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'index']);
    $p->position = '60';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '59', 'name' => 'tanques.create', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'create']);
    $p->position = '61';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '60', 'name' => 'tanques.edit', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'edit']);
    $p->position = '62';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '61', 'name' => 'tanques.destroy', 'group' => 'Cadastros', 'feature' => 'Tanques', 'method' => 'destroy']);
    $p->position = '63';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '62', 'name' => 'materias_primas.index', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'index']);
    $p->position = '64';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '63', 'name' => 'materias_primas.create', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'create']);
    $p->position = '65';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '64', 'name' => 'materias_primas.edit', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'edit']);
    $p->position = '66';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '65', 'name' => 'materias_primas.destroy', 'group' => 'Cadastros', 'feature' => 'Matéria Prima', 'method' => 'destroy']);
    $p->position = '67';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '66', 'name' => 'tabelas_preco.index', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'index']);
    $p->position = '68';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '67', 'name' => 'tabelas_preco.create', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'create']);
    $p->position = '69';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '68', 'name' => 'tabelas_preco.edit', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'edit']);
    $p->position = '70';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '69', 'name' => 'tabelas_preco.destroy', 'group' => 'Cadastros', 'feature' => 'Tabela de Preço', 'method' => 'destroy']);
    $p->position = '71';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '70', 'name' => 'users.index', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'index']);
    $p->position = '72';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '71', 'name' => 'users.create', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'create']);
    $p->position = '73';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '72', 'name' => 'users.edit', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'edit']);
    $p->position = '74';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '73', 'name' => 'users.destroy', 'group' => 'Cadastros', 'feature' => 'Usuários', 'method' => 'destroy']);
    $p->position = '75';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '74', 'name' => 'roles.index', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'index']);
    $p->position = '76';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '75', 'name' => 'roles.create', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'create']);
    $p->position = '77';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '76', 'name' => 'roles.edit', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'edit']);
    $p->position = '78';
    $p->save();
    $p = Permission::firstOrCreate(['position' => '77', 'name' => 'roles.destroy', 'group' => 'Cadastros', 'feature' => 'Perfis de Acesso', 'method' => 'destroy']);
    $p->position = '79';
    $p->save();

    $p = Permission::firstOrCreate(['position' => '80', 'name' => 'Relatórios', 'group' => 'Menu']);
    $p = Permission::firstOrCreate(['position' => '81', 'name' => 'relatorio_servicos.index', 'group' => 'Relatórios', 'feature' => 'Relatório de Serviços', 'method' => 'index']);
    
  }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RelatorioChecklistPermissionSeeder extends Seeder
{
    public function run()
    {
        // Criar permissão se não existir
        Permission::firstOrCreate(['name' => 'relatorio_checklist.index']);
        
        // Atribuir permissão ao papel de admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo('relatorio_checklist.index');
        }
    }
}

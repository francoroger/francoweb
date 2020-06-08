<div class="site-menubar">
  <div class="site-menubar-body">
    <div>
      <div>
        <ul class="site-menu" data-plugin="menu">
          <li class="site-menu-category">Navegação</li>
          <li class="site-menu-item{{ Request::is('/') ? ' active' : '' }}">
            <a href="{{ url('/') }}">
              <i class="site-menu-icon wb-dashboard" aria-hidden="true"></i>
              <span class="site-menu-title">Dashboard</span>
            </a>
          </li>
          <li class="site-menu-category">Processo</li>
          @can('painel_acompanhamento.index')
          <li class="site-menu-item{{ Request::is('painel*') ? ' active' : '' }}">
            <a href="{{ route('painel') }}">
              <i class="site-menu-icon wb-library" aria-hidden="true"></i>
              <span class="site-menu-title">Painel</span>
            </a>
          </li>
          @endcan
          @can('recebimentos.index')
          <li class="site-menu-item{{ Request::is('recebimento*') ? ' active' : '' }}">
            <a href="{{ route('recebimentos.index') }}">
              <i class="site-menu-icon wb-inbox" aria-hidden="true"></i>
              <span class="site-menu-title">Recebimento</span>
            </a>
          </li>
          @endcan
          <li class="site-menu-item has-sub{{ Request::is('catalogacao/*') ? ' active open' : '' }}">
            <a href="javascript:void(0)">
              <i class="site-menu-icon wb-tag" aria-hidden="true"></i>
              <span class="site-menu-title">Catalogação</span>
              <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
              @can('catalogacoes.index')
              <li class="site-menu-item">
                <a href="#">
                  <span class="site-menu-title">Catalogação</span>
                </a>
              </li>
              @endcan
              @can('checklist.index')
              <li class="site-menu-item{{ Request::is('catalogacao/checklist*') ? ' active' : '' }}">
                <a href="{{ route('catalogacao_checklist.index') }}">
                  <span class="site-menu-title">Check List</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @can('ordens_servico.index')
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-order" aria-hidden="true"></i>
              <span class="site-menu-title">Ordem de Serviço</span>
            </a>
          </li>
          @endcan
          @can('servicos.index')
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-wrench" aria-hidden="true"></i>
              <span class="site-menu-title">Serviço</span>
            </a>
          </li>
          @endcan
          <li class="site-menu-category">Produção</li>
          <li class="site-menu-item has-sub{{ Request::is('producao/*') ? ' active open' : '' }}">
            <a href="javascript:void(0)">
              <i class="site-menu-icon wb-pluse" aria-hidden="true"></i>
              <span class="site-menu-title">Produção</span>
              <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
              @can('controle_reforco.index')
              <li class="site-menu-item{{ Request::is('producao/controle_reforco*') ? ' active' : '' }}">
                <a href="{{ route('controle_reforco') }}">
                  <span class="site-menu-title">Controle de Reforço</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          <li class="site-menu-category">Manutenção</li>
          <li class="site-menu-item has-sub{{ Request::is('cadastros/*') ? ' active open' : '' }}">
            <a href="javascript:void(0)">
              <i class="site-menu-icon wb-folder" aria-hidden="true"></i>
              <span class="site-menu-title">Cadastros</span>
              <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
              @can('clientes.index')
              <li class="site-menu-item{{ Request::is('cadastros/clientes*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('clientes.index') }}">
                  <span class="site-menu-title">Clientes</span>
                </a>
              </li>
              @endcan
              @can('guias.index')
              <li class="site-menu-item{{ Request::is('cadastros/guias*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('guias.index') }}">
                  <span class="site-menu-title">Guias</span>
                </a>
              </li>
              @endcan
              @can('fornecedores.index')
              <li class="site-menu-item{{ Request::is('cadastros/fornecedores*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('fornecedores.index') }}">
                  <span class="site-menu-title">Fornecedores</span>
                </a>
              </li>
              @endcan
              @can('materiais.index')
              <li class="site-menu-item{{ Request::is('cadastros/materiais*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('materiais.index') }}">
                  <span class="site-menu-title">Materiais</span>
                </a>
              </li>
              @endcan
              @can('tipos_servico.index')
              <li class="site-menu-item{{ Request::is('cadastros/tipos_servico*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('tipos_servico.index') }}">
                  <span class="site-menu-title">Tipos de Serviço</span>
                </a>
              </li>
              @endcan
              @can('cores.index')
              <li class="site-menu-item{{ Request::is('cadastros/cores*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('cores.index') }}">
                  <span class="site-menu-title">Cores</span>
                </a>
              </li>
              @endcan
              @can('produtos.index')
              <li class="site-menu-item{{ Request::is('cadastros/produtos*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('produtos.index') }}">
                  <span class="site-menu-title">Produtos</span>
                </a>
              </li>
              @endcan
              @can('tipos_transporte.index')
              <li class="site-menu-item{{ Request::is('cadastros/tipos_transporte*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('tipos_transporte.index') }}">
                  <span class="site-menu-title">Tipos de Transporte</span>
                </a>
              </li>
              @endcan
              @can('tanques.index')
              <li class="site-menu-item{{ Request::is('cadastros/tanques*') ? ' active' : '' }}">
                <a href="{{ route('tanques.index') }}">
                  <span class="site-menu-title">Tanques</span>
                </a>
              </li>
              @endcan
              @can('materias_primas.index')
              <li class="site-menu-item">
                <a href="#">
                  <span class="site-menu-title">Matéria Prima</span>
                </a>
              </li>
              @endcan
              @can('tabelas_preco.index')
              <li class="site-menu-item">
                <a href="#">
                  <span class="site-menu-title">Tabela de Preços</span>
                </a>
              </li>
              @endcan
              @can('users.index')
              <li class="site-menu-item{{ Request::is('cadastros/usuarios*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('usuarios.index') }}">
                  <span class="site-menu-title">Usuários</span>
                </a>
              </li>
              @endcan
              @can('roles.index')
              <li class="site-menu-item{{ Request::is('cadastros/roles*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('roles.index') }}">
                  <span class="site-menu-title">Perfis de Acesso</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          <li class="site-menu-category">Consultas</li>
          <li class="site-menu-item has-sub{{ Request::is('relatorios/*') ? ' active open' : '' }}">
            <a href="javascript:void(0)">
              <i class="site-menu-icon wb-file" aria-hidden="true"></i>
              <span class="site-menu-title">Relatórios</span>
              <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
              <li class="site-menu-item{{ Request::is('relatorios/servicos*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('relatorio_servicos.index') }}">
                  <span class="site-menu-title">Serviços</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('relatorios/ficha_producao*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('relatorio_ficha_producao.index') }}">
                  <span class="site-menu-title">Ficha de Produção</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('relatorios/producao*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('relatorio_producao.index') }}">
                  <span class="site-menu-title">Produção</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

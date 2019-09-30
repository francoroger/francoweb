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
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-inbox" aria-hidden="true"></i>
              <span class="site-menu-title">Recebimento</span>
            </a>
          </li>
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-tag" aria-hidden="true"></i>
              <span class="site-menu-title">Catalogação</span>
            </a>
          </li>
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-order" aria-hidden="true"></i>
              <span class="site-menu-title">Ordem de Serviço</span>
            </a>
          </li>
          <li class="site-menu-item{{ Request::is('catalogacao/checklist*') ? ' active' : '' }}">
            <a href="{{ route('catalogacao_checklist.index') }}">
              <i class="site-menu-icon fa-check-square-o" aria-hidden="true"></i>
              <span class="site-menu-title">Check List Catalogação</span>
            </a>
          </li>
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-wrench" aria-hidden="true"></i>
              <span class="site-menu-title">Serviço</span>
            </a>
          </li>
          <li class="site-menu-category">Manutenção</li>
          <li class="site-menu-item has-sub{{ Request::is('cadastros/*') ? ' active open' : '' }}">
            <a href="javascript:void(0)">
              <i class="site-menu-icon wb-folder" aria-hidden="true"></i>
              <span class="site-menu-title">Cadastros</span>
              <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
              <li class="site-menu-item{{ Request::is('cadastros/clientes*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('clientes.index') }}">
                  <span class="site-menu-title">Clientes</span>
                </a>
              </li>
              <li class="site-menu-item">
                <a class="animsition-link" href="#">
                  <span class="site-menu-title">Guias</span>
                </a>
              </li>
              <li class="site-menu-item">
                <a class="animsition-link" href="#">
                  <span class="site-menu-title">Fornecedores</span>
                </a>
              </li>
              <li class="site-menu-item">
                <a class="animsition-link" href="#">
                  <span class="site-menu-title">Materiais</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('cadastros/tipos_servico*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('tipos_servico.index') }}">
                  <span class="site-menu-title">Tipos de Serviço</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('cadastros/cores*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('cores.index') }}">
                  <span class="site-menu-title">Cores</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('cadastros/produtos*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('produtos.index') }}">
                  <span class="site-menu-title">Produtos</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('cadastros/tipos_transporte*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('tipos_transporte.index') }}">
                  <span class="site-menu-title">Tipos de Transporte</span>
                </a>
              </li>
              <li class="site-menu-item{{ Request::is('cadastros/usuarios*') ? ' active' : '' }}">
                <a class="animsition-link" href="{{ route('usuarios.index') }}">
                  <span class="site-menu-title">Usuários</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="site-menu-item">
            <a href="#">
              <i class="site-menu-icon wb-grid-9" aria-hidden="true"></i>
              <span class="site-menu-title">Tabela de Preços</span>
            </a>
          </li>
          <li class="site-menu-item has-sub">
            <a href="javascript:void(0)">
              <i class="site-menu-icon wb-settings" aria-hidden="true"></i>
              <span class="site-menu-title">Configurações</span>
              <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
              <li class="site-menu-item">
                <a class="animsition-link" href="#">
                  <span class="site-menu-title">Cotações</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

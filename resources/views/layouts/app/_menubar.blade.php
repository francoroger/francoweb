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
                  <span class="site-menu-title">Fornecedores</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">

  <div class="navbar-header">
    <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided" data-toggle="menubar">
      <span class="sr-only">Navegação</span>
      <span class="hamburger-bar"></span>
    </button>
    <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
      <i class="icon wb-more-horizontal" aria-hidden="true"></i>
    </button>
    <a class="navbar-brand navbar-brand-center" href="{{ url('/') }}">
      <img class="navbar-brand-logo" src="{{ asset('assets/images/logo.png') }}" title="{{ config('app.name', 'Laravel') }}">
      <span class="navbar-brand-text hidden-xs-down"> {{ config('app.name', 'Laravel') }}</span>
    </a>
    <!--
    <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search" data-toggle="collapse">
      <span class="sr-only">Pesquisar</span>
      <i class="icon wb-search" aria-hidden="true"></i>
    </button>
    -->
  </div>

  <div class="navbar-container container-fluid">
    <!-- Navbar Collapse -->
    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
      <!-- Navbar Toolbar -->
      <ul class="nav navbar-toolbar">
        <li class="nav-item hidden-float" id="toggleMenubar">
          <a class="nav-link" data-toggle="menubar" href="#" role="button">
            <i class="icon fa-bars">
              <span class="sr-only">Menu</span>
              <span class="hamburger-bar"></span>
            </i>
          </a>
        </li>
        <!--
        <li class="nav-item hidden-float">
          <a class="nav-link icon wb-search" data-toggle="collapse" href="#" data-target="#site-navbar-search" role="button">
            <span class="sr-only">Pesquisa</span>
          </a>
        </li>
        -->
        {{-- @include('layouts.app._mega') --}}
      </ul>
      <!-- End Navbar Toolbar -->

      <!-- Navbar Toolbar Right -->
      <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
        {{-- @include('layouts.app._notifications') --}}

        {{-- @include('layouts.app._messages') --}}

        <li class="nav-item dropdown">
          <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
            <span class="avatar">
              <img src="{{ asset('assets/portraits/0.png') }}" alt="...">
            </span>
          </a>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon wb-user" aria-hidden="true"></i> Perfil</a>
            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon wb-settings" aria-hidden="true"></i> Configurações</a>
            <div class="dropdown-divider" role="presentation"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon wb-power" aria-hidden="true"></i> Sair</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </div>
        </li>
      </ul>
      <!-- End Navbar Toolbar Right -->
    </div>
    <!-- End Navbar Collapse -->

    <!-- Site Navbar Seach -->
    <div class="collapse navbar-search-overlap" id="site-navbar-search">
      <form role="search">
        <div class="form-group">
          <div class="input-search">
            <i class="input-search-icon wb-search" aria-hidden="true"></i>
            <input type="text" class="form-control" name="site-search" placeholder="Pesquisar...">
            <button type="button" class="input-search-close icon wb-close" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
          </div>
        </div>
      </form>
    </div>
    <!-- End Site Navbar Seach -->
  </div>
</nav>

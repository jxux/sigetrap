<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
          <img src="{{asset("assets/dist/img/user2-160x160.jpg")}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name  ?? ''}}</a>
      </div>
  </div>

  <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/binnacles') }}" class="nav-link @if(request()->routeIs('binnacles')) active @endif">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Parte Diario</p>
                </a>
            </li>  
            @can('persons.index') 
                <li class="nav-item @if(request()->routeIs('persons') || request()->routeIs('users.index')) menu-open @endif">
                    <a href="#" class="nav-link @if(request()->routeIs('persons')  || request()->routeIs('users.index')) active @endif">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Administrador<i class="fas fa-angle-left right"></i>
                            {{-- <span class="badge badge-info right">6</span> --}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('persons.index', Model::class)
                            <li class="nav-item">
                                <a href="{{ url('/persons') }}" class="nav-link @if(request()->routeIs('persons')) active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Clientes</p>
                                </a>
                            </li>
                        @endcan
                        @can('users.index', Model::class)
                            <li class="nav-item">
                                <a href="{{ url('/users') }}" class="nav-link @if(request()->routeIs('users.index')) active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('accounts.index', Model::class)
                <li class="nav-item @if(request()->routeIs('accounts') || request()->routeIs('costs') || request()->routeIs('roles.index')) menu-open @endif">
                    <a href="#" class="nav-link @if(request()->routeIs('accounts') || request()->routeIs('costs') || request()->routeIs('roles.index')) active @endif">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Configuración<i class="fas fa-angle-left right"></i>
                            {{-- <span class="badge badge-info right">6</span> --}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('accounts.index', Model::class)
                        <li class="nav-item">
                            <a href="{{ url('/accounts') }}" class="nav-link @if(request()->routeIs('accounts')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cuentas</p>
                            </a>
                        </li>
                        @endcan
                        @can('costs.index', Model::class)
                        <li class="nav-item">
                            <a href="{{ url('/costs') }}" class="nav-link @if(request()->routeIs('costs')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Centro de costos</p>
                            </a>
                        </li>
                        @endcan
                        @can('roles.index', Model::class)
                        <li class="nav-item">
                            <a href="{{ url('/roles') }}" class="nav-link @if(request()->routeIs('roles.index')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>{{ __('Salir') }}</p>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>
            </li>

        </ul>
    </nav>
  <!-- /.sidebar-menu -->
</div>

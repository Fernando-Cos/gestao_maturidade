<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item col-lg-12">
                    <a class="nav-link active" aria-current="page" href="{{route('homeavaliador')}}"><i class="fas fa-house-user"></i> </a>
                </li>
                <li class="nav-item col-lg-12">
                    <a class="nav-link" href="#"></a>
                </li>
                <li class="nav-item col-lg-12">
                    <a class="nav-link" href="#"></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <h7>{{ strtoupper(Auth::user()->name) }}</h7>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Alterar Senha</a></li>
                        <li><a class="dropdown-item" href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">Sair
                            </a>
                        </li>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


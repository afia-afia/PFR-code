<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Monitoring</title>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css"> -->
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-md bg-primary navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa d-inline fa-lg fa-line-chart"></i> <b>Monitoring</b>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbar2SupportedContent" aria-controls="navbar2SupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse text-center" id="navbar2SupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("dashboard") }}">Dashboard</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="{{ action('OrganizationController@index') }}"
                           id="navbarDropdownTools" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa d-inline fa-lg fa-bookmark-o"></i>&nbsp;My groupes
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdownOrganizations">
                            @foreach (Auth::user()->organizations as $organization)
                            <a class="dropdown-item"
                               href="{{ action("OrganizationController@show", ["organization" => $organization]) }}">
                                {{ $organization->name }}
                            </a>
                            @endforeach
                        </div>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>

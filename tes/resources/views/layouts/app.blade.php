<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('images/lspr.png') }}" rel="icon">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/global.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600&display=swap" rel="stylesheet">

  @yield('style')
</head>

<body>
  <div id="app">
    @auth
    <nav class="navbar navbar-expand-md navbar-light fixed-top mt-0 bg-light custom-navbar shadow-lg">
      <a class="navbar-brand">
        <img class="logo-1" src="{{url('/images/logo.png')}}" alt="">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">

          @can('member-area')
          <li class="nav-item{{ request()->is('member/page/info') ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('member.index') }}">Home</a>
          </li>
          @endcan

          @can('academic-area')
          <li class="nav-item{{ request()->is('academic/users') ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('academic.users.index') }}">Data Mahasiswa</a>
          </li>
          @endcan

          @can('admin-area')
          <li class="nav-item{{ request()->is('admin/users') ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">Data Mahasiswa</a>
          </li>
          <li class="nav-item{{ request()->is('admin/major') ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('admin.major.index') }}">Major</a>
          </li>
          <li class="nav-item{{ request()->is('admin/categories') ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">Soal</a>
          </li>
          <li class="nav-item{{ request()->is('admin/users-management') ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.management') }}">Management User</a>
          </li>
          @endcan
          @if (Auth::check() && Auth::user()->email_verified_at)
          <li class="nav-item{{ request()->is('password.edit') ||  request()->is('account/password') ||  request()->is('member/page/q-n-a')  ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('password.edit') }}">Change Password</a>
          </li>
          @endif
          @guest
          <li class="nav-item ml-5">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          @else
          <li class="nav-item dropdown custom-menu-right ml-5">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
          @endguest
        </ul>
      </div>
    </nav>
    @endauth
    <div id="app-layout">
      @if (Auth::check() && !Auth::user()->email_verified_at)
      @if (request()->is('email/verify'))
      @else
      @endif
      @endif
      @include('alert')
      @yield('content')
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

  @yield('scriptBottom')
</body>

</html>
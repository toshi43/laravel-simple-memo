<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="/js/confirm.js"></script>
    @yield('javascript')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <link href="/css/layout.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class>
            <div class="row">
                <div class="co-sm-12 col-md-5 p-0">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            フォルダ一覧
                            <a href="{{ route('folders/formcreate') }}" class="btn-block"><i class="fas fa-folder"></i></a>
                        </div>
                        <div class="card-body my-card-body">
                                <a href="/" class = "card-text d-block mb-3">全て表示</a>
                            @foreach($folders as $folder)
                            <div class="fokder-list d-flex justify-content-between" method="POST">
                                <a href="/?folder_id={{$folder['id']}}" class="list-group-item elipsis mb-3">{{ $folder['title'] }}</a>
                                <a href="/folders/{{$folder['id']}}/edit"><i class="fas fa-edit"></i></a>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>                
                <div class="col-md-5 p-0">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            フォルダ編集
                            <form id="delete-form" action="{{ route('folderdestroy', ['folder_id'=> $edit_folder->id]) }}" method="POST">
                                @csrf
                                <input type="hidden"  name="folder_id" value="{{ $edit_folder['id'] }}" /> 
                                <i class="fas fa-trash mr-3" onclick="deleteHandle(event);"></i>
                            </form>
                        </div>
                        <form class="card-body  my-card-body" action="{{ route('folderupdate', ['folder_id' => $edit_folder->id]) }}" method="POST">
                        @csrf
                            <label for="title">フォルダ名</label>
                            <input type="text" class="form-control mt-2 mb-3" name="title" id="title" value="{{ old('title') ?? $edit_folder['title'] }}" />
                            <button type="submit" class="btn btn-primary">更新</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Bolão da firma') }}</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">

	<!-- <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script> -->
	<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

	<!-- Scripts -->
	@vite(['resources/sass/app.scss', 'resources/js/app.js'])
 
</head>
<body>
	<div id="app">
		<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
			<div class="container">
				{{-- Logo do aplicativo --}}
				<div class="col-3">
					<img src="{{ asset('img/trevo.jpeg') }}" alt="Logo" width="30" height="30">
				</div>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<!-- Right Side Of Navbar -->
					<ul class="navbar-nav ms-auto">

						<li class="nav-item">
							<a class="nav-link" href="{{ url('/') }}">
								{!! (Route::is('welcome')?"<span class='h5'>":"") !!}
									Benvindo
								{!! (Route::is('welcome')?"</span>":"") !!}
							</a>
						</li>
							
						<!-- Atalhos para usuãrios não logados -->
						@guest
							<li class="nav-item">
								<a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ route('register') }}">{{ __('Cadastrar') }}</a>
							</li>
						<!-- Atalhos para usuários logados -->
						@else
							<li class="nav-item">
								<a class="nav-link" href="{{ url('home') }}">
									{!! (Route::is('home')?"<span class='h5'>":"") !!}
										Apostas
									{!! (Route::is('home')?"</span>":"") !!}
								</a>
							</li>
							@can('admin')
							<li class="nav-item">
								<a class="nav-link" href="{{ url('tasks') }}">
									{!! (Route::is('tasks')?"<span class='h5'>":"") !!}
										Tarefas
									{!! (Route::is('tasks')?"</span>":"") !!}
								</a>
							</li>
							@endcan
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

		<main class="py-4">
			@yield('content')
		</main>
	</div>
</body>
</html>

@hassection('script-commands')
	@yield('script-commands')
@endif
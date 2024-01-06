@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="card bg-light">
			<div class="card-header">
				<span class="h4">Total de palpites do grupo: {{$total}}</span>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<form action="/option/store_file" method="post" enctype="multipart/form-data">
							@csrf
							<div class="row">
								<div class="col">
									<label for="filenames">Recibos</label>
									<input hidden name="user_id" value="{{Auth::user()->id}}">
									<input 	type="file"
											class="form-control" 
											name="filenames[]" 
											required
											multiple accept=".png,.jpg" />
								</div>
								<div class="col mt-4">
									<button type="submit" class="btn" value="Upload">
										<i class="bi bi-save">&nbsp Salvar</i>
									</button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-4 mt-4">
						@if ( count($images) != 0 )
							<button class="btn photo-booth"  data-bs-toggle="modal" data-bs-target="#boothModal">
								<i class="bi-images">
									Exibir meus recibos
								</i>
							</button>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{-- Resultado das solicitações --}}
		@if ( $success = Session::get('success') )
			<x-alert-success>
				<strong>{{ $success }}</strong>
			</x-alert-success>
		@endif

		<!-- Lista de apostas por usuário -->
		<div class="card mt-2 bg-light">
			<div class="card-body">
				@foreach ( $options as $o )
					<ol class="list-group ">

						<!-- Identificação do usuário -->
						<li class="list-group-item bg-primary-subtle mt-2">
							<span class="h4">{{ $o['name'] }}: </span>
							<span>{{$o['parcial']}} palpites / {{$o['percent']}}% do total</span>
						</li>

						<!-- Exibe formulário de apostas para usuário logado -->
						@if ( $o['user_id'] == Auth::user()->id )
						<li class="list-group-item">
							<div class="row">
								<div class="col-8">
									<form id="save-form" action="/option/store" method="post">
										@csrf
										<input	id="option" 
											type="tel" 
											class="form-control" 
											name="guesses" 
											value=""
											placeholder="Escolha suas dezenas">
										<input hidden name="user_id" value="{{Auth::user()->id}}">
									</form>
								</div>
								<div id="div-btn-save" class="col-4" hidden="hidden">
									<button id="btn-save" type="submit" class="btn" form-id="save-form">
										<i class="bi bi-save"> Salvar</i>
									</button>
								</div>

								<div id="div-btn-sort" class="col-4">
									<button id="btn-sort" class="btn">
										<i class="bi bi-dice-5"> Complete</i>
									</button>
								</div>
								<div class="col-12">
									<span id="message" class=""></span>
								</div>
							</div>
						</li>
						@endif
				
						@foreach ( $o['guesses'] as $guess )
							<li class="list-group-item {{ $guess->checked ? 'bg-success-subtle' : ''}}">
								<div class="row">
									<div class="col-10">
										@foreach ( $guess['data'] as $d )
											{{ sprintf('%02d',$d) }}
										@endforeach
									</div>

									<!-- Só exibe opção de apagar para usuário dono da aposta -->
									<div class="col-1">
										@if ( $o['user_id'] == Auth::user()->id && $guess->checked == false )
											<form action="/option/destroy" method="post">
												@csrf
												<button type="submit" class="btn">
													<i class="bi bi-trash"></i>
												</button>
												<input hidden name="id" value="{{$guess->id}}">
											</form>
										@elseif ( $guess->checked == true )
											<i class="bi bi-check-lg"></i>
										@endif
									</div>

									<!-- Só exibe opção de marcar aposta para admin -->
									@can('admin')
									<div class="col-1">
										@if ( $guess->checked == false )
											<form action="/option/check" method="post">
												@csrf
												<button type="submit" class="btn">
													<i class="bi bi-square"></i>
												</button>
												<input hidden name="id" value="{{$guess->id}}">
											</form>
										@endif
									</div>
									@endcan

							</li>
						@endforeach
					</ol>
				@endforeach	
			</div>
		</div>
	</div> 
</div>

@php 
	//echo '<pre>' ;
	//print_r($rows);
	//echo '</pre>' ;
@endphp

{{-- Modal com o carrossel de imagens --}}
<x-modal.photo-booth>
	@foreach ( $images as $i )
		<div class="carousel-item active">
			<img class='d-block w-5' src="{{ URL::to('/')}}/recibos/{{$i->name}}">
		</div>
	@endforeach
</x-modal.photo-booth>

@endsection

@section('script-commands')
<script type="text/javascript">

	$(document).ready(function(){
	
		// Limpa campo ao carregar página
		$('#option').val("");

		setTimeout(function() {
     		$('.alert-temp').hide('slow');
    	},5000);
	});

	// faz o sorteio das dezenas faltantes
	$('#btn-sort').on('click',function() {

		// Verifica quantas dezenas faltam
		let list = formatInput();
		let count = list.length == 0 ? 6 : ( 6 - list.length ) ;
		console.log("Total de dezenas: " + list.length + ", faltam " + count + " dezenas") ;

		// Executa o sorteio das dezenas faltantes
		let limit = 20 ;
		while ( count && limit-- ) {
			// Sorteia um número entre 1 e 60
			let randomDecimal = Math.random();
			let randomNumber = Math.floor(randomDecimal * 60) + 1;

			if ( list.includes(randomNumber) ) {
				console.log(randomNumber + " já está na lista");
			}
			else {
				// Inclui número gerado na lista
				console.log("nova dezena: "+ randomNumber);
				list.push(randomNumber);
				count-- ;
			}
		}

		let text = list.toString() ;
		console.log("Sorteio: " + text);
		const myInput = document.getElementById("option");
		myInput.value = text ;
		myInput.dispatchEvent(new Event("input"));
	});

	// 5.44.43.2.21

	// Formata entrada apagando caracters não numéricos ou espaços 
	function formatInput(){

		// Carrega texto com palpites
		let input = $('#option').val();
		console.log(input + " - entrada");

		// Substitui caracteres diferentes de números e espaços por espaços
		let text = input.replace(/[^0-9]+/g," ");
		console.log(text + ' - só números e espaços');

		text = text.replace(/^[^\d]+/,"");
		console.log(text + ' - texto final');

		if ( text != input ) {
			console.log('texto alterado!');
			$('#option').val(text);
		}

		let temp = text.split(" ");
		let list = [] ;
		temp.forEach(function(val) {
			let x = Number(val);
			if ( x > 0 ) {
				list.push(x);
			}
		});

		return list ;
	}

	// Executa cada vez que uma tecla for pressionada 
	$('#option').on('input',function(e) {
	//$('#option').on('change',function() {
		processInput();
	});

	// Executa cada vez que uma tecla for pressionada 
	function processInput() {

		// Inicia variáveis
		let messages = [] ;

		// Alteração no campo de digitação de apostas 
		let list = formatInput();
		let guesses = new Set(list);

		// Verifica se são 6 dezenas
		console.log("total de dezenas = "+ list.length);
		if ( list.length != 6 ) {
			messages.push("Escolha 6 dezenas!");
			$("#div-btn-sort").attr("hidden",false);
		}
		else {
			$("#div-btn-sort").attr("hidden",true);
		}

		// Verifica se existe palpite maior que 60
		guesses.forEach(function(val){
			if ( val > 60 ) {
				messages.push(val + " maior que 60, aposta incorreta") ;
			}
		});

		// Verifica se existem dezenas repetidas
		if ( guesses.size !== list.length ) {
			messages.push("Dezenas repetidas") ;
		}

		// Indica primeira mensagem escrita
		let first = true ;

		// Limpa área de mensagens
		$('#message').empty();
		$.each(messages,function(index,value){

			if ( first == false ) {
				$('#message').append($('<div class="vr"></div>'));
			}
			$('#message').append($('<span>',{
				text: value,
				class: "badge rounded-pill text-bg-secondary m-2"
			}));
			first = false ;

		});

		// Exibe botão de salvar somente quando não há mensagens de erro
		let show = messages.length == 0 ? false : true ;
		$("#div-btn-save").attr("hidden",show);

	}

	// Busca apostas no dB e acisa no caso de repetição
	function findOption(guesses) {
		
		let apostas = Array.from(guesses);
		console.log(apostas);

		$.getJSON('/api/options/find', {
				"guesses" : apostas,
			})
			.done(function(data){
				console.log('sucesso');
				console.log(data);
				if ( data['total'] > 0 ) {

					let text = "Já existem " + data['total'] + " aposta(s) igual(s) a esta!";
					$('#message').append($('<span>',{
						text: text,
						class: "badge rounded-pill text-bg-danger m-2"
					}));
				}
			})
			.fail(function(data){
				console.log('Falha');
				console.log(data);
			}
		);
	}

</script>
@endsection

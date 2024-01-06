@extends('layouts.app')

@section('content')
<div class="card m-5">
	<div class="card-body">
		<h5 class="card-title">Regras</h5>
		<ul>
			<li>As são feitas junto a caixa pelo administrador</li>
			<li>O jogo é dividivo em cotas</li>
			<li>Cada cota custa o preço de uma aposta nas casas lotéricas</li>
			<li>O preço de uma aposta é de R$5,00 em dezembro de 2023</li>
			<li>Cada participante compra quantas cotas quiser</li>
			<li>Cada cota da direito a um palpite de 6 dezenas</li>
			<li>Qualquer eventual premio será dividido pelo número de cotas</li>
			<li>A entrada no bolão só será considerada após o pagamento</li>
			<li>O valor do prémio será calculado da seguinte maneira: ( Valor do premio ) * ( cotas adquiridas ) / ( total de cotas )</li>
			<li>As apostas podem ser apagadas até a efetivação somente pelo autor</li>
			<li>A efetivação das apostas é feita pelo administrador</li>

		</ul>
	</div>
</div>
@endsection
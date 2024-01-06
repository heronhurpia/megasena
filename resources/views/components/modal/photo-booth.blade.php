{{-- Modal com o carrossel de imagens --}}
<div class="row d-flex justify-content-center">
	<div class="modal modal-md fade" id="boothModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<div class="card card-boby">
						<div id="boothCarroussel" class="carousel slide carousel-fade">
							<div id="carousel-images" class="carousel-images">
								{{$slot}}
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#boothCarroussel" data-bs-slide="prev">
								<span class="carousel-control-prev-icon bg-info" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#boothCarroussel" data-bs-slide="next">
								<span class="carousel-control-next-icon bg-info" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
				</div>

			</div>
		</div>
	</div>
</div>

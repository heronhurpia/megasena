@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="card">
			<div class="card-header">
				<span class="h4">Tarefas</span>
			</div>

			<div class="card-body">

				<form action="/tasks/store" method="POST" id="task-form">
					@csrf
					<div class="row">
						<div class="col-6">
							<input class="form-control" type="text" name="description" placeholder="Nova tarefa" required>
						</div>
						<div class="col-2">
							<button type="submit" class="btn edit-item"><i class="bi bi-save"></i></button>
						</div>
					</div>
				</form>

				<ul class="list-group">
					@foreach ($tasks as $task)
						<li class="list-group-item">
							<div class="row pt-3">
								<div class="col-1">
									<form action="/tasks/check" method="post">
										@csrf
										<button type="submit" class="btn">
											<i class="bi {{$task->status?'bi-check-square':'bi-square'}}"></i>
										</button>
										<input name="id" value="{{$task->id}}" hidden>
										<input name="status" value="{{$task->status}}" hidden>
									</form>
								</div>

								<div class="col-11">							
									<input name="id" value="{{$task->id}}" hidden>
									<span>{{ $task->updated_at->format('d/m/Y H:i') }} - </span>
									<span>{{ $task->description }}</span>
								</div>
							</div>
						</li>
					@endforeach
				</ul>
			</div>
			<span id="body"></span>
		</div>
	</div>
</div>
@endsection

<script type="text/javascript"> 

</script>

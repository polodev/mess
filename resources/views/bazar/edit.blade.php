@extends('layouts.app')
@section('content')
<h2 class="my-2">Edit a bazar</h2>
@include('partials.errors')
@include('partials.alert')
<form action='{{ route('bazar.update', ['bazar' => $bazar->id]) }}' method="post">

	@csrf
	@method('PUT')
	<div class='form-group'>
		<label for="date">Date</label>
		<input placeholder="Bazar Date" type="text" name="date" class="form-control" id="date">
	</div>
	<!-- /.form-group -->
	@if(false)
		<div class='form-group'>
			<label for="user_id">User</label>
			<select class="form-control" name="user_id" id="user_id">
				@foreach ($users as $user)
					<option
						{{old('user_id') == $user->id ? 'selected' : ''}}
						value="{{$user->id}}">{{ $user->display_name ? $user->display_name : $user->name }}</option>
				@endforeach
			</select>
		</div>
	@endif
	<?php

	$types = [
		'regular',
		'common',
		'others',
	];


	 ?>
	<div class='form-group'>
		<label for="type">Type of cost</label>
		<select class="form-control" name="type" id="type">
			@foreach ($types as $type)
				<option
					{{old('type', $bazar->type) == $type ? 'selected' : ''}}
					value="{{ $type }}">{{ ucfirst( $type ) }}</option>
			@endforeach
		</select>
	</div>

	<div class='form-group'>
		<label for="cost">Bazar Cost</label>
		<input value="{{ old('cost', $bazar->cost) }}" type='number' name='cost' id='cost' class="form-control" />
	</div>

	<div class='form-group'>
		<label for="more_info">More Information of Bazar (optional)</label>
		<textarea name="more_info" class="form-control" id="more_info">{{ old('more_info', $bazar->more_info) }}</textarea>
	</div>

	<div class='form-group'>
		<button class="btn btn-info" type="submit">Add</button>
	</div>
	
</form>

<?php

$db_date = $bazar->date->isoFormat('d-m-Y');

?>
@endsection

@push('script')
<script>
$("#date").flatpickr({
  dateFormat: "d-m-Y",
  maxDate: 'today',
 <?php if (old('date', $db_date)): ?>
  defaultDate: '{{ old('date', $db_date) }}'
<?php endif; ?>
});
</script>
@endpush
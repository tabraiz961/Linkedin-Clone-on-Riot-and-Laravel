@php($title = "Supprimer une offre d'emploi")
@extends('layouts.app', ['profile_sidebar' => true])

@section('content')
	<div class="callout card">
		<div class="card-divider">
			Delete a job posting
		</div>
		<div class="card-section">
			<p>Are you sure you want to <b>delete</b> the offer for<b>{{ $job->position }}</b> at the house of <b>{{ $job->entity->name }}</b> ?</p>
			<div class="text-center">
				{!! Form::open(['route' => [ 'job.delete', $job->job_id ] ]) !!}
					{{ method_field( 'DELETE' ) }}
					<a class="button secondary" href="{{ URL::previous() }}">Non</a>
					<button class="button alert" type="submit">Oui</button>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection
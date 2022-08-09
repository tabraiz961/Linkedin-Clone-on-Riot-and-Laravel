@php($title = 'Supprimer une entreprise/école')
@extends('layouts.app', ['profile_sidebar' => true])

@section('content')
	<div class="callout card">
		<div class="card-divider">
			Delete a business/school
		</div>
		<div class="card-section">
			<p>Are you sure you want to <b>delete</b> the company/school <b>{{ $entity->name }}</b> ?</p>
			<p>All associated job postings will be deleted.</p>
			<div class="text-center">
				{!! Form::open(['route' => [ 'entity.delete', $entity->entity_id ] ]) !!}
					{{ method_field( 'DELETE' ) }}
					<a class="button secondary" href="{{ URL::previous() }}">Non</a>
					<button class="button alert" type="submit">Yes</button>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection
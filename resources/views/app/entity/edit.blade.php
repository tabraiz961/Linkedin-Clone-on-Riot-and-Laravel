@php($title = 'Modifier une entreprises/école')
@extends('layouts.app', ['profile_sidebar' => true])

@section('content')
	<div class="callout card">
		<div class="card-divider">
			Modify a company/school
		</div>
		<div class="card-section">
			@include( 'app.inc.forms.entity', [ 'entity' => $entity ] )
		</div>
	</div>
@endsection
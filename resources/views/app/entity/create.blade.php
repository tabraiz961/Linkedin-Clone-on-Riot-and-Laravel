@php($title = 'Create a business/school')
@extends('layouts.app', ['profile_sidebar' => true])

@section('content')
	<div class="callout card">
		<div class="card-divider">
			Create a business/school
		</div>
		<div class="card-section">
			@include( 'app.inc.forms.entity' )
		</div>
	</div>
@endsection
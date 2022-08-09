@extends('layouts.app')
 
@section('content')

	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<job-application-select></job-application-select>

		</div>
	</div>
@endsection

@section('styles')
	@parent
		{{ Html::style( 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' ) }}
@endsection

@section('scripts')
	@parent
	//
	{{ Html::script( 'https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js' ) }}
	@include( 'app.inc.jobs.listings' )
	{{-- @include( 'app.inc.jobs.loaders.loaders' ) --}}
	
	<script>
		jobs = JSON.parse('{!!  json_encode($data2)  !!}');
		console.log(jobs);
		window.riot.mount( 'job-application-select', {
			basepath: '{{route('index')}}',
			jobsListing: jobs,
			getJobDetailsLink: '{{route('job.applications.users')}}'
		} );
	</script>

@endsection
@extends('layouts.app')
 
@section('content')

	<div id="home" class="grid-container">
		<course-list></course-list>
		
	</div> 
@endsection

@section('styles')
	@parent
		{{-- {{ Html::style( 'https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css' ) }}
		{{ Html::style( 'https://unpkg.com/filepond@^4/dist/filepond.css' ) }} --}}
@endsection

@section('scripts')
	@parent
		{{-- {{ Html::script( 'https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js' ) }}
		{{ Html::script( 'https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js' ) }}
	 	{{ Html::script( 'https://unpkg.com/filepond@^4/dist/filepond.js' ) }} --}}
		 
		 @include( 'app.inc.courses.tags') 
		 {{-- @include( 'app.inc.jobs.loaders.loaders' ) --}}
		 @php
			$statement = DB::select("show table status like 'courses'");
			$courseId = $statement[0]->Auto_increment;
		@endphp
	<script src="/js/libraries/jquery.barrating.js"></script>
	<script>
		
		window.riot.mount( 'course-list', {
			basepath: '{{route('index')}}',
			all_course_url: '{{route('courses.all.get')}}',
			current_user_id : '{{Auth::user() ? Auth::user()->user_id : null}}',
			interest_toggle_url : '{{route('api.course.interest.toggle')}}',
			course_create_url: '{{route('course.create', ['id' => $courseId])}}',
			rating_update_url: '{{route('api.course.submit_rating')}}'
		} );
	</script>

@endsection
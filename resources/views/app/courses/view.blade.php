@extends('layouts.app')
 
@section('content')

	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<div class="cell medium-8 large-8 hide-for-small-only">
				<course-view></course-view>
			</div> 
		</div>
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
	 	{{-- {{ Html::script( 'https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js' ) }} --}}
	@include( 'app.inc.courses.tags') 
	{{-- @include( 'app.inc.jobs.loaders.loaders' ) --}}
	
	<script>
		
		let authenticated = {{ Auth::check() ? 'true' : 'false' }};
		window.riot.mount( 'course-view', {
			basepath: '{{route('index')}}',
			slug: '{{$slug}}',
			getCourseUrl: '{{route('courses.course.get',['slug'=> $slug] )}}',
			courseStoragePath: window.origin+ '/storage/courses',
			authenticated: authenticated, 
		} );
	</script>

@endsection
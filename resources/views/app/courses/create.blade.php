@extends('layouts.app')
 
@section('content')

	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<div class="cell medium-8 large-8 hide-for-small-only">
				<div class="cell callout border-rad-10">
					<course-create></course-create>
				</div>
			</div> 
		</div>
	</div>
@endsection

@section('styles')
	@parent
		{{ Html::style( 'https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css' ) }}
		{{ Html::style( 'https://unpkg.com/filepond@^4/dist/filepond.css' ) }}
@endsection

@section('scripts')
	@parent
		{{ Html::script( 'https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js' ) }}
		{{ Html::script( 'https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js' ) }}
	 	{{ Html::script( 'https://unpkg.com/filepond@^4/dist/filepond.js' ) }}
	 	{{ Html::script( 'https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js' ) }}
	@include( 'app.inc.courses.tags' ) 
	{{-- @include( 'app.inc.jobs.loaders.loaders' ) --}}
	
	<script>
		// console.log(jobs);
		window.riot.mount( 'course-create', {
			basepath: '{{route('index')}}',
			course_id: '{{$data2['course_id']}}',
			formSubmitLink: '{{route('course.submit', ['courseId'=> $data2['course_id']])}}',
			formFileAsyncPath: '{{route('course.files.async')}}',
			courseBlockGenUrl: '{{route('course.block.autogen')}}',
			courseFilesconfirm: '{{route('course.files.final')}}',
			courseBlockDelUrl: '{{route('course.block.delete')}}',
			courseFileDelUrl: '{{route('course.file.delete')}}',
			courseBlocks: '{{route('course.blocks.public', ['courseId'=> $data2['course_id']])}}',
			mycourse_url: '{{route('courses.mycourse.list')}}',
			courseStoragePath: window.origin+ '/storage/courses',
			csrfToken: '{{csrf_token() }}',
		} );
	</script>

@endsection
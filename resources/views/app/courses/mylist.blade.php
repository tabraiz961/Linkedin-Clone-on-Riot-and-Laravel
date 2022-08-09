@extends('layouts.app')
 
@section('content')
	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<div class="cell medium-8 large-8 hide-for-small-only">
				<div> 
					<h4>
						<strong>My Courses</strong>
					</h4>
				</div>
				{{-- <div class="searchContainer flex-container">
					<div class="input">
						<input type="text" class="border-left-rad-10" onchange="getCourses">
					</div>
					<div class="button border-right-rad-10">
						<button >
							<i class="fas fa-search"></i>
						</button>
					</div>
				</div> --}}
				<div class="cell callout border-rad-10">
					<div class="action_tools">
						<div class="searchbar_course"></div>
					</div>
					<div class="my_course_list">
						@foreach ($courses as $course) 
							<div class="flex-container courses-item" style="justify-items: flex-start;">
								<div class="course_poster">
									<img class="border-rad-10" src="{{ (isset($course['course']['course_image']) ? '/storage/courses/'.$course['course']['id'].'/course-poster/'. $course['course']['course_image'] : '') }}" alt="">
								</div>
								<div class="course_info flex-dir-column">
									<div class="title"><strong>{{isset($course['course']['course_title']) ? $course['course']['course_title'] : ''}}</strong></div>
									@php
									$reviewCount = 0; 
									$goldStars = [];
									$greyStars = [0,1,2,3,4];
									$average = 0;
									if (isset($course['course']['ratings']) && count($course['course']['ratings'])) {
										foreach ($course['course']['ratings'] as $value ) {
											if(isset($value['points'])){
												$reviewCount++;
												$goldStars[] = $value['points'];
											}
										}
										$average = ceil(array_sum($goldStars)/count($goldStars));
										array_splice($greyStars, 0, $average );
									}
									@endphp
									<div class="courses_publish_status">
										<div class="flex-container" style="margin-right: 50px;">
											<span class="gold">({{ $reviewCount }})</span>
											<div class="icon">
												@php
												for ($i=0; $i < $average; $i++) { 
													echo '<i style="color: gold;" class="fas fa-star"></i>';
												}
												@endphp
												
												@php
													foreach ($greyStars as $value2) {
														echo '<i style="color: #C6C5C2;" class="fas fa-star"></i>';
													}
												@endphp
											</div>
										</div>
									</div>
									<div class="course_views">
										{{ $course['views']. ' Views'}}
									</div>	
									<div class="course_status tags_container">
										<div class=" text_beforeicon_tags cursorhoverDefault">
											{{$course['course']['status'] ? 'Active': 'Inactive'}}
										</div>
									</div>
								</div>
								<div class="course_actions space-between flex-dir-column align-center">
									<div class="space-between actions_div">
										<div>
											<i class="fas fa-copy clickable" style="color: gray;" onclick="copyCourseLink('{{route('course.create', ['id' => $course['course']['id'] ])}}')"></i>
										</div>
										<div>
											<a href="{{route('course.create', ['id' => $course['course']['id'] ])}}">
												<i style="color: gray;" class="clickable fas fa-edit"></i>
											</a> 
										</div>
									</div>
									<div><a href="/{{'course/'.$course['course']['slug']}}" class="button border-rad-10 m-0">View</a></div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
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
	 	{{ Html::script( 'https://unpkg.com/filepond@^4/dist/filepond.js' ) }}
	 	{{ Html::script( 'https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js' ) }} --}}
	@include( 'app.inc.courses.tags' ) 
	{{-- @include( 'app.inc.jobs.loaders.loaders' ) --}}
	
	<script>
		function copyCourseLink(url) {
			navigator.clipboard.writeText(url);
			$.notify("Copied to Clipboard", {position:'bottom-right', className: 'success'});
		}
	</script>

@endsection
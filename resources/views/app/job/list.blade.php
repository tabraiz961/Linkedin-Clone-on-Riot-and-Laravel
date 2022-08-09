@extends('layouts.app')
 
@section('content')
	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<div class="cell medium-5 large-5 hide-for-small-only">
				<job-listings ></job-listings>	
				<div class="pagination-job">
					
				</div>
			</div>
			<div class="cell medium-7 large-7" id="timeline">
				<job-selected></job-selected>
			</div>

		</div>
	</div>
@endsection

@section('styles')
	@parent
	
	{{-- {{ Html::style( 'css/job-list.css' ) }} --}}
@endsection

@section('scripts')
	@parent
	@include( 'app.inc.jobs.listings' )
	@include( 'app.inc.jobs.loaders.loaders' )

	<script>
		this.loadingItem = true;
		let jobSelectedTag = null;
		let listingTag = null;
		let that = this;
		let responseData = null;
		let customPaginate = false;
		
		function reload(){
			that.loadUserJobs(null, that.listingTag, '')
		}
		function loadUserJobs(empty, tag, url=''){
			tag.loading = true;
			tag.update();
			if(!that.listingTag){
				that.listingTag = tag;
			}
			let dataurl = (url == '') ? '{{route('api.jobs',["page"=>1])}}' : url;  
			<?php  
			if($data2['type'] == 'specific') {
			?>
				// var completeUrl = "{{url()->current()}}";
				// splitted = completeUrl.split('/');
				dataurl = 	'{{route('api.jobs',["page"=>1, "institute" => $data2['url_key'] ])}}'
				that.customPaginate = true;
				if (url !== ''){
					let params = (new URL(url)).searchParams;
					if(params.get("page") !== undefined && parseInt(params.get("page")) > 1 ){
						let params_dataurl = (new URL(dataurl));
						params_dataurl.searchParams.set('page', params.get('page'))
						dataurl = params_dataurl.toString();
					}
				}
			
			<?php 
			}
			?>
			window.axios.get(dataurl)
			.then( function( response ) {
				if(response.data){
					that.loadingItem = false;
					tag.addItems(response.data);
					var pages = Math.ceil(response.data.total / 4);
					$('.pagination-job')[0].innerHTML = '';
					for (let index = 1; index <= pages; index++) {
						// if(that.customPaginate){
							let paginated_dataurl = (new URL(dataurl));
							paginated_dataurl.searchParams.set('page', index)
							dataurl = paginated_dataurl.toString();
						// }
						$('.pagination-job')[0].innerHTML += "<button onclick='paginate(this)' style='background-color: " + (response.data.current_page == index ? '#3388EE;':'auto;') + "' data-href='"+ dataurl +""  + "' class='button border-rad-10'>" + index + "</button>";
					}
					that.responseData = response.data;
				}
			}).catch( function( error ) {
				console.log( error );
			});
		}
		function paginate(e) {
			this.loadUserJobs(e, that.listingTag, e.getAttribute('data-href'))
		}
		function selectedJobEvent(params) {
			that.jobSelectedTag.trigger('job_selected_event', params)
		}
		function listingCallback(tag) {
			that.jobSelectedTag = tag;
		}
		let authenticated = {{ Auth::check() ? 'true' : 'false' }};
		window.riot.mount( 'job-listings', { 
			baseApiPath: '{{ url( 'api' ) }}',
			loading: loadingItem,
			selectedJobProp: selectedJobEvent,
			load: loadUserJobs,
			editUrl: '{{route("institution.job.create")}}',
			delLink: '{{route("institution.job.delete")}}'
		} );
			
		window.riot.mount( 'job-selected', { 
			baseApiPath: '{{ url( 'api' ) }}',
			basePath: '{{ url( '/' ) }}',
			callback: listingCallback,
			authenticated: authenticated, 
			home_url: '{{route('index')}}',
			curr_user: @json(Auth::check() ? ['user_id'=> Auth::user()->user_id, 'username'=> Auth::user()->username, 'cv' => Auth::user()->cv_url] : []),
			reloadListings: reload
			// itemSelected: selectedJob,
		} );
		
	</script>

@endsection